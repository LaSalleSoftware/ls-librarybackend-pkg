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
 * @copyright  (c) 2019-2025 The South LaSalle Trading Corporation
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
use Lasallesoftware\Librarybackend\Profiles\Models\Lookup_address_type as Model;

// Laravel facade
use Illuminate\Support\Facades\DB;


/**
 * Class Lookup_address_typePolicy
 *
 * @package Lasallesoftware\Librarybackend\Policies
 */
class Lookup_address_typePolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [1,2,3,4,5];


    /**
     * Determine whether the user can view a lookup_address_type's details.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Lookup_address_type   $model
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can create a lookup_address_type.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can update a lookup_address_type.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Lookup_address_type   $model
     * @return bool
     */
    public function update(User $user, Model $model)
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
     * Determine whether the user can delete a lookup_address_type.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Lookup_address_type   $model
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        if (!$user->hasRole('owner')) {
            return false;
        }

        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        if (DB::table('addresses')->where('lookup_address_type_id', $model->id)->first()) {
            return false;
        }

        return true;
    }

    /**
     * Determine whether the user can restore a lookup_address_type.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Lookup_address_type   $model
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return $user->hasRole('owner');
    }

    /**
     * Determine whether the user can permanently delete a lookup_address_type.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @param  \Lasallesoftware\Librarybackend\Profiles\Models\Lookup_address_type   $model
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
}
