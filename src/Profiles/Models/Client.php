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
 * @copyright  (c) 2019-2022 The South LaSalle Trading Corporation
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
 * This is the model class for clients.
 *
 * @package Lasallesoftware\Librarybackend\Profiles\Models
 */
class Client extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'clients';

    /**
     * The attributes that aren't mass assignable.
     *
     * ['*'] is treated as "all fields are guarded"
     * https://github.com/laravel/framework/blob/5.7/src/Illuminate/Database/Eloquent/Concerns/GuardsAttributes.php#L164
     *
     * @var array
     */
    protected $guarded = ['personbydomain_id', 'comments'];

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
     * One client may have lots of personbydomains associated with it.
     * One personbydomain may be associated with a lot of different clients.
     * Therefore, need a pivot table to implement this "many to many" relationship.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain', 'personbydomain_client', 'client_id', 'personbydomain_id');
    }

    /*
     * One to one relationship with Company.
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
        return $this->belongsTo('Lasallesoftware\Librarybackend\Profiles\Models\Company');
    }

    /*
     * One to one relationship with podcast_show.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function podcast_show()
    {
        if ( class_exists('Lasallesoftware\Podcastbackend\Models\Podcast_show') ) {
            return $this->hasMany('\Lasallesoftware\Podcastbackend\Models\Podcast_show');
        }
    }

    /*
     * One to one relationship with podcast_episode.
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
            return $this->hasMany('\Lasallesoftware\Podcastbackend\Models\Podcast_episode');
        }
    }

    /*
     * One to one relationship with podcast_show_podcast_directories.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function podcast_show_podcast_directory()
    {
        if ( class_exists('Lasallesoftware\Podcastbackend\Models\podcast_show_podcast_directory') ) {
            return $this->hasMany('\Lasallesoftware\Podcastbackend\Models\podcast_show_podcast_directory');
        }
    }
    

    /* *********************************************************** */
    /*                   START: VIDEO PACKAGE                    */
    /* *********************************************************** */

    /*
     * One to one relationship with video_show.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function video_show()
    {
        if ( class_exists('Lasallesoftware\Videobackend\Models\Video_show') ) {
            return $this->hasMany('\Lasallesoftware\Videobackend\Models\Video_show');
        }
    }

    /*
     * One to one relationship with video_episode.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function video_episode()
    {
        if ( class_exists('Lasallesoftware\Videobackend\Models\Video_episode') ) {
            return $this->hasMany('\Lasallesoftware\Videobackend\Models\Video_episode');
        }
    }

    /* *********************************************************** */
    /*                   END: VIDEO PACKAGE                        */
    /* *********************************************************** */
}