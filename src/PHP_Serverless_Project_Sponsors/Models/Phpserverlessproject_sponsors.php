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
 * @copyright  (c) 2019-2025 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

 namespace Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Models;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Models\CommonModel;


class Phpserverlessproject_sponsors extends CommonModel
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
    public $table = 'phpserverlessproject_sponsors';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'full_name',
        'image_url',
        'image_thumbnail_url',
        'profile',
        'email_address',
        'umbrella_sponsor',
        'basecamp_sponsor',
        'restream_sponsor',
        'internal_comment',
        'enabled',
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
        static::creating(function($phpserverlessproject_sponsors) {
            self::populateFullnameField($phpserverlessproject_sponsors);
            self::populateUrlFields($phpserverlessproject_sponsors);
            self::populateProfileField($phpserverlessproject_sponsors);
            self::populateInternal_commentField($phpserverlessproject_sponsors);
            self::populateEmail_addressField($phpserverlessproject_sponsors);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($phpserverlessproject_sponsors) {
            self::populateFullnameField($phpserverlessproject_sponsors);
            self::populateUrlFields($phpserverlessproject_sponsors);
            self::populateProfileField($phpserverlessproject_sponsors);
            self::populateInternal_commentField($phpserverlessproject_sponsors);
            self::populateEmail_addressField($phpserverlessproject_sponsors);
        });
    }

    /**
     * Populate the "name" field.
     *
     * @param  Phpserverlessproject_sponsors  $phpserverlessproject_sponsors
     */
    private static function populateFullnameField(Phpserverlessproject_sponsors $phpserverlessproject_sponsors)
    {
        // without any "save", this following statement actually populates the field!
        $phpserverlessproject_sponsors->full_name = self::deepWashText($phpserverlessproject_sponsors->full_name);
    }

    /**
     * Populate the "url" field when triggered by creating & updating model event.
     *
     * @param  Phpserverlessproject_sponsors  $phpserverlessproject_sponsors
     */
    protected static function populateUrlFields(Phpserverlessproject_sponsors $phpserverlessproject_sponsors)
    {
        // without any "save", this following statement actually populates the "url" field!
        $phpserverlessproject_sponsors->image_url           = self::washUrl($phpserverlessproject_sponsors->image_url);
        $phpserverlessproject_sponsors->image_thumbnail_url = self::washUrl($phpserverlessproject_sponsors->image_thumbnail_url);
    }

    protected static function populateProfileField(Phpserverlessproject_sponsors $phpserverlessproject_sponsors)
    {
        $phpserverlessproject_sponsors->profile = self::deepWashText($phpserverlessproject_sponsors->profile);
    }
    
    protected static function populateInternal_commentField(Phpserverlessproject_sponsors $phpserverlessproject_sponsors)
    {
        $phpserverlessproject_sponsors->internal_comment = self::deepWashText($phpserverlessproject_sponsors->internal_comment);
    }
    
    protected static function populateEmail_addressField(Phpserverlessproject_sponsors $phpserverlessproject_sponsors)
    {
        $phpserverlessproject_sponsors->email_address = self::washContent($phpserverlessproject_sponsors->email_address);
    }



    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    // there are no relationships

}