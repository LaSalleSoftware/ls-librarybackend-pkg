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

namespace Lasallesoftware\Librarybackend\Common\Policies;

// LaSalle Software
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;

// Laravel class
use Illuminate\Auth\Access\HandlesAuthorization;

// Laravel facade
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


/**
 * Class CommonPolicy
 *
 * @package Lasallesoftware\Librarybackend\Common\Policies
 */
class CommonPolicy
{
    use HandlesAuthorization;


    /**
     * Do not delete this model?
     *
     * @param $model
     * @return bool
     */
    public function isRecordDoNotDelete($model)
    {
        if (in_array($model->id, $this->recordsDoNotDelete)) {
            return true;
        }
    }

    /**
     * Get the lookup_role_id of the model's personbydomain
     *
     * @param  $model
     * @return mixed
     */
    public function getRoleIdOfTheModelPersonbydomain($model)
    {
        return DB::table('personbydomain_lookup_roles')
            ->where('personbydomain_id', $model->id)
            ->pluck('lookup_role_id')
            ->first()
        ;
    }


    /**
     * Should an action be accessible for a given individual podcast related model?
     * 
     * For the podcast related database tables, the most common permissions scenario is:
     * (i)   owner can do everything
     * (ii)  super admin and admin can do nothing
     * (iii) a user with the new "Client" role can do stuff for records that have their "client_id". 
     * 
     * This means that podcast related database tables must have a "client_id" field. This field resides
     * in the "personbydomain_client" db pivot table. So a **user** belongs to a client. Basically,
     * a logged-in client can only see their client records. 
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  Eloquent model                                                        $model
     * @return bool
     */
    public function getTypicalClientPermissions($user, $model)
    {
        if ($this->isUserRoleOwner($user)) return true;
 
        if ($this->isUserRoleClient($user) && ($model->client_id == $user->getClientId(Auth::id()))) return true; 

        return false;
    }

    /**
     * Is a given user an owner
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @return boolean
     */
    public function isUserRoleOwner($user)
    {
        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Is a given user a client
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @return boolean
     */
    public function isUserRoleClient($user)
    {
        return ($user->hasRole('client')) ? true : false;
    }

    /**
     * Does a given user have a client_id? IOW, does a given personbydomain_id have a corresponding client_id in
     * the personbydomain_client db table?
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @return boolean
     */
    public function doesUserHaveClientId($user)
    {
        return ($user->getClientId($user->id) == 0) ? false : true;
    }
}