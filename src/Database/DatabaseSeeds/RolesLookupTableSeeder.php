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
 * @copyright  (c) 2019-2022 The South LaSalle Trading Corporation
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

// Third party class
use Illuminate\Support\Carbon;;

class RolesLookupTableSeeder extends BaseSeeder
{
    /**
     * Run the Profiles tables seeds.
     *
     * @return void
     */
    public function run()
    {
        // lookup_address_types table

        DB::table('lookup_roles')->insert([
            'title'       => 'Owner',
            'description' => 'Owner is the highest permissions available in LaSalle Software. Few should be assigned this one.',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);


        DB::table('lookup_roles')->insert([
            'title'       => 'Super Administrator',
            'description' => 'Super admin is the senior administrative role.',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_roles')->insert([
            'title'       => 'Administrator',
            'description' => 'Admin is the junior administrative role.',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_roles')->insert([
            'title'       => 'Client',
            'description' => 'Client.',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);        
    }
}