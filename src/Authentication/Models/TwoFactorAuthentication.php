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

namespace Lasallesoftware\Librarybackend\Authentication\Models;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Models\CommonModel;

// Laravel class
use Illuminate\Support\Carbon;

// Laravel facade
use Illuminate\Support\Facades\DB;

/**
 * This is the model class for TwoFactorAuthentication.
 *
 * @package Lasallesoftware\Librarybackend\Authentication\Models
 */
class TwoFactorAuthentication extends CommonModel
{
    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'twofactorauthentication';

    /**
     * The attributes that aren't mass assignable.
     *
     * ['*'] is treated as "all fields are guarded"
     * https://github.com/laravel/framework/blob/5.7/src/Illuminate/Database/Eloquent/Concerns/GuardsAttributes.php#L164
     *
     * @var array
     */
    protected $guarded = ['*'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
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
    //////////////         CRUD ACTIONS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Create a new twofactorauthentication database table record
     *
     * @param  array $data
     * @return mixed
     */
    public function createNewTwoFactorAuthenticationRecord($data)
    {
        $twofactorauthentication = new TwoFactorAuthentication;

        $twofactorauthentication->email                          = $data['email'];
        $twofactorauthentication->two_factor_code                = $data['two_factor_code'];
        $twofactorauthentication->number_of_attempts_to_validate = 0;
        $twofactorauthentication->created_at                     = Carbon::now(null);


        if ($twofactorauthentication->save()) {
            // Return the new ID
            return $twofactorauthentication->id;
        }
        return false;
    }

    /**
     * Delete twofactorauthentication records with a specific email
     *
     * @param  string  $email  The email address.
     * @return bool
     */
    public function deleteTwoFactorAuthenticationWithEmail($email)
    {
         return DB::table('twofactorauthentication')->where('email', '=', $email)->delete();
    }


    ///////////////////////////////////////////////////////////////////
    //////////////              MISC                ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Increment the "number_of_attempts_to_validate" field by 1
     *
     * @param  string  $email  The email address.
     * @return bool
     */
    public function incrementNumberOfAttemptsToValidate($email)
    {
        return DB::table('twofactorauthentication')->where('email', '=', $email)->increment('number_of_attempts_to_validate');
    }

    /**
     * Return the value of the "number_of_attempts_to_validate" field for a given email address
     *
     * @param  string  $email  The email address.
     * @return bool
     */
    public function totalNumberOfAttemptsToValidate($email)
    {
        return DB::table('twofactorauthentication')->where('email', '=', $email)->pluck('number_of_attempts_to_validate')->first();
    }

    /**
     * Value of the "two_factor_code" field for a given email address 
     *
     * @param  string  $email  The email address.
     * @return bool
     */
    public function getTwoFactorCodeValue($email)
    {
        return DB::table('twofactorauthentication')->where('email', '=', $email)->pluck('two_factor_code')->first();
    }


    /**
     * Has a two factor code expired?
     *
     * @param  string  $email  The email address.
     * @return bool
     */
    public function isTwoFactorCodeExpired($email)
    {
        $expiredAt = Carbon::now()->subSeconds($this->secondsToExpiration());

        return ($this->where('email', $email)->where('created_at', '<', $expiredAt)->count() > 0) ? true : false;
    }

    /**
     * How many seconds until expiration?
     *
     * @return int
     */
    protected function secondsToExpiration()
    {
        $minutesToExpiration = config('lasallesoftware-librarybackend.number_of_minutes_until_a_two_factor_code_expires');

        return $minutesToExpiration * 60;
    }   
}
