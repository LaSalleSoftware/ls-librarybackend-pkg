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

namespace Lasallesoftware\Librarybackend\Nova;

// Laravel Framework
use Illuminate\Support\Carbon;

// Laravel facade
use Illuminate\Support\Facades\DB;

class DeleteActioneventsRecords
{
    /**
     * Delete "action_events" database recrods
     *
     * @return void
     */
    public function deleteRecords()
    {
        $expiredAt = Carbon::now()->subDays($this->daysToExpiration());

        DB::table('action_events')->where('updated_at', '<', $expiredAt)->delete();
    }

    /**
     * How many days to wait for deletion?
     *
     * @return int
     */
    protected function daysToExpiration()
    {
        return config('lasallesoftware-librarybackend.actionevents_number_of_days_until_deletion');
    }
}