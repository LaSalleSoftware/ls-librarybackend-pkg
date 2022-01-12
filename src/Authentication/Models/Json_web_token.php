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

namespace Lasallesoftware\Librarybackend\Authentication\Models;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Models\CommonModel;

// Laravel Framework
use Illuminate\Support\Carbon;

// Laravel facades
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for Json_web_tokens.
 *
 * @package Lasallesoftware\Librarybackend\Authentication\Models
 */
class Json_web_token extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'json_web_tokens';

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
     * New JWT
     *
     * @param string  $jwt   The JSON Web Token
     * @return bool
     */
    public function saveWithJWT($jwt)
    {
        $json_web_tokens = new $this;
        $json_web_tokens->jwt = $jwt;
        $json_web_tokens->save();
    }

    /**
     * Delete expired JWT tokens
     *
     * @return void
     */
    public function deleteExpired()
    {
        $expiredAt = Carbon::now()->subMinutes($this->minutesToExpiration());

        $this->where('created_at', '<', $expiredAt)->delete();
    }

    /**
     * How many minutes until expiration?
     *
     * @return int
     */
    protected function minutesToExpiration()
    {
        return (24 * 60) - 10;
    }
}
