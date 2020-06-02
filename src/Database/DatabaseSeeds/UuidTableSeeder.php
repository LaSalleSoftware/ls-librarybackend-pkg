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

namespace Lasallesoftware\Librarybackend\Database\DatabaseSeeds;

// Laravel Framework
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Third party classes
use Illuminate\Support\Carbon;

class UuidTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('uuids')->insert([
            'lasallesoftware_event_id' => 1,
            'uuid'                     => 'created_during_initial_seeding',
            'created_at'               => Carbon::now(),
            'created_by'               => 1,
        ]);
    }
}
