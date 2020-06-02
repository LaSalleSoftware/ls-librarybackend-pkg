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
 * @see       https://lasallesoftware.ca
 * @see       https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see       https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 */


// Laravel class
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;

// Third party class
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Lasallesoftware\Librarybackend\Profiles\Models\Website::class, function (Faker $faker) {
    return [
        'lookup_website_type_id' => $faker->numberBetween($min = 1, $max = 6),
        'url'                    => $faker->unique($reset = false)->url(),
        'description'            => $faker->sentence($nbWords = 6, $variableNbWords = false) ,
        'comments'               => $faker->paragraph($nbSentences = 3, $variableNbSentences = false),
        'uuid'                   => (string)Str::uuid(),
        'comments'               => Crypt::encrypt($faker->paragraph()),
        'created_at'             => Carbon::now(null),
        'created_by'             => 1,
        'updated_at'             => null,
        'updated_by'             => null,
        'locked_at'              => null,
        'locked_by'              => null,
    ];
});
