<?php

/**
 * This file is part of the Lasalle Software library back-end package. 
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019-2024 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Authentication\Models;

// LaSalle Software
use Lasallesoftware\Librarybackend\Authentication\Models\Login;

// Laravel Facade
use Illuminate\Support\Facades\DB;

// Third Party Classes
use Carbon\CarbonImmutable;


trait PersonbydomainNovaFormProcessing
{
    /*
    ********************************************************************************************************************
                                                ** IMPORTANT NOTE **
    ********************************************************************************************************************
    I am finding that the processing emanating from my Nova resource (Lasallesoftware\Novabackend\Nova\Resources\Personbydomain)
    is getting quite involved. So much so that I think that it is time to create a separate class for it.

    I am depending on model events for processing after the form is submitted. I am probably at, or on the cusp, of abusing
    model events.

    The "personbydomains" database table is the table used for authentication. When you "Auth::user()", you are hitting
    this db table. There is NO users table -- I deleted it! Instead, my custom LaSalleGuard points to the "personbydomains"
    db table.

    The main reason for "personbydomains" is multiple domains. The key idea is:

                     ONE PERSONBYDOMAIN RECORD -> ONE UNIQUE EMAIL ADDRESS -> ONE DOMAIN

    A secondary reason is to split a loggable user ("personbydomain" db table) from a non-log-in-able person ("persons"
    db table) I want in the database.

    When someone registers via the public facing registration form, the
    Lasallesoftware\Librarybackend\Authentication\Http\Controllers\RegisterController handles the multiple steps involved.

    As unseemly as it is to have this stuff all in the controller, it is easier to put it all there for the registration
    and then figure out later if perhaps it is better to do some refactoring. Not quite knowing how the "personbydomains"
    management will look like up when I started work on LSv2, and seeing that this controller is dedicated to registration,
    I am leaving this code there. It turns out to be a wonderful basis for figuring out the Nova processing.

    "personbydomains" must have a "persons". MUST!!

    The email in "personbydomains" must exist in "emails". MUST!!

    Yes, "personbydomains" has redundant fields, in part as a convenience, and in part due to the Laravel Guard strictures.

    So, "personbydomains" is a unique type of database table.

    ********************************************************************************************************************
    */



    /**
     * Process the Nova create form for personbydomain.
     *
     * This method is static as it is called by a personbydomain model event, which itself is static
     *
     * @param  Personbydomain  $personbydomain
     */
    public static function processTheCreateNovaForm(Personbydomain $personbydomain)
    {
        // the form provides the person_id... now need to get the person_first_name and person_surname from "persons"
        // and save to "personbydomain"
        self::processCreatePerson($personbydomain);

        // the form provides an email... need to do full processing
        self::processEmailaddress($personbydomain);

        // the form provides the installed_domain_id, although I do not think that's what it is called
        // need to get the installed_domain_title from "installed_domains" and save it here
        self::processDomain($personbydomain);

        // process banned fields
        self::processBan($personbydomain);
    }

    /**
     * Process the Nova update form for personbydomain.
     *
     * This method is static as it is called by a personbydomain model event, which itself is static
     *
     * @param  Personbydomain  $personbydomain
     */
    public static function processTheUpdateNovaForm(Personbydomain $personbydomain)
    {
        // the form provides the person_id... now need to get the person_first_name and person_surname from "persons"
        // and save to "personbydomain"
       // self::processCreatePerson($personbydomain);

        // the form provides an email... need to do full processing
        self::processEmailaddress($personbydomain);

        // the form provides the installed_domain_id, although I do not think that's what it is called
        // need to get the installed_domain_title from "installed_domains" and save it here
        //self::processDomain($personbydomain);

        // process banned fields
        self::processBan($personbydomain);
    }


    ///////////////////////////////////////////////////////////////////
    ////////////          MAIN PROCESSING           ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Process the stuff related to the persons database table for the create
     *
     * @param  Personbydomain  $personbydomain
     */
    protected static function processCreatePerson(Personbydomain $personbydomain)
    {
        // persons fields
        self::populatedPersonFirstNameField($personbydomain);
        self::populatedPersonSurnameField($personbydomain);
        self::populatedPersonNamealculatedField($personbydomain);

        // email address
        self::processEmailaddress($personbydomain);

        // domain
        self::processDomain($personbydomain);
    }


    ///////////////////////////////////////////////////////////////////
    ////////////         PERSONS HANDLING           ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Populate the person_first_name field
     *
     * @param  Personbydomain  $personbydomain
     */
    protected static function populatedPersonFirstNameField(Personbydomain $personbydomain)
    {
        $personbydomain->person_first_name = self::getPersonFirstNameFromId($personbydomain->person_id);
    }

    /**
     * Get the first_name field from the persons db table using the ID
     *
     * @param  int      $personId        The person_id from the Nova form
     * @return int
     */
    protected static function getPersonFirstNameFromId($personId)
    {
        return DB::table('persons')
            ->where('id', $personId)
            ->pluck('first_name')
            ->first()
        ;
    }

    /**
     * Populate the person_surname field
     *
     * @param  Personbydomain  $personbydomain
     */
    protected static function populatedPersonSurNameField(Personbydomain $personbydomain)
    {
        $personbydomain->person_surname = self::getPersonSurnameFromId($personbydomain->person_id);
    }

    /**
     * Populate the name_calculated field
     *
     * @param  Personbydomain  $personbydomain
     */
    protected static function populatedPersonNamealculatedField(Personbydomain $personbydomain)
    {
        $first_name = self::getPersonFirstNameFromId($personbydomain->person_id);
        $surname    = self::getPersonSurnameFromId($personbydomain->person_id);
        $personbydomain->name_calculated = $first_name . " " . $surname;
    }

    /**
     * Get the surname field from the persons db table using the ID
     *
     * @param  int      $personId        The person_id from the Nova form
     * @return int
     */
    protected static function getPersonSurnameFromId($personId)
    {
        return DB::table('persons')
            ->where('id', $personId)
            ->pluck('surname')
            ->first()
        ;
    }


    ///////////////////////////////////////////////////////////////////
    ////////////     EMAIL ADDRESS HANDLING         ///////////////////
    ///////////////////////////////////////////////////////////////////

    protected static function processEmailaddress(Personbydomain $personbydomain)
    {
        // Is the email address entered into the Nova form already in the emails db table?
        if (! self::isEmailaddressAlreadyInTheEmailsTable($personbydomain->email)) {

            // The email address entered into the Nova form is NOT in the emails db table

            // (i)   insert the new email address entered into the form into the emails db table
            self::populateEmailsTable($personbydomain);

            // (ii)  insert new email address and person into the person_email pivot table
            self::populatePersonemailTable($personbydomain->person_id, self::getEmailIdWithEmailaddress($personbydomain->email));

            // (iii) insert/update new email address into the personbydomains db table
            // the Nova form processing will populate personbydomains' email field.

            return;
        }

        // Is the email address entered into the Nova form already in the person_email db pivot table?
        if (! self::isEmailaddressAlreadyInThePersonemailPivotTable(self::getEmailIdWithEmailaddress($personbydomain->email))) {

            // The email address entered into the Nova form is NOT in the person_email db pivot table
            // (and is in the emails db table already)

            // (i)  insert the new email address and person into the person_email pivot table
            self::populatePersonemailTable($personbydomain->person_id, self::getEmailIdWithEmailaddress($personbydomain->email));

            // (ii) insert/update new email address into the personbydomains db table
            // the Nova form processing will populate personbydomains' email field.

            return;
        }

        // Still here? Then the email address entered into the Nova form is already in the emails and person_email db tables already.
        // So, just insert/update the new email address into the personbydomains db table
        // the Nova form processing will populate personbydomains' email field.

        return;
    }

    /**
     * Is the specified email address already in the emails db table?
     *
     * @param  string  $emailaddress
     * @return bool
     */
    protected static function isEmailaddressAlreadyInTheEmailsTable($emailaddress)
    {
        return (DB::table('emails')->where('email_address', $emailaddress)->first()) ? true : false;
    }

    /**
     * Is the email address, as specified by its ID in the emails db table, in the person_email db pivot table?
     *
     * @param  int  $emailId
     * @return bool
     */
    protected static function isEmailaddressAlreadyInThePersonemailPivotTable($emailId)
    {
       return (DB::table('person_email')->where('email_id', $emailId)->first()) ? true : false;
    }

    /**
     * Populate the emails table
     *
     * @param  Personbydomain  $personbydomain
     * @return bool
     */
    protected static function populateEmailsTable(Personbydomain $personbydomain)
    {
        return DB::table('emails')->insert([
            'lookup_email_type_id' => 4,
            'email_address'        => $personbydomain->email,
            'description'          => 'Created by a personbydomain model event.',
            'comments'             => 'Created by a personbydomain model event.',
            'uuid'                 => $personbydomain->uuid,
            'created_at'           => $personbydomain->created_at,
            'created_by'           => $personbydomain->created_by,
        ]);
    }

    /**
     * Get the emails db table's ID using the specified email address
     *
     * @param  string   $emailaddress
     * @return int
     */
    protected static function getEmailIdWithEmailaddress($emailaddress)
    {
        return DB::table('emails')
            ->where('email_address', $emailaddress)
            ->pluck('id')
            ->first()
        ;
    }

    /**
     * Populate the person_email db pivot table
     *
     * @param  int   $personId
     * @param  int   $emailId
     * @return bool
     */
    protected static function populatePersonemailTable($personId, $emailId)
    {
        return DB::table('person_email')->insert([
            'person_id' => $personId,
            'email_id'  => $emailId,
        ]);
    }


    ///////////////////////////////////////////////////////////////////
    ////////////          DOMAIN HANDLING           ///////////////////
    ///////////////////////////////////////////////////////////////////

    protected static function processDomain(Personbydomain $personbydomain)
    {
        // Double checking that the authenticated user running the Nova form is ok to assign the domain that they assigned.

        // Want to get the name of the domain so we can pop it into the personbydomains' installed_domain_title field
        $personbydomain->installed_domain_title = self::getInstalleddomaintitleWithId($personbydomain->installed_domain_id);

    }

    /**
     * Get the emails db table's ID using the specified email address
     *
     * @param $id
     * @return mixed
     */
    protected static function getInstalleddomaintitleWithId($id)
    {
        return DB::table('installed_domains')
            ->where('id', $id)
            ->pluck('title')
            ->first()
        ;
    }


    ///////////////////////////////////////////////////////////////////
    ////////////          BANNED HANDLING           ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Process a ban
     *
     * @param  Personbydomain  $personbydomain
     */
    protected static function processBan(Personbydomain $personbydomain)
    {
        self::populatedBannedAtField($personbydomain);
        self::populatedBannedCommentsField($personbydomain);
        self::deleteLoginsRecordsByPersonbydomainId($personbydomain->id);
    }

    /**
     * Populate the banned datetime field.
     *
     * @param  Personbydomain  $personbydomain
     * @return mixed
     */
    protected static function populatedBannedAtField(Personbydomain $personbydomain)
    {
        if (($personbydomain->banned_enabled == 1) && (is_null($personbydomain->banned_at))) {
            $personbydomain->banned_at = CarbonImmutable::now();
        }
        
        if ($personbydomain->banned_enabled == 0) {
            $personbydomain->banned_at = NULL;
        }        
    }

    /**
     * If a personbydomain is banned, then
     *
     * @param  Personbydomain  $personbydomain
     * @return mixed
     */
    protected static function populatedBannedCommentsField(Personbydomain $personbydomain)
    {
        $personbydomain->banned_comments = (is_null($personbydomain->banned_comments)) ? NULL : trim($personbydomain->banned_comments);
    }


    ///////////////////////////////////////////////////////////////////
    ////////////            LOGINS TABLE            ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Delete logins database table records with a specific personbydomain_id
     *
     * @param  int    $personbydomainId     The personbydomain_id
     * @return mixed
     */
    protected static function deleteLoginsRecordsByPersonbydomainId($personbydomainId)
    {
        $login = new Login;
        $login->deleteLoginsByPersonbydomainId($personbydomainId);
    }
}
