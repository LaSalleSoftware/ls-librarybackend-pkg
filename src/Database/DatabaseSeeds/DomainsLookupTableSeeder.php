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

class DomainsLookupTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('installed_domains')->insert([
            'title'       => app('config')->get('lasallesoftware-librarybackend.lasalle_app_domain_name'),
            'description' => app('config')->get('lasallesoftware-librarybackend.lasalle_app_domain_name'),
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        if (app('config')->get('app.env') == "testing") {
            DB::table('installed_domains')->insert([
                'title'       => 'lasallesoftware.ca',
                'description' => 'LaSalleSoftware.ca',
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
}
