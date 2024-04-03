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

namespace Lasallesoftware\Librarybackend\Profiles\Models;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Models\CommonModel;

// Laravel facades
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for email.
 *
 * @package Lasallesoftware\Librarybackend\Profiles\Models
 */
class Email extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'emails';

    /**
     * Which fields may be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'lookup_email_type_id',
        'email_address',
        'description',
        'comments',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * LaSalle Software handles the created_at and updated_at fields, so false.
     *
     * @var bool
     */
    public $timestamps = false;


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to one relationship with Lookup_email_type.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function lookup_email_type()
    {
        return $this->belongsTo('Lasallesoftware\Librarybackend\Profiles\Models\Lookup_email_type');
    }

    /*
     * A person can have many emails, but an email belongs to just one person.
     * Relationship is optional!
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function person()
    {
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Person', 'person_email', 'email_id', 'person_id');
    }

    /*
     * A company can have many emails, but an email belongs to just one company.
     * Relationship is optional!
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function company()
    {
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Company', 'company_email', 'email_id', 'company_id');
    }

    /*
     * One to many relationship with personbydomain.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function personbydomain()
    {
        return $this->hasMany('Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain', 'email');
    }
}
