<?php

/**
 * This file is part of the Lasalle Software library package. 
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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/ls-library-pkg
 * @link       https://github.com/LaSalleSoftware/ls-library-pkg
 *
 */

// Laravel class
use Illuminate\Support\Str;

// Third party class
use Faker\Generator as Faker;
use Illuminate\Support\Carbon;

$factory->define(Lasallesoftware\Library\Profiles\Models\Social::class, function (Faker $faker) {
    return [
        'lookup_social_type_id' => $faker->numberBetween($min = 1, $max = 12),
        'url'                   => $faker->unique($reset = false)->url(),
        'description'           => $faker->sentence($nbWords = 6, $variableNbWords = false) ,
        'comments'              => $faker->paragraph($nbSentences = 3, $variableNbSentences = false),
        'uuid'                  => (string)Str::uuid(),
        'comments'              => $faker->paragraph(),
        'created_at'            => Carbon::now(null),
        'created_by'            => 1,
        'updated_at'            => null,
        'updated_by'            => null,
        'locked_at'             => null,
        'locked_by'             => null,
    ];
});
