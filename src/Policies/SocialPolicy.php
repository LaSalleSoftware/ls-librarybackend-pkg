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
use Lasallesoftware\Librarybackend\Profiles\Models\Social as Model;

// Laravel facade
use Illuminate\Support\Facades\DB;


/**
 * Class SocialPolicy
 *
 * @package Lasallesoftware\Librarybackend\Policies
 */
class SocialPolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [];


    /**
     * Determine whether the user can view a social site's details.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Social                $model
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return $user->hasRole('owner') || $user->hasRole('superadministrator');
    }

    /**
     * Determine whether the user can create social sites.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole('owner') || $user->hasRole('superadministrator');
    }

    /**
     * Determine whether the user can update a social site.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Social                $model
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
     * Determine whether the user can delete a social site.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Social                $model
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        // if the user role is either "owner" or "superadministrator", then this social site is deletable
        if  ((!$user->hasRole('owner')) && (!$user->hasRole('superadministrator'))) {
            return false;
        }

        // if this social site is on the "do not delete" list, then not deletable
        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        // if this social site is in the person_social pivot table, then not deletable
        if ( DB::table('person_social')->where('social_id', $model->id)->first() ) {
            return false;
        }

        // if this social site is in the company_social pivot table, then not deletable
        if ( DB::table('company_social')->where('social_id', $model->id)->first() ) {
            return false;
        }

        // if still here, then this social site is deletable
        return true;
    }

    /**
     * Determine whether the user can restore a social site.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Social                $model
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return $user->hasRole('owner') && $user->hasRole('superadministrator');
    }

    /**
     * Determine whether the user can permanently delete a social site.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Social                $model
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
