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
 * @see       https://lasallesoftware.ca
 * @see       https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see       https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 */


// Laravel class
use Illuminate\Support\Str;

// Third party class
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Lasallesoftware\Librarybackend\Profiles\Models\Person::class, function (Faker $faker) {

    return [
        //'name_calculated'  => $name_calculated,  *** populated by the model event!!**
        'salutation'       => $faker->title(),
        'first_name'       => $faker->firstName(),
        'middle_name'      => $faker->firstName(),
        'surname'          => $faker->lastName(),
        'position'         => $faker->jobTitle,
        'description'      => $faker->sentence(6, true),
        'comments'         => $faker->paragraph(30, true),
        'profile'          => $faker->paragraph(3, true),
        //'featured_image' => file(),
        'featured_image'   => null,
        'birthday'         => $faker->dateTimeBetween("-40 years", "-30 years", null),
        'anniversary'      => $faker->dateTimeBetween("-10 years", "now", null),
        'deceased'         => null,
        'comments_date'    => null,
        //'uuid'           => function () {
        //    return \Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator(1, null, 1);
        //}
        'uuid'             => $faker->uuid(),
        'created_at'       => Carbon::now(null),
        'created_by'       => 1,
        'updated_at'       => null,
        'updated_by'       => null,
        'locked_at'        => null,
        'locked_by'        => null,
    ];
});

