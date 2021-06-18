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

namespace Lasallesoftware\Librarybackend\Policies;

// LaSalle Software class
use Lasallesoftware\Librarybackend\Common\Policies\CommonPolicy;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain as User;
use Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role as Model;

// Laravel facades
use Illuminate\Support\Facades\DB;


/**
 * Class Lookup_rolePolicy
 *
 * @package Lasallesoftware\Librarybackend\Policies
 */
class Lookup_rolePolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [1,2,3,4];


    /**
     * Owners & superadministrators can view lookup_roles.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role     $model
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return $user->hasRole('owner') || $user->hasRole('superadministrator');
    }

    /**
     * Only owners can view lookup_roles.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole('owner');
    }

    /**
     * Only owners can update lookup_roles.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role     $model
     * @return bool
     */
    public function update(User $user, Model $model)
    {
        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        if (!$user->hasRole('owner')) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete a lookup_roles.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role     $model
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        // if the record is in the do-not-delete array, then not delete-able
        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        // if the lookup_roles record exists in the personbydomain_lookup_roles pivot table, then not delete-able
        if (DB::table('personbydomain_lookup_roles')->where('lookup_role_id', $model->id)->first()) {
            return false;
        }

        // if the user is not an owner, then not delete-able
        if (!$user->hasRole('owner')) {
            return false;
        }

        // still here? then delete-able
        return true;
    }

    /**
     * Determine whether the user can restore a lookup_role.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role     $model
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can permanently delete a lookup_role.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role     $model
     * @return bool
     */
    public function forceDelete(User $user, Model $model)
    {
        if (!$user->hasRole('owner')) {
            return false;
        }

        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can attach any personbydomains to lookup_roles.
     *
     * Basically, no, cannot attach here. Go to the Personbydomains menu item!
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role     $model
     * @return bool
     */
    public function attachAnyPersonbydomain(User $user, Model $model)
    {
        return false;
    }

    /**
     * Determine whether the user can detach any personbydomains to lookup_roles.
     *
     * Basically, no, cannot detach here. Go to the Personbydomains menu item!
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role     $model
     * @return bool
     */
    public function detachPersonbydomain(User $user, Model $model)
    {
        return false;
    }

    /**
     * To suppress the edit-attached button!
     *
     *
     * See this fabulous post: https://github.com/laravel/nova-issues/issues/1003#issuecomment-497008278
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role     $model
     * @return bool
     */
    public function attachPersonbydomain(User $user, Model $model)
    {
        return false;
    }
}
