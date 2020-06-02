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
 * Class lookup_address_type.
 *
 * This is a lookup table.
 *
 * @package Lasallesoftware\Librarybackend\Profiles\Models
 */
class Lookup_address_type extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'lookup_address_types';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'title',
        'description',
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
        static::creating(function($lookup_address_type) {
            self::populateTitleField($lookup_address_type);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($lookup_address_type) {
            self::populateTitleField($lookup_address_type);
        });
    }

    /**
     * Populate the "title" field.
     *
     * @param  Lookup_address_type  $lookup_address_type
     */
    private static function populateTitleField(Lookup_address_type $lookup_address_type)
    {
        // without any "save", this following statement actually populates the field!
        $lookup_address_type->title = self::deepWashText($lookup_address_type->title);
    }


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to one relationship with address
     *
     * Method name must be the model name, *not* the table name
     *
     * @return Eloquent
     */
    public function address()
    {
        return $this->hasMany('Lasallesoftware\Librarybackend\Profiles\Models\Address');
    }
}
