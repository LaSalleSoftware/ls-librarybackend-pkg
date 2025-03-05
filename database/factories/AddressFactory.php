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


// Laravel class
use Illuminate\Support\Str;

// Third party class
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;


$factory->define(Lasallesoftware\Librarybackend\Profiles\Models\Address::class, function (Faker $faker) {

    $address_line_1     = $faker->streetAddress();
    $address_line_2     = $faker->secondaryAddress();
    $address_line_3     = $faker->secondaryAddress();
    $address_line_4     = $faker->secondaryAddress();
    $city               = $faker->city();
    $province           = $faker->state();
    $country            = $faker->country();
    $postal_code        = $faker->postcode();

    $address_calculated = $address_line_1 . " " .
        $address_line_2 . " " .
        $address_line_3 . " " .
        $address_line_4 . ", " .
        $city . ", " .
        $province . ", " .
        $country . "  " .
        $postal_code
    ;

    return [
        'lookup_address_type_id' => $faker->numberBetween($min = 1, $max = 5),
        'title'                  => '',
        'address_calculated'     => $address_calculated,
        'address_line_1'         => $address_line_1,
        'address_line_2'         => $address_line_2,
        'address_line_3'         => $address_line_3,
        'address_line_4'         => $address_line_4,
        'city'                   => $city,
        'province'               => $province,
        'country'                => $country,
        'postal_code'            => $postal_code,
        'latitude'               => $faker->latitude($min = -90, $max = 90),
        'longitude'              => $faker->longitude($min = -180, $max = 180),
        'description'            => $faker->sentence(6, true),
        'comments'               => $faker->paragraph(3, true),
        'profile'                => $faker->paragraph(3, true),
        //'featured_image'         => file(),
        'featured_image'         => null,
        //'uuid'                   => function () {
        //    return \Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator(1, null, 1);
        //}
        'uuid'                   => $faker->uuid(),
        'created_at'             => Carbon::now(null),
        'created_by'             => 1,
        'updated_at'             => null,
        'updated_by'             => null,
        'locked_at'              => null,
        'locked_by'              => null,
    ];
});
