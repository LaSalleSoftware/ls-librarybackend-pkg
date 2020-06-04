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

// Third party class
use Illuminate\Support\Carbon;;

class ProfilesLookupTablesSeeder extends BaseSeeder
{
    /**
     * Run the Profiles tables seeds.
     *
     * @return void
     */
    public function run()
    {
        // lookup_address_types table

        DB::table('lookup_address_types')->insert([
            'title'       => 'Billing',
            'description' => 'Billing',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);


        DB::table('lookup_address_types')->insert([
            'title'       => 'Home',
            'description' => 'Home',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_address_types')->insert([
            'title'       => 'Other',
            'description' => 'Other',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_address_types')->insert([
            'title'       => 'Shipping',
            'description' => 'Shipping',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_address_types')->insert([
            'title'       => 'Work',
            'description' => 'Work',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        if (app('config')->get('app.env') == "testing") {
            DB::table('lookup_address_types')->insert([
                'title'       => 'Blues',
                'description' => 'Blues',
                'enabled'     => 1,
                'created_at'  => Carbon::now(),
                'created_by'  => 1,
                'updated_at'  => null,
                'updated_by'  => null,
                'locked_at'   => null,
                'locked_by'   => null,
            ]);
        }


        // lookup_email_types table

        DB::table('lookup_email_types')->insert([
            'title'       => 'Main',
            'description' => 'Main email address',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_email_types')->insert([
            'title'       => 'Work',
            'description' => 'Work',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_email_types')->insert([
            'title'       => 'Other',
            'description' => 'Other',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        // Lasallesoftware\Librarybackend\Authentication\Models\PersonbydomainNovaFormProcessing::populateEmailsTable(Personbydomain $personbydomain)
        DB::table('lookup_email_types')->insert([
            'title'       => 'Created By Nova Personbydomain Form',
            'description' => 'Created By Nova Personbydomain Form',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);


        // lookup_social_types table
        // http://en.wikipedia.org/wiki/List_of_social_networking_websites

        DB::table('lookup_social_types')->insert([
            'title'       => 'Twitter',
            'description' => 'Twitter @name',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'Facebook',
            'description' => 'Facebook',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'LinkedIn',
            'description' => 'LinkedIn',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'Classmates.com',
            'description' => 'School, college, work and the military',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'Flickr',
            'description' => 'Flickr.com',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'Google+',
            'description' => 'plus.google.com (closing down in 2019)',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'Instagram',
            'description' => 'instagram.com',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'Meetup',
            'description' => 'Meetup.com',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'Pinterest',
            'description' => 'Pinterest.com',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'SoundCloud',
            'description' => 'SoundCloud.com',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'Yelp',
            'description' => 'Yelp is the best way to find great local businesses',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_social_types')->insert([
            'title'       => 'Other',
            'description' => '',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);


        // lookup_telephone_types table

        DB::table('lookup_telephone_types')->insert([
            'title'       => 'Cell',
            'description' => 'Cell',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_telephone_types')->insert([
            'title'       => 'Home',
            'description' => 'Home',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_telephone_types')->insert([
            'title'       => 'Other',
            'description' => 'Other',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_telephone_types')->insert([
            'title'       => 'Work',
            'description' => 'Work',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);


        // lookup_website_types table

        DB::table('lookup_website_types')->insert([
            'title'       => 'Blog',
            'description' => 'Blog',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_website_types')->insert([
            'title'       => 'Podcast',
            'description' => 'Podcast',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_website_types')->insert([
            'title'       => 'Ecommerce',
            'description' => 'Ecommerce',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_website_types')->insert([
            'title'       => 'Business',
            'description' => 'The main site for the business.',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_website_types')->insert([
            'title'       => 'Client',
            'description' => 'This site is a client of someone.',
            'enabled'     => 1,
            'created_at'  => Carbon::now(),
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        DB::table('lookup_website_types')->insert([
            'title'       => 'Other',
            'description' => 'No website type seems to apply.',
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
