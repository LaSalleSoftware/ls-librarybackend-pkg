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

// Laravel facades
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for installed_domain.
 *
 * This is a lookup table.
 *
 * @package Lasallesoftware\Librarybackend\Profiles\Models
 */
class Installed_domain extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'installed_domains';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
        'enabled'
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
        'enabled'    => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'locked_at'  => 'datetime',
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
        static::creating(function($installed_domain) {
            self::populateTitleField($installed_domain);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($installed_domain) {
            self::populateTitleField($installed_domain);
        });
    }

    /**
     * Populate the "title" field.
     *
     * @param  Installed_domain  $installed_domain
     */
    private static function populateTitleField(Installed_domain $installed_domain)
    {
        // without any "save", this following statement actually populates the field!
        $installed_domain->title = self::deepWashText($installed_domain->title);
    }


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to one relationship with personbydomain.
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
        return $this->hasMany('\Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain');
    }

    /*
     * One to one relationship with installed_domain_jwt_key.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function installed_domains_jwt_key()
    {
        return $this->hasMany('\Lasallesoftware\Librarybackend\Authentication\Models\Installed_domains_jwt_key');
    }

    /*
     * One to one relationship with contact_form.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
     public function contact_form()
    {
        return $this->hasMany('Lasallesoftware\Contactformbackend\Models\Contact_form');
    }


    /* *********************************************************** */
    /*                     START: BLOG PACKAGE                     */
    /* *********************************************************** */

    /*
     * A category may have one, and only one, domain.
     *
     * The category database table has the field "installed_domain_id". So the post model specifies "hasOne".
     * The installed_domain is the "inverse of the relationship" (per the lexicon of https://laravel.com/docs/5.8/eloquent-relationships#one-to-one)
     *
     * Method name must be the model name, *not* the table name
     *
     * @return Eloquent
     */
    public function category()
    {
        if ( class_exists('Lasallesoftware\Blogbackend\Models\Category') ) {

            return $this->hasMany('Lasallesoftware\Blogbackend\Models\Category');
        }
    }

    /*
     * A tag may have one, and only one, domain.
     *
     * The tag database table has the field "installed_domain_id". So the post model specifies "hasOne".
     * The installed_domain is the "inverse of the relationship" (per the lexicon of https://laravel.com/docs/5.8/eloquent-relationships#one-to-one)
     *
     * Method name must be the model name, *not* the table name
     *
     * @return Eloquent
     */
    public function tag()
    {
        if ( class_exists('Lasallesoftware\Blogbackend\Models\Tag') ) {

            return $this->hasMany('Lasallesoftware\Blogbackend\Models\Tag');
        }
    }

    /*
     * A post may have one, and only one, domain.
     *
     * The post database table has the field "installed_domain_id". So the post model specifies "hasOne".
     * The installed_domain is the "inverse of the relationship" (per the lexicon of https://laravel.com/docs/5.8/eloquent-relationships#one-to-one)
     *
     * Method name must be the model name, *not* the table name
     *
     * @return Eloquent
     */
    public function post()
    {
        if ( class_exists('Lasallesoftware\Blogbackend\Models\Post') ) {

            return $this->belongsTo('Lasallesoftware\Blogbackend\Models\Post');
        }
    }

    /*
     * A postupdate may have one, and only one, domain.
     *
     * The postupdate database table has the field "installed_domain_id". So the post model specifies "hasOne".
     * The installed_domain is the "inverse of the relationship" (per the lexicon of https://laravel.com/docs/5.8/eloquent-relationships#one-to-one)
     *
     * Method name must be the model name, *not* the table name
     *
     * @return Eloquent
     */
    public function postupdate()
    {
        if ( class_exists('Lasallesoftware\Blogbackend\Models\Postupdate') ) {

            return $this->belongsTo('Lasallesoftware\Blogbackend\Models\Postupdate');
        }
    }

    /* *********************************************************** */
    /*                      END: BLOG PACKAGE                      */
    /* *********************************************************** */

}
