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

namespace Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Policies;

// LaSalle Software class
use Lasallesoftware\Librarybackend\Common\Policies\CommonPolicy;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain as User;
use Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Models\Phpserverlessproject_sponsors as Model;


class Phpserverlessproject_sponsorsPolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [];


    /**
     * Determine whether the user can view details.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain                                 $user
     * @param  \Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Models\Phpserverlessproject_sponsors $model
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Determine whether the user can create.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain  $user
     * @return bool
     */
    public function create(User $user)
    {
        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Determine whether the user can update.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain                                 $user
     * @param  \Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Models\Phpserverlessproject_sponsors $model
     * @return bool
     */
    public function update(User $user, Model $model)
    {
        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Determine whether the user can delete.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain                                 $user
     * @param  \Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Models\Phpserverlessproject_sponsors $model
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        if ($this->isRecordDoNotDelete($model)) {
            return false;
        }

        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Determine whether the user can restore.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain                                 $user
     * @param  \Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Models\Phpserverlessproject_sponsors $model
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return ($user->hasRole('owner')) ? true : false;
    }

    /**
     * Determine whether the user can permanently delete.
     *
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain                                 $user
     * @param  \Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Models\Phpserverlessproject_sponsors $model
     * @return bool
     */
    public function forceDelete(User $user, Model $model)
    {
        return ($user->hasRole('owner')) ? true : false;
    }
}