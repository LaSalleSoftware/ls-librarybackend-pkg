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
 * This is the model class for social.
 *
 * @package Lasallesoftware\Librarybackend\Profiles\Models
 */
class Social extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'socials';

    /**
     * Which fields may be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'lookup_social_type_id',
        'url',
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
        static::creating(function($social) {
            self::populateUrlField($social);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($social) {
            self::populateUrlField($social);
        });
    }

    /**
     * Populate the "url" field when triggered by creating & updating model event.
     *
     * @param  Social  $social
     */
    protected static function populateUrlField(Social $social)
    {
        // without any "save", this following statement actually populates the "url" field!
        $social->url = self::washUrl($social->url);
    }


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
    public function lookup_social_type()
    {
        return $this->belongsTo('Lasallesoftware\Librarybackend\Profiles\Models\Lookup_social_type');
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
    public function person()
    {
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Person', 'person_social', 'social_id', 'person_id');
    }

    /*
     * A company can have many social sites, but a social site belongs to just one company.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Company', 'company_social', 'social_id', 'company_id');
    }
}
