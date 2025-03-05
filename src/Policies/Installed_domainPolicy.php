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
 * @see       https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see       https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 */

namespace Lasallesoftware\Librarybackend\Policies;

// LaSalle Software class
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain as User;
use Lasallesoftware\Librarybackend\Common\Policies\CommonPolicy;
use Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain as Model;

//                       SPECIAL NOTE!
//
// Installed_domains is populated during seeding. If it is absolutely necessary to create/update/delete records,
// then -- gasp! -- modify the table directly. Viewing the table in the admin is just a convenience!
//

/**
 * Class Installed_domainPolicy.
 */
class Installed_domainPolicy extends CommonPolicy
{
    /**
     * Records that are not deletable.
     *
     * @var array
     */
    protected $recordsDoNotDelete = [];

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * @return bool
     */
    public function view(User $user, Model $model)
    {
        return $user->hasRole('owner') ? true : false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * @return bool
     */
    public function create(User $user)
    {
        return $user->hasRole('owner') ? true : false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * @return bool
     */
    public function update(User $user, Model $model)
    {
        return $user->hasRole('owner') ? true : false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * @return bool
     */
    public function delete(User $user, Model $model)
    {
        return $user->hasRole('owner') ? true : false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     *  This feature is not used!
     *
     * @return bool
     */
    public function restore(User $user, Model $model)
    {
        return false;
    }

    /**
     * Only owners can see installed_domains! No C-U-D operations are allowed.
     *
     * This feature is not used!
     *
     * @return bool
     */
    public function forceDelete(User $user, Model $model)
    {
        return false;
    }
}
