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

namespace Lasallesoftware\Librarybackend\Policies;

// LaSalle Software class
use Lasallesoftware\Librarybackend\Common\Policies\CommonPolicy;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain as User;
use Lasallesoftware\Librarybackend\Profiles\Models\Person as Model;

// Laravel facade
use Illuminate\Support\Facades\DB;


/**
 * Class emailPolicy
 *
 * @package Lasallesoftware\Librarybackend\Policies
 */
class PersonPolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [1,2];


    /**
     * Determine whether the user can view a person's details.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Person                $model
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return $user->hasRole('owner') || $user->hasRole('superadministrator');
    }

    /**
     * Determine whether the user can create a person.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole('owner') || $user->hasRole('superadministrator');
    }

    /**
     * Determine whether the user can update a person.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Person                $model
     * @return bool
     */
    public function update(User $user, Model $model)
    {
        if  ((!$user->hasRole('owner')) && (!$user->hasRole('superadministrator'))) {
            return false;
        }

        if ($model->id == 1) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete a person.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Person                $model
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        // if the user role is either "owner" or "superadministrator", then email address is deletable
        if  ((!$user->hasRole('owner')) && (!$user->hasRole('superadministrator'))) {
            return false;
        }

        // if person is on the "do not delete" list, then not deletable
        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        // if this person is in the person_address pivot table, then this person is not deletable
        if ( DB::table('person_address')->where('person_id', $model->id)->first() ) {
            return false;
        }

        // if this person is in the person_email pivot table, then this person is not deletable
        if ( DB::table('person_email')->where('person_id', $model->id)->first() ) {
            return false;
        }

        // if this person is in the person_social pivot table, then this person is not deletable
        if ( DB::table('person_social')->where('person_id', $model->id)->first() ) {
            return false;
        }

        // if this person is in the person_telephone pivot table, then this person is not deletable
        if ( DB::table('person_telephone')->where('person_id', $model->id)->first() ) {
            return false;
        }

        // if this person is in the person_website pivot table, then this person is not deletable
        if ( DB::table('person_website')->where('person_id', $model->id)->first() ) {
            return false;
        }

        // if still here, then this person is deletable
        return true;
    }

    /**
     * Determine whether the user can restore a person.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Person                $model
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return $user->hasRole('owner') && $user->hasRole('superadministrator');
    }

    /**
     * Determine whether the user can permanently delete a person.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Person                $model
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
     * Determine whether the user can attach any personbydomains to persons.
     *
     * Basically, no, cannot attach here. Go to the Personbydomains menu item!
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Person                $model
     * @return bool
     */
    public function attachAnyPersonbydomain(User $user, Model $model)
    {
        return false;
    }

    /**
     * Determine whether the user can detach any personbydomains to persons.
     *
     * Basically, no, cannot detach here. Go to the Personbydomains menu item!
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Person                $model
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
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Person                $model
     * @return bool
     */
    public function attachPersonbydomain(User $user, Model $model)
    {
        return false;
    }
}
