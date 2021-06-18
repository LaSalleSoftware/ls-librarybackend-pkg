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
use Lasallesoftware\Librarybackend\Profiles\Models\Company as Model;

// Laravel facade
use Illuminate\Support\Facades\DB;


/**
 * Class emailPolicy
 *
 * @package Lasallesoftware\Librarybackend\Policies
 */
class CompanyPolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [];


    /**
     * Determine whether the user can view a company's details.
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
     * Determine whether the user can create companies.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole('owner') || $user->hasRole('superadministrator');
    }

    /**
     * Determine whether the user can update a company.
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

        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can delete a company.
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
     * Determine whether the user can restore a company.
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
     * Determine whether the user can permanently delete a company.
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
}
