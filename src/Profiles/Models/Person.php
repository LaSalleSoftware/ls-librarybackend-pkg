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
use Lasallesoftware\Librarybackend\Events\PersonPopulateNamecalculatedField;
use Lasallesoftware\Podcastguestmanagementbackend\Models\Podcast_guest_status;

/**
 * This is the model class for person.
 *
 * @package Lasallesoftware\Librarybackend\Profiles\Models
 */
class Person extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * The convention is plural -- and plural is assumed.
     *
     * Lowercase.
     *
     * @var string
     */
    public $table = 'persons';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'middle_name',
        'surname',
        'position',
        'description',
        'comments',
        'profile',
        'featured_image',
        'birthday',
        'anniversary',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * LaSalle Software handles the created_at and updated_at fields, so false.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'birthday'    => 'date',
        'anniversary' => 'date',
        'deceased'    => 'date',
    ];


    ///////////////////////////////////////////////////////////////////
    //////////////         MODEL EVENTS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The "booting" method of the model.
     *
     * Laravel will execute this function automatically
     * https://github.com/laravel/framework/blob/e6c8aa0e39d8f91068ad1c299546536e9f25ef63/src/Illuminate/Database/Eloquent/Model.php#L197
     *
     * @return void
     */
    protected static function boot()
    {
        // parent's boot function should occur first
        parent::boot();

        // Do this when the "creating" model event is dispatched
        // https://laracasts.com/discuss/channels/eloquent/is-there-any-way-to-listen-for-an-eloquent-event-in-the-model-itself
        //
        static::creating(function($person) {
            self::populateSalutationField($person);
            self::populateFirstnameField($person);
            self::populateMiddlenameField($person);
            self::populateSurnameField($person);
            self::populatePositionField($person);
            self::populateCalculatedField($person);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($person) {
            self::populateSalutationField($person);
            self::populateFirstnameField($person);
            self::populateMiddlenameField($person);
            self::populateSurnameField($person);
            self::populatePositionField($person);
            self::populateCalculatedField($person);
        });
    }

    /**
     * Populate the "salutation" field when triggered by creating & updating model event.
     *
     * @param  Person  $person
     */
    private static function populateSalutationField(Person $person)
    {
        $person->salutation = self::deepWashText($person->salutation);
    }

    /**
     * Populate the "first_name" field when triggered by creating & updating model event.
     *
     * @param  Person  $person
     */
    private static function populateFirstnameField(Person $person)
    {
        $person->first_name = self::deepWashText($person->first_name);
    }

    /**
     * Populate the "middle_name" field when triggered by creating & updating model event.
     *
     * @param  Person  $person
     */
    private static function populateMiddlenameField(Person $person)
    {
        $person->middle_name = self::deepWashText($person->middle_name);
    }

    /**
     * Populate the "surname" field when triggered by creating & updating model event.
     *
     * @param  Person  $person
     */
    private static function populateSurnameField(Person $person)
    {
        $person->surname = self::deepWashText($person->surname);
    }

    /**
     * Populate the "position" field when triggered by creating & updating model event.
     *
     * @param  Person  $person
     */
    private static function populatePositionField(Person $person)
    {
        $person->position = self::deepWashText($person->position);
    }

    /**
     * Populate the "name_calculated" field when triggered by creating & updating model event.
     *
     * @param  Person  $person
     */
    protected static function populateCalculatedField(Person $person)
    {
        // recreated the following in Lasallesoftware\Librarybackend\Rules\PersonsUniqueRule

        $first_name  = trim($person->first_name) . " ";
        $middle_name = $person->middle_name == null ? '' : trim($person->middle_name) . " ";
        $surname     = trim($person->surname);

        // without any "save", this following statement actually populates the "name_calculated" field!
        $person->name_calculated = $first_name .
            $middle_name .
            $surname
        ;
    }


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to many relationship with personbydomain.
     *
     * A person can have many domains they can log into
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
        return $this->hasOne('\Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain');
    }

    /*
     * One to many relationship with login.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function login()
    {
        return $this->hasMany('\Lasallesoftware\Librarybackend\Authentication\Models\Login');
    }

    /*
     * Many to many relationship with company.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Company', 'company_person', 'person_id', 'company_id');
    }

    /*
     * A person can have many addresses, but an address belongs to just one person.
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
    public function address()
    {
        return $this->belongsToMany(
            'Lasallesoftware\Librarybackend\Profiles\Models\Address',
            'person_address',
            'person_id',
            'address_id'
        );
    }

    /*
    * A person can have many emails, but an email belongs to just one person.
    * An email can belong to a company, not a person.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function email()
    {
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Email', 'person_email', 'person_id', 'email_id');
    }

    /*
    * A person can have many social sites, but a social site belongs to just one person.
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
    public function social()
    {
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Social', 'person_social', 'person_id', 'social_id');
    }

    /*
    * A person can have many telephone numbers, but a telephone number belongs to just one person.
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
    public function telephone()
    {
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Telephone', 'person_telephone', 'person_id', 'telephone_id');
    }

    /*
    * A person can have many websites, but a website belongs to just one person.
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
    public function website()
    {
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Website', 'person_website', 'person_id', 'website_id');
    }

    /*
    * Many to many relationship with podcast_episode.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function podcast_episode()
    {
        if ( class_exists('Lasallesoftware\Podcastbackend\Models\Podcast_episode') ) {
            return $this->belongsToMany('Lasallesoftware\Podcastbackend\Models\Podcast_episode', 
            'podcast_episode_guest', 
            'person_id', 
            'podcast_episode_id');
        }
    }

   
    public function podcast_guest_status()
    {
        if ( class_exists('Lasallesoftware\Podcastguestmanagementbackend\Models\Podcast_guest_status') ) {
            return $this->hasMany('Lasallesoftware\Podcastguestmanagementbackend\Models\Podcast_guest_status');
        }
    }
}