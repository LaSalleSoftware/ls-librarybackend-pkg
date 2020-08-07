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
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
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

/**
 * This is the model class for company.
 *
 * @package Lasallesoftware\Librarybackend\Profiles\Models
 */
class Company extends CommonModel
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
    public $table = 'companies';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
        'comments',
        'profile',
        'featured_image',
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
        static::creating(function($company) {
            self::populateNameField($company);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($company) {
            self::populateNameField($company);
        });
    }

    /**
     * Populate the "name" field.
     *
     * @param  Company  $company
     */
    private static function populateNameField(Company $company)
    {
        // without any "save", this following statement actually populates the field!
        $company->name = self::deepWashText($company->name);
    }



    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * Many to many relationship with person.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Person', 'company_person', 'company_id', 'person_id');
    }

    /*
     * Many to many relationship with address.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Address', 'company_address', 'company_id', 'address_id');
    }

    /*
    * Many to many relationship with email.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Email', 'company_email', 'company_id', 'email_id');
    }

    /*
    * Many to many relationship with social.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Social', 'company_social', 'company_id', 'social_id');
    }

    /*
    * Many to many relationship with telephone.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Telephone', 'company_telephone', 'company_id', 'telephone_id');
    }

    /*
    * Many to many relationship with website.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Website', 'company_website', 'company_id', 'website_id');
    }

    /*
     * One to one relationship with Client.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function client()
    {
        return $this->hasOne('Lasallesoftware\Librarybackend\Profiles\Models\Client');
    }

    /*
    * Many to many relationship with podcast_links.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function podcast_link()
    {
        if ( class_exists('Lasallesoftware\Podcastbackend\Models\Podcast_link') ) {
            return $this->belongsToMany('Lasallesoftware\Podcastbackend\Models\Podcast_link', 'podcast_link_company', 'company_id', 'podcast_link_id');
        }
    }

    /*
    * Many to many relationship with podcast_research_notes.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function podcast_research_note()
    {
        if ( class_exists('Lasallesoftware\Podcastbackend\Models\Podcast_research_note') ) {
            return $this->belongsToMany('Lasallesoftware\Podcastbackend\Models\podcast_research_note_company', 
            'podcast_research_note_company', 
            'company_id', 
            'podcast_research_note_id');
        }
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
            'podcast_episode_sponsor', 
            'company_id', 
            'podcast_episode_id');
        }
    }
}
