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
use Illuminate\Support\Facades\DB;


/**
 * Class BasePolicy
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
}
