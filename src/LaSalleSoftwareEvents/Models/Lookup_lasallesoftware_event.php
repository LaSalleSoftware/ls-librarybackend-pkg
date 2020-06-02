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

namespace Lasallesoftware\Librarybackend\LaSalleSoftwareEvents\Models;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Models\CommonModel;

// Laravel facades
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for lookup_lasallesoftware_event.
 *
 * This is a lookup table,
 *
 * @package Lasallesoftware\Librarybackend\LaSalleSoftwareEvents\Models
 */
class Lookup_lasallesoftware_event extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'lookup_lasallesoftware_events';

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


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to one relationship with uuid.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function uuid()
    {
        return $this->hasOne('Lasallesoftware\Library\UniversallyUniqueIDentifiers\Uuid');
    }
}
