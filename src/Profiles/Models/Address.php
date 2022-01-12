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

// Laravel facades
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for address.
 *
 * @package Lasallesoftware\Librarybackend\Profiles\Models
 */
class Address extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'addresses';

    /**
     * Which fields may be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'lookup_address_type_id',
        'title',
        'street1',
        'street2',
        'street3',
        'street4',
        'city',
        'province',
        'country',
        'postal_code',
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
        static::creating(function($address) {
            self::populateCalculatedField($address);
            self::populateMaplinkField($address);
            self::populateTitleField($address);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($address) {
            self::populateCalculatedField($address);
            self::populateMaplinkField($address);
            self::populateTitleField($address);
        });
    }

    /**
     * Populate the "address_calculated" field when triggered by creating & updating model event.
     *
     * @param  Address  $address
     */
    protected static function populateCalculatedField(Address $address) {

        // recreated the following in Lasallesoftware\Librarybackend\Rules\AddressUniqueRule

        $address_line_1 = trim($address->address_line_1) . ', ';
        $address_line_2 = $address->address_line_2 == null ? '' : trim($address->address_line_2) . ', ';
        $address_line_3 = $address->address_line_3 == null ? '' : trim($address->address_line_3) . ', ';
        $address_line_4 = $address->address_line_4 == null ? '' : trim($address->address_line_4) . ', ';
        $city           = $address->city           == null ? '' : trim($address->city)           . ', ';
        $province       = $address->province       == null ? '' : trim($address->province)       . ', ';
        $country        = $address->country        == null ? '' : trim($address->country)        . '  ';
        $postal_code    = $address->postal_code    == null ? '' : trim($address->postal_code);

        // without any "save", this following statement actually populates the "address_calculated" field!
        $address->address_calculated = $address_line_1 .
            $address_line_2 .
            $address_line_3 .
            $address_line_4 .
            $city .
            $province .
            $country .
            $postal_code
        ;
    }

    /**
     * Populate the "address_calculated" field when triggered by creating & updating model event.
     *
     * @param  Address  $address
     */
    private static function populateMaplinkField(Address $address)
    {
        $address->map_link = self::washUrl($address->map_link);
    }

    /**
     * Populate the "title" field.
     *
     * @param  Address  $address
     */
    private static function populateTitleField(Address $address)
    {
        // without any "save", this following statement actually populates the field!
        $address->title = self::deepWashText($address->title);
    }


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to one relationship with lookup_address_type.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function lookup_address_type()
    {
        return $this->belongsTo('Lasallesoftware\Librarybackend\Profiles\Models\Lookup_address_type');
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
    public function person()
    {
        return $this->belongsToMany(
            'Lasallesoftware\Librarybackend\Profiles\Models\Person',
            'person_address',
            'address_id',
            'person_id'
        );
    }

    /*
     * A company can have many addresses, but an address belongs to just one company.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Company', 'company_address', 'address_id', 'company_id');
    }
}
