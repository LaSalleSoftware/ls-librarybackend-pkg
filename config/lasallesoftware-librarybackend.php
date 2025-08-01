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
 * @copyright  (c) 2019-2025 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 *
 * @see       https://lasallesoftware.ca
 * @see       https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see       https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 */

return [


    /*
    | ========================================================================
    | START: SET-UP
    | ========================================================================
    */

    /*
    |--------------------------------------------------------------------------
    | The name of this LaSalle Software app being installed?
    |--------------------------------------------------------------------------
    |
    | There are two right now:
    | * adminbackendapp
    | * basicfrontendapp
    |
    | There can be many front ends, but only one administrative backend.
    |
    | The admin backend is the only one with a database, and with access to certain
    | features and database tables.
    |
    | Set in the .env file.
    |
    */
	'lasalle_app_name' => env('LASALLE_APP_NAME'),

    /*
    |--------------------------------------------------------------------------
    | The app's URL, without the "https://"
    |--------------------------------------------------------------------------
    |
    | Best explained by example: if the app's URL is "https://admin.DoubleTrouble.com",
    | then this is "admin.DoubleTrouble.com".
    |
    | Set in the .env file.
    |
    */
    'lasalle_app_domain_name' => env('LASALLE_APP_DOMAIN_NAME'),
    
    /*
	|--------------------------------------------------------------------------
	| Seed database with test data
	|--------------------------------------------------------------------------
	|
    | Seed database with test data? 
    |
    | Test data will seed in the "testing" environment, even when this setting is false.
    | 
    */
    'seed_database_with_test_data' => env('LASALLE_POPULATE_DATABASE_WITH_TEST_DATA', false),

    /*
	|--------------------------------------------------------------------------
	| Previous APP_KEY
	|--------------------------------------------------------------------------
	|
    | Before you enter the a new APP_KEY, copy the existing APP_KEY environment 
    | variable to the LASALLE_PREVIOUS_APP_KEY so that you can re-encrypt the existing 
    | encrypted fields with the new APP_KEY.
    | 
    */
    'lasalle_previous_app_key' => env('LASALLE_PREVIOUS_APP_KEY'),

     /*
    | ========================================================================
    | END: SET-UP
    | ========================================================================
    */



     /*
    | ========================================================================
    | START: JWT AUTHENTICATION
    | ========================================================================
    */

    /*
	|--------------------------------------------------------------------------
	| Json Web Token EXP claim duration
	|--------------------------------------------------------------------------
	|
    | How many seconds until a JWT expires?
    |
    | This EXP claim is set in the front-end domain, so you'll want all your domains 
    | set with the same number.
	|
    | https://tools.ietf.org/html/rfc7519#section-4.1.4
	|
	*/
    'lasalle_jwt_exp_claim_seconds_to_expiration' => 3600,

    /*
	|--------------------------------------------------------------------------
	| Json Web Token IAT
	|--------------------------------------------------------------------------
	|
	| How many seconds should a JWT be valid after it is issued.
	|
    | The IAT claim is set automatically in the client domain.
    |
    | This duration is used in the API (back-end) domain as a time based validation.
    |
    | https://tools.ietf.org/html/rfc7519#section-4.1.6
	|
	*/
    'lasalle_jwt_iat_claim_valid_for_how_many_seconds' => 120,

     /*
    | ========================================================================
    | END: JWT AUTHENTICATION
    | ========================================================================
    */



    /*
    | ========================================================================
    | START: CONTENT GENERATION
    | ========================================================================
    */

    /*
	|--------------------------------------------------------------------------
	| Filesystem Disk Where Images Are Stored
	|--------------------------------------------------------------------------
	|
	| Which of the 'disks' in config\filesystems.php is used to store images? eg: 'local', 'public', 's3'.
	|
	| Beware that if you use the 'local' filesystem disk, then images will *not* be available to all
    | apps -- just the app that saved the image.
	|
	| So, generally, 's3' (or another cloud provider) is used.
	|
	*/
    'lasalle_filesystem_disk_where_images_are_stored'  => 's3',

    /*
	|--------------------------------------------------------------------------
	| Excerpt Length
	|--------------------------------------------------------------------------
	|
	| When an excerpt field is left blank, then the "content" field is used to
	| construct the excerpt. How many characters of the base "content" field
	| do you want to use for the excerpt?
	|
	*/
    'how_many_initial_chars_of_content_field_for_excerpt' => '250',

    /*
    | ========================================================================
    | END: CONTENT GENERATION
    | ========================================================================
    */



    /*
    | ========================================================================
    | START: GENERAL
    | ========================================================================
    */

    /*
    |--------------------------------------------------------------------------
    | Default user role
    |--------------------------------------------------------------------------
    |
    | If not otherwise set, what user role should be automatically assigned to new registrants?
    |
    | There are 3 user roles (see the lookup_roles database table):
    |
    | * Owner (1) (very much not recommended as a default)
    | * Super Administrator (2) (not recommended as a default either)
    | * Administrator (3) (recommended)
    |
    */
    'lasalle_app_default_user_role' => 3,

    
    /*
	|--------------------------------------------------------------------------
	| UUID Expiration
	|--------------------------------------------------------------------------
	|
    | How many days until a record in the "uuids" database table expires?
    |
    | Used in Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid's daysToExpiration() method.
	| 
	*/
    'uuid_number_of_days_until_expiration' => 7,

    /*
	|--------------------------------------------------------------------------
	| Deleting Nova's "action_events" database table records
	|--------------------------------------------------------------------------
	|
    | How many days until a deleting records in the "action_events" database table?
    |
    | "action_events" db table is Nova. 
    |
    | Used in Lasallesoftware\Librarybackend\Nova\DeleteActioneventsRecords class.
	| 
	*/
    'actionevents_number_of_days_until_deletion' => 14,

    /*
    | ========================================================================
    | END: GENERAL
    | ========================================================================
    */


    /*
    | ========================================================================
    | START: MIDDLEWARE
    | ========================================================================
    */

    /*
	|--------------------------------------------------------------------------
	| Do The Whitelist Check For Web Middleware
	|--------------------------------------------------------------------------
	|
	| There is a whitelist middleware.
    |
    | This middleware allows selected IP addresses access to the site.
	|
	| This middleware is assigned to the "web" middleware group only.
    |
    | Note: this check does *not* necessarily relate to logging in. Relates to routes associated with the
    |       "web" middleware group.
    |
    | Note: for "web" middleware associated routes, when *not* on whitelist then access denied! (401 Unauthorized)
	|
	*/
    'web_middleware_do_whitelist_check' => env('LASALLE_WEB_MIDDLEWARE_DO_WHITELIST_CHECK', 'no'),

    /*
	|--------------------------------------------------------------------------
	| Whitelisted IP Addresses
	|--------------------------------------------------------------------------
	|
	| IP addresses allowed access to the "web" middleware group.
    |
    | Must be an array of IP addresses.
	|
	*/
    'web_middleware_whitelist_ip_addresses' => [],

    /*
    | ========================================================================
    | END: MIDDLEWARE
    | ========================================================================
    */



    /*
	| ========================================================================
	| START: PATHS FOR FEATURED IMAGES
	| ========================================================================
	|
	| You may want to store featured image images (!) in S3 folders, and not just
	| in S3 buckets. And, you may want store Nova resource featured images in
	| their own folders. You can specify individual S3 folders here for profile
	| and blog resources.
	|
	| Must have a leading slash.
	| Must not have a trailing slash.
	|
	| Do not want to use an S3 folder at all? Just put the images in the S3 bucket?
	| Then, just specify '/',
	|
	| I designed this specifically for S3, but it applies generally because Nova
	| uses Laravel's storage facade
	| * https://laravel.com/docs/master/filesystem
	| * https://nova.laravel.com/docs/2.0/resources/file-fields.html#file-fields
	|
	| IMPORTANT!!! ****You need to set up each S3 folder in your AWS console.****
	| See https://github.com/LaSalleSoftware/ls-adminbackend-app/blob/master/AWS_S3_NOTES_README.md
	|
    */

    // for Nova resources in the novabackend package
    'image_path_for_address_nova_resource' => '/',
    //'image_path_for_address_nova_resource' => '/address',

    'image_path_for_company_nova_resource' => '/',
    //'image_path_for_company_nova_resource' => '/company',

    'image_path_for_person_nova_resource'  => '/',
    //'image_path_for_person_nova_resource'  => '/person',


    // for Nova resources in the blogbackend package
    'image_path_for_category_nova_resource' => '/',
    //'image_path_for_category_nova_resource' => '/category',

    'image_path_for_post_nova_resource'     => '/',
    //'image_path_for_post_nova_resource'     => '/post',

    /*
	| ========================================================================
	| END: PATHS FOR FEATURED IMAGES
	| ========================================================================
   */



    /*
	| ========================================================================
	| START: BACK-END BLADE VIEW PATHS
	| ========================================================================
    */

    /*
	|--------------------------------------------------------------------------
	| Back-end view path
	|--------------------------------------------------------------------------
	|
	*/
    'path_to_back_end_view_path' => 'lasalleuibackend::basic',

    /*
	|--------------------------------------------------------------------------
	| Back-end authentication view path
	|--------------------------------------------------------------------------
	|
	*/
    'path_to_back_end_authentication_view_path' => 'lasalleuibackend::authentication',

    /*
	| ========================================================================
	| END: BACK-END BLADE VIEW PATHS
	| ========================================================================
    */
];
