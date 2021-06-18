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

namespace Lasallesoftware\Librarybackend\Database\DatabaseSeeds;

// Laravel Framework
use Illuminate\Support\Facades\DB;

// Third party class
use Illuminate\Support\Carbon;;

class DomainTypesLookupTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lookup_domain_types')->insert([
            'title'       => 'adminbackend',
            'description' => 'Administrative back-end',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_domain_types')->insert([
            'title'       => 'basicfrontendapp',
            'description' => 'Basic Front-end Application',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_domain_types')->insert([
            'title'       => 'blog',
            'description' => 'Blog',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_domain_types')->insert([
            'title'       => 'podcast',
            'description' => 'Podcast',
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
