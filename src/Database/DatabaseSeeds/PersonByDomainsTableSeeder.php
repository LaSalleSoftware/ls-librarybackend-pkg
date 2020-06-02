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
use Illuminate\Support\Carbon;;

class PersonByDomainsTableSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ($this->doPopulateWithTestData()) {

            $installed_domain_id = 1;  // Admin back-end domain

            // for the owner role
            $person = $this->getPerson(2);
            DB::table('personbydomains')->insert([
                'person_id'             => $person->id,
                'person_first_name'     => $person->first_name,
                'person_surname'        => $person->surname,
                'name_calculated'       => $person->first_name . ' ' . $person->surname,
                'email'                 => $person->email[0]->email_address,
                'email_verified_at'     => Carbon::now(),
                'password'              => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'installed_domain_id'   => 1,
                'installed_domain_title' => $this->getDomainTitle($installed_domain_id),
                'banned_enabled'        => 0,
                'banned_at'             => null,
                'banned_comments'       => null,
                'uuid'                  => 'created_during_initial_seeding',
                'created_at'            => Carbon::now(),
                'created_by'            => 1,
                'updated_at'            => null,
                'updated_by'            => null,
                'locked_at'             => null,
                'locked_by'             => null,
            ]);

            DB::table('personbydomain_lookup_roles')->insert([
                'id'                => 1,
                'personbydomain_id' => 1,
                'lookup_role_id'    => 1,
            ]);

            // for the super administrator role
            $person = $this->getPerson(3);
            DB::table('personbydomains')->insert([
                'person_id'             => $person->id,
                'person_first_name'     => $person->first_name,
                'person_surname'        => $person->surname,
                'name_calculated'       => $person->first_name . ' ' . $person->surname,
                'email'                 => $person->email[0]->email_address,
                'email_verified_at'     => Carbon::now(),
                'password'              => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'installed_domain_id'   => 1,
                'installed_domain_title' => $this->getDomainTitle($installed_domain_id),
                'banned_enabled'        => 0,
                'banned_at'             => null,
                'banned_comments'       => null,
                'uuid'                  => 'created_during_initial_seeding',
                'created_at'            => Carbon::now(),
                'created_by'            => 1,
                'updated_at'            => null,
                'updated_by'            => null,
                'locked_at'             => null,
                'locked_by'             => null,
            ]);

            DB::table('personbydomain_lookup_roles')->insert([
                'id'                => 2,
                'personbydomain_id' => 2,
                'lookup_role_id'    => 2,
            ]);


            // for the administrator role
            $person = $this->getPerson(4);
            DB::table('personbydomains')->insert([
                'person_id'             => $person->id,
                'person_first_name'     => $person->first_name,
                'person_surname'        => $person->surname,
                'name_calculated'       => $person->first_name . ' ' . $person->surname,
                'email'                 => $person->email[0]->email_address,
                'email_verified_at'     => Carbon::now(),
                'password'              => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
                'installed_domain_id'   => 1,
                'installed_domain_title' => $this->getDomainTitle($installed_domain_id),
                'banned_enabled'        => 0,
                'banned_at'             => null,
                'banned_comments'       => null,
                'uuid'                  => 'created_during_initial_seeding',
                'created_at'            => Carbon::now(),
                'created_by'            => 1,
                'updated_at'            => null,
                'updated_by'            => null,
                'locked_at'             => null,
                'locked_by'             => null,
            ]);

            DB::table('personbydomain_lookup_roles')->insert([
                'id'                => 3,
                'personbydomain_id' => 3,
                'lookup_role_id'    => 3,
            ]);
        }
    }
}
