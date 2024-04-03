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

namespace Lasallesoftware\Librarybackend\Authentication\Models;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Models\CommonModel;

// Laravel facades
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for installed_domain.
 *
 * @package Lasallesoftware\Librarybackend\Authentication\Models
 */
class Installed_domains_jwt_key extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'installed_domains_jwt_keys';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [];

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
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to many (inverse) relationship with installed_domain.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function installed_domain()
    {
        return $this->belongsTo('Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain');
    }




    ///////////////////////////////////////////////////////////////////
    //////////////            GOODIES!              ///////////////////
    ///////////////////////////////////////////////////////////////////


    /**
     * Get the key for a give installed domain's ID
     *
     * @param  int        $installed_domain_id       The installed domain's ID
     * @return string
     */
    public function getKeyGivenId(int $installed_domain_id) : string
    {
        $key = Installed_domains_jwt_key::where('installed_domain_id', $installed_domain_id)
            ->where('enabled', 1)
            ->pluck('key')
            ->first()
        ;   
    
        if (is_null($key)) return 'key not found for installed domain #' . $installed_domain_id;

        return $key;
    }
}
