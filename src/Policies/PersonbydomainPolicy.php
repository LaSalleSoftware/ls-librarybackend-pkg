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

namespace Lasallesoftware\Librarybackend\Policies;

// LaSalle Software class
// Yes, the User and Model are derived from the same model class.. but! The user is still the user!
// And, the model is still the model.
use Lasallesoftware\Librarybackend\Common\Policies\CommonPolicy;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain as User;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain as Model;

// Laravel facade
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


/**
 * Class PersonbydomainPolicy
 *
 * @package Lasallesoftware\Librarybackend\Policies
 */
class PersonbydomainPolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [1];


    /**
     * Determine whether the user can view a person's details.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $model
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return $user->hasRole('owner')              || 
               $user->hasRole('superadministrator') || 
               $user->hasRole('administrator')
        ;
    }

    /**
     * Determine whether the user can create a person.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole('owner') || $user->hasRole('superadministrator');
    }

    /**
     * Determine whether the user can update a person.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $model
     * @return bool
     */
    public function update(User $user, Model $model)
    {
        // if the user role is neither "owner" nor "superadministrator", then not update-able
        //if  ((!$user->hasRole('owner')) && (!$user->hasRole('superadministrator'))) {
        //    return false;
        //}

        if (($user->hasRole('administrator')) && ($model->id == Auth::id())) {
            return true;
        }



        // a superadmin cannot update an owner
        if (($user->hasRole('superadministrator')) && ($this->getRoleIdOfTheModelPersonbydomain($model) == 1)) {
                return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete a person.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $model
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        // if person is on the "do not delete" list, then not delete-able
        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        // if the user role is neither "owner" nor "superadministrator", then not delete-able
        if  ((!$user->hasRole('owner')) && (!$user->hasRole('superadministrator'))) {
            return false;
        }

        // a superadmin cannot update an owner
        if (($user->hasRole('superadministrator')) && ($this->getRoleIdOfTheModelPersonbydomain($model) == 1)) {
            return false;
        }

        // if still here, then this person is deletable
        return true;
    }

    /**
     * Determine whether the user can restore a person.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $model
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return $user->hasRole('owner') && $user->hasRole('superadministrator');
    }

    /**
     * Determine whether the user can permanently delete a person.
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $model
     * @return bool
     */
    public function forceDelete(User $user, Model $model)
    {
        if  ((!$user->hasRole('owner')) && (!$user->hasRole('superadministrator'))) {
            return false;
        }

        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can attach any lookup user roles to the personbydomain.
     *
     * Note: one role per user!
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $model
     * @return mixed
     */
    public function attachAnyLookup_role(User $user, Model $model)
    {
        // if a role is already associated (attached) to the user, then do not add any more roles!
        return $this->getRoleIdOfTheModelPersonbydomain($model) ? false : true;
    }

    /**
     * Determine whether the user can attach any lookup user roles to the personbydomain.
     *
     * Note: one role per user!
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $model
     * @return mixed
     */
    public function detachLookup_role(User $user, Model $model)
    {
        // do not delete the first pivot table record!
        if ($model->id == 1) {
            return false;
        }

        // super admins cannot delete an owner role
        if (($user->hasRole('superadministrator')) && ($this->getRoleIdOfTheModelPersonbydomain($model) == 1)) {
            return false;
        }

        return true;
    }

    /**
     * To suppress the edit-attached button!
     *
     *
     * See this fabulous post: https://github.com/laravel/nova-issues/issues/1003#issuecomment-497008278
     *
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Library\Authentication\Models\Personbydomain  $model
     * @return bool
     */
    public function attachLookup_role(User $user, Model $model)
    {
        // if a role is already associated (attached) to the user, then no edit-attaching
        return $this->getRoleIdOfTheModelPersonbydomain($model) ? false : true;
    }
}
