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
 * @copyright  (c) 2019-2021 The South LaSalle Trading Corporation
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
 * This is the model class for telephone.
 *
 * @package Lasallesoftware\Librarybackend\Profiles\Models
 */
class Telephone extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'telephones';

    /**
     * Which fields may be mass assigned
     *
     * @var array
     */
    protected $fillable = [
        'lookup_telephone_type_id',
        'telephone_number',
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
        static::creating(function($telephone) {
            self::populateCountrycodeField($telephone);
            self::populateAreacodeField($telephone);
            self::populateTelephonenumberField($telephone);
            self::populateCalculatedField($telephone);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($telephone) {
            self::populateCountrycodeField($telephone);
            self::populateAreacodeField($telephone);
            self::populateTelephonenumberField($telephone);
            self::populateCalculatedField($telephone);
        });
    }

    /**
     * Populate the "country_code" field when triggered by creating & updating model event.
     *
     * @param  Telephone  $telephone
     */
    private static function populateCountrycodeField($telephone)
    {
        if (
            (is_null($telephone->country_code))              ||
            ($telephone->country_code == "")                 ||
            ($telephone->country_code == " ")                ||
            (strtolower($telephone->country_code) == 'null')
        ) {
            $telephone->country_code = 1;
        }
    }

    /**
     * Populate the "area_code" field when triggered by creating & updating model event.
     *
     * @param  Telephone  $telephone
     */
    private static function populateAreacodeField($telephone)
    {
        $telephone->area_code = self::stripCharactersFromText1($telephone->area_code);
    }

    /**
     * Populate the "telephone_number" field when triggered by creating & updating model event.
     *
     * @param  Telephone  $telephone
     */
    private static function populateTelephonenumberField($telephone)
    {
        $telephone->telephone_number = self::stripCharactersFromText1($telephone->telephone_number);
    }

    /**
     * Populate the "telephone_calculated" field when triggered by creating & updating model event.
     *
     * @param  Telephone  $telephone
     */
    private static function populateCalculatedField(Telephone $telephone)
    {
        // recreated the following in Lasallesoftware\Librarybackend\Rules\TelephonesUniqueRule

        $country_code     = self::stripCharactersFromText1(trim($telephone->country_code));

        $area_code        = self::stripCharactersFromText1(trim($telephone->area_code));
        $area_code        = self::formatAreaCode($area_code);

        $telephone_number = self::stripCharactersFromText1(trim($telephone->telephone_number));
        $telephone_number = self::formatTelephoneNumber($telephone_number);

        $extension        = $telephone->extension == null ? '' : ' ' . trim($telephone->extension);

        // without any "save", this following statement actually populates the "telephone_calculated" field!
        $telephone->telephone_calculated = $country_code . ' ' . $area_code . ' ' . $telephone_number . $extension;
    }

    /**
     * Modify "ttttttt" to "ttt-tttt".
     *
     * @param  string  $text
     * @return string
     */
    private static function formatTelephoneNumber($text)
    {
        if (strlen(trim($text)) == 7) {
            $text = substr($text, 0,3) . '-' . substr($text, 3,4);
        }
        return $text;
    }

    /**
     * Modify "ttt" to "(ttt)".
     *
     * @param  string  $text
     * @return string
     */
    private static function formatAreaCode($text)
    {
        if (strlen(trim($text)) == 3) {
            return '(' . $text . ')';
        }
        return $text;
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
    public function lookup_telephone_type()
    {
        return $this->belongsTo('Lasallesoftware\Librarybackend\Profiles\Models\Lookup_telephone_type');
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
    public function person()
    {
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Person', 'person_telephone', 'telephone_id', 'person_id');
    }

    /*
     * A company can have many telephone numbers, but a telephone number belongs to just one company.
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
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Company', 'company_telephone', 'telephone_id', 'company_id');
    }
}
