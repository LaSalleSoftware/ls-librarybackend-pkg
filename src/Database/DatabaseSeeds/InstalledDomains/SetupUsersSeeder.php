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
 * @copyright  (c) 2019-2024 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Database\DatabaseSeeds\InstalledDomains;

// LaSalle Software
use Lasallesoftware\Librarybackend\Database\DatabaseSeeds\BaseSeeder;

// Laravel Framework
use Illuminate\Support\Facades\DB;

// Third party classes
use Illuminate\Support\Carbon;

class SetupUsersSeeder extends BaseSeeder
{
    protected $uuid;
    protected $now;


    public function __construct()
    {
        $this->uuid = "created_during_initial_seeding";
        $this->now  = Carbon::now();
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ($this->doPopulateWithTestData()) {
            $this->setUpSuperadminForAdmindomain();
            $this->setUpAdminForAdmindomain();
        }
    }

    /**
     * Create a super administrator user belonging to the admin domain for blog package tests
     *
     * @return void
     */
    private function setUpSuperadminForAdmindomain()
    {
        $lookup_role_id      = 2;  // Super Administrator
        $installed_domain_id = 1;  // Admin back-end domain

        // persons table
        DB::table('persons')->insert([
            'name_calculated'        => 'Sidney Bechet',
            'salutation'             => null,
            'first_name'             => 'Sidney',
            'middle_name'            => '',
            'surname'                => 'Bechet',
            'position'               => 'Super administrator for blogging tests only',
            'description'            => 'Super administrator for blogging tests only',
            'comments'               => 'Super administrator for blogging tests only',
            'profile'                => null,
            'featured_image'         => null,
            'birthday'               => null,
            'anniversary'            => null,
            'deceased'               => null,
            'comments_date'          => null,
            'uuid'                   => $this->uuid,
            'created_at'             => $this->now,
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);

        // emails and people_email tables
        DB::table('emails')->insert([
            'lookup_email_type_id'   => 1,
            'email_address'          => 'sidney.bechet@blogtest.ca',
            'description'            => null,
            'comments'               => null,
            'uuid'                   => $this->uuid,
            'created_at'             => $this->now,
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);

        $person = $this->getLatestPerson();
        $email  = $this->getLatestEmail();

        DB::table('person_email')->insert([
            'person_id'              => $person->id,
            'email_id'               => $email->id,
        ]);

        // personbydomains
        DB::table('personbydomains')->insert([
            'person_id'              => $person->id,
            'person_first_name'      => $person->first_name,
            'person_surname'         => $person->surname,
            'email'                  => $email->email_address,
            'email_verified_at'      => $this->now,
            'password'               => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'installed_domain_id'    => $installed_domain_id,
            'installed_domain_title' => $this->getDomainTitle($installed_domain_id),
            'uuid'                   => $this->uuid,
            'created_at'             => $this->now,
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);

        $personbydomain = $this->getLatestPersonbydomain();

        DB::table('personbydomain_lookup_roles')->insert([
            'personbydomain_id'      => $personbydomain->id,
            'lookup_role_id'         => $lookup_role_id,
        ]);
    }

    /**
     * Create a administrator user belonging to the admin domain for blog package tests
     *
     * @return void
     */
    private function setUpAdminForAdmindomain()
    {
        $lookup_role_id      = 3;  // Super Administrator
        $installed_domain_id = 1;  // Admin back-end domain

        // persons table
        DB::table('persons')->insert([
            'name_calculated'        => 'Robert Johonson',
            'salutation'             => null,
            'first_name'             => 'Robert',
            'middle_name'            => '',
            'surname'                => 'Johnson',
            'position'               => 'Administrator for blogging tests only',
            'description'            => 'Administrator for blogging tests only',
            'comments'               => 'Administrator for blogging tests only',
            'profile'                => null,
            'featured_image'         => null,
            'birthday'               => null,
            'anniversary'            => null,
            'deceased'               => null,
            'comments_date'          => null,
            'uuid'                   => $this->uuid,
            'created_at'             => $this->now,
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);

        // emails and people_email tables
        DB::table('emails')->insert([
            'lookup_email_type_id'   => 1,
            'email_address'          => 'robert.johnson@blogtest.ca',
            'description'            => null,
            'comments'               => null,
            'uuid'                   => $this->uuid,
            'created_at'             => $this->now,
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);

        $person = $this->getLatestPerson();
        $email  = $this->getLatestEmail();

        DB::table('person_email')->insert([
            'person_id'              => $person->id,
            'email_id'               => $email->id,
        ]);

        // personbydomains
        DB::table('personbydomains')->insert([
            'person_id'              => $person->id,
            'person_first_name'      => $person->first_name,
            'person_surname'         => $person->surname,
            'email'                  => $email->email_address,
            'email_verified_at'      => $this->now,
            'password'               => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'installed_domain_id'    => $installed_domain_id,
            'installed_domain_title' => $this->getDomainTitle($installed_domain_id),
            'uuid'                   => $this->uuid,
            'created_at'             => $this->now,
            'created_by'             => 1,
            'updated_at'             => null,
            'updated_by'             => null,
            'locked_at'              => null,
            'locked_by'              => null,
        ]);

        $personbydomain = $this->getLatestPersonbydomain();

        DB::table('personbydomain_lookup_roles')->insert([
            'personbydomain_id'      => $personbydomain->id,
            'lookup_role_id'         => $lookup_role_id,
        ]);
    }
}
