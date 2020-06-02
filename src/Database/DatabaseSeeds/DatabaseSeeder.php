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

// use a parameter per https://laravel.com/docs/5.7/seeding#running-seeders:
// php artisan db:seed --class=Lasallesoftware\\Library\\DatabaseSeeds\\DatabaseSeeder


class DatabaseSeeder extends BaseSeeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            CompanyTableSeeder::class,
            PersonTableSeeder::class,
            LaSalleSoftwareEventTableSeeder::class,
            UuidTableSeeder::class,
            ProfilesLookupTablesSeeder::class,
            ProfilesTablesSeeder::class,
            DomainsLookupTableSeeder::class,
            DomainTypesLookupTableSeeder::class,
            DomainbydomaintypeTableSeeder::class,
            RolesLookupTableSeeder::class,
            PersonByDomainsTableSeeder::class,
        ]);
    }
}
