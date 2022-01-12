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
 * @copyright  (c) 2019-2022 The South LaSalle Trading Corporation
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

$factory->define(Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid::class, function (Faker $faker) {
    return [
        'lasallesoftware_event_id' => 1,
        'uuid'                     => (string)Str::uuid(),
        'comments'                 => $faker->paragraph(),
        'created_at'               => Carbon::now(null),
        'created_by'               => 1,
    ];
});
