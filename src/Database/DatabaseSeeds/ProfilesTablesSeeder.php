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
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

// Third party class
use Illuminate\Support\Carbon;
use Faker\Generator as Faker;

class ProfilesTablesSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $uuid = "created_during_initial_seeding";


        ///////////////////////////////////////////////////////////////////
        //////////////           ADDRESSES              ///////////////////
        ///////////////////////////////////////////////////////////////////

        if ($this->doPopulateWithTestData()) {

            DB::table('addresses')->insert([
                'id'                     => 1,
                'lookup_address_type_id' => 5,

                // NOTE THAT THE MODEL EVENT IS **NOT** DISPATCHED DURING SEEDING!!
                'address_calculated'     => '328 North Dearborn Street, Chicago, IL, US  60654',
                'address_line_1'         => '328 North Dearborn Street',
                'address_line_2'         => '',
                'address_line_3'         => '',
                'address_line_4'         => '',
                'city'                   => 'Chicago',
                'province'               => 'IL',
                'country'                => 'US',
                'postal_code'            => '60654',
                'latitude'               => 41.89290000,
                'longitude'              => -87.62970000,
                'description'            => null,
                'comments'               => null,
                'profile'                => null,
                'featured_image'         => null,
                'map_link'               => 'https://maps.google.com?daddr=329 N. Dearborn+Chicago IL 60654',
                'uuid'                   => $uuid,
                'created_at'             => Carbon::now(),
                'created_by'             => 1,
                'updated_at'             => null,
                'updated_by'             => null,
                'locked_at'              => null,
                'locked_by'              => null,
            ]);

            // populate the company_address pivot table with the above email address
            DB::table('company_address')->insert([
                'id'          => 1,
                'company_id'  => 1,
                'address_id'  => 1,
            ]);
        }


        ///////////////////////////////////////////////////////////////////
        //////////////              EMAILS              ///////////////////
        ///////////////////////////////////////////////////////////////////


        if ($this->doPopulateWithTestData()) {

            // email address for the owner role
            DB::table('emails')->insert([
                'id'                    => 1,
                'lookup_email_type_id'  => 1,
                'email_address'         => 'bob.bloom@lasallesoftware.ca',
                'description'           => null,
                'comments'              => null,
                'uuid'                  => $uuid,
                'created_at'            => Carbon::now(),
                'created_by'            => 1,
                'updated_at'            => null,
                'updated_by'            => null,
                'locked_at'             => null,
                'locked_by'             => null,
            ]);

            // populate the person_email pivot table with the above email address
            DB::table('person_email')->insert([
                'id'          => 1,
                'person_id'   => 2,
                'email_id'    => 1,
            ]);

            // email address for the super administrator role
            DB::table('emails')->insert([
                'id'                   => 2,
                'lookup_email_type_id' => 1,
                'email_address'        => 'bbking@kingofblues.com',
                'description'          => null,
                'comments'             => null,
                'uuid'                 => $uuid,
                'created_at'           => Carbon::now(),
                'created_by'           => 1,
                'updated_at'           => null,
                'updated_by'           => null,
                'locked_at'            => null,
                'locked_by'            => null,
            ]);

            // populate the person_email pivot table with the above email address
            DB::table('person_email')->insert([
                'id'        => 2,
                'person_id' => 3,
                'email_id'  => 2,
            ]);

            // email address for the administrator role
            DB::table('emails')->insert([
                'id'                   => 3,
                'lookup_email_type_id' => 1,
                'email_address'        => 'srv@doubletrouble.com',
                'description'          => null,
                'comments'             => null,
                'uuid'                 => $uuid,
                'created_at'           => Carbon::now(),
                'created_by'           => 1,
                'updated_at'           => null,
                'updated_by'           => null,
                'locked_at'            => null,
                'locked_by'            => null,
            ]);

            // populate the person_email pivot table with the above email address
            DB::table('person_email')->insert([
                'id'        => 3,
                'person_id' => 4,
                'email_id'  => 3,
            ]);

            // email address to test "Email Address" CRUD forms
            DB::table('emails')->insert([
                'id'                   => 4,
                'lookup_email_type_id' => 1,
                'email_address'        => 'muddy.waters@blues.com',
                'description'          => null,
                'comments'             => null,
                'uuid'                 => $uuid,
                'created_at'           => Carbon::now(),
                'created_by'           => 1,
                'updated_at'           => null,
                'updated_by'           => null,
                'locked_at'            => null,
                'locked_by'            => null,
            ]);
        }


       ///////////////////////////////////////////////////////////////////
       //////////////           SOCIALS                ///////////////////
       ///////////////////////////////////////////////////////////////////

        if ($this->doPopulateWithTestData()) {

            DB::table('socials')->insert([
                'id'                    => 1,
                'lookup_social_type_id' => 1,
                'url'                   => 'https://www.allaboutbluesmusic.com/delta-blues/',
                'description'           => $faker->sentence($nbWords = 6, $variableNbWords = false),
                'comments'              => $faker->paragraph($nbSentences = 3, $variableNbSentences = false),
                'uuid'                  => $uuid,
                'created_at'            => Carbon::now(),
                'created_by'            => 1,
                'updated_at'            => null,
                'updated_by'            => null,
                'locked_at'             => null,
                'locked_by'             => null,
            ]);

            // populate the person_social pivot table with the above social site
            DB::table('person_social')->insert([
                'id'        => 1,
                'person_id' => 3,
                'social_id' => 1,
            ]);

            DB::table('socials')->insert([
                'id'                    => 2,
                'lookup_social_type_id' => 1,
                'url'                   => 'https://www.allaboutbluesmusic.com',
                'description'           => $faker->sentence($nbWords = 6, $variableNbWords = false),
                'comments'              => $faker->paragraph($nbSentences = 3, $variableNbSentences = false),
                'uuid'                  => $uuid,
                'created_at'            => Carbon::now(),
                'created_by'            => 1,
                'updated_at'            => null,
                'updated_by'            => null,
                'locked_at'             => null,
                'locked_by'             => null,
            ]);
        }


        ///////////////////////////////////////////////////////////////////
        //////////////         TELEPHONES               ///////////////////
        ///////////////////////////////////////////////////////////////////

        if ($this->doPopulateWithTestData()) {

            DB::table('telephones')->insert([
                'id'                       => 1,
                'lookup_telephone_type_id' => 1,
                'telephone_calculated'     => '1 (555) 123-4567',
                'country_code'             => 1,
                'area_code'                => 555,
                'telephone_number'         => 1234567,
                'extension'                => null,
                'description'              => $faker->sentence($nbWords = 6, $variableNbWords = false),
                'comments'                 => $faker->paragraph($nbSentences = 3, $variableNbSentences = false),
                'uuid'                     => $uuid,
                'created_at'               => Carbon::now(),
                'created_by'               => 1,
                'updated_at'               => null,
                'updated_by'               => null,
                'locked_at'                => null,
                'locked_by'                => null,
            ]);

            // populate the person_telephone pivot table with the above telephone number
            DB::table('person_telephone')->insert([
                'id'           => 1,
                'person_id'    => 3,
                'telephone_id' => 1,
            ]);

            $area_code   = $faker->randomNumber($nbDigits = 3, $strict = true);
            $randomThree = $faker->randomNumber($nbDigits = 3, $strict = true);
            $randomFour  = $faker->randomNumber($nbDigits = 4, $strict = true);

            DB::table('telephones')->insert([
                'id'                       => 2,
                'lookup_telephone_type_id' => 1,
                'telephone_calculated'     => '1' . ' (' . $area_code . ') ' . $randomThree . '-' . $randomFour,
                'country_code'             => '1',
                'area_code'                => $area_code,
                'telephone_number'         => $randomThree . $randomFour,
                'extension'                => null,
                'description'              => $faker->sentence($nbWords = 6, $variableNbWords = false),
                'comments'                 => $faker->paragraph($nbSentences = 3, $variableNbSentences = false),
                'uuid'                     => $uuid,
                'created_at'               => Carbon::now(),
                'created_by'               => 1,
                'updated_at'               => null,
                'updated_by'               => null,
                'locked_at'                => null,
                'locked_by'                => null,
            ]);
        }


        ///////////////////////////////////////////////////////////////////
        //////////////           WEBSITES               ///////////////////
        ///////////////////////////////////////////////////////////////////

        if ($this->doPopulateWithTestData()) {

            DB::table('websites')->insert([
                'id'                     => 1,
                'lookup_website_type_id' => 1,
                'url'                    => 'https://www.mlb.com/bluejays',
                'description'            => $faker->sentence($nbWords = 6, $variableNbWords = false),
                'comments'               => Crypt::encrypt($faker->paragraph($nbSentences = 3, $variableNbSentences = false)),
                'uuid'                   => $uuid,
                'created_at'             => Carbon::now(),
                'created_by'             => 1,
                'updated_at'             => null,
                'updated_by'             => null,
                'locked_at'              => null,
                'locked_by'              => null,
            ]);

            // populate the person_website pivot table with the above website
            DB::table('person_website')->insert([
                'id'         => 1,
                'person_id'  => 3,
                'website_id' => 1,
            ]);

            DB::table('websites')->insert([
                'id'                     => 2,
                'lookup_website_type_id' => 1,
                'url'                    => 'https://buffalobills.com',
                'description'            => $faker->sentence($nbWords = 6, $variableNbWords = false),
                'comments'               => Crypt::encrypt($faker->paragraph($nbSentences = 3, $variableNbSentences = false)),
                'uuid'                   => $uuid,
                'created_at'             => Carbon::now(),
                'created_by'             => 1,
                'updated_at'             => null,
                'updated_by'             => null,
                'locked_at'              => null,
                'locked_by'              => null,
            ]);
        }


        // Running these factories will break my tests. However, there are times when I want a more fully populated
        // database. Hence, this funny little snippet...
        if ($this->doPopulateWithTestData()) {

            $do_it      = false;
            $iterations = 300;

            $do_it ? factory(\Lasallesoftware\Librarybackend\Profiles\Models\Address::class,   $iterations)->create() : false ;

            $do_it ? factory(\Lasallesoftware\Librarybackend\Profiles\Models\Email::class,     $iterations)->create() : false ;

            $do_it ? factory(\Lasallesoftware\Librarybackend\Profiles\Models\Social::class,    $iterations)->create() : false ;

            $do_it ? factory(\Lasallesoftware\Librarybackend\Profiles\Models\Telephone::class, $iterations)->create() : false ;

            $do_it ? factory(\Lasallesoftware\Librarybackend\Profiles\Models\Website::class,   $iterations)->create() : false ;
        }
    }
}
