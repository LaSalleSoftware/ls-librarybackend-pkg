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
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| See https://github.com/LaSalleSoftware/ls-librarybackend-pkg/issues/114
|
*/

Route::middleware(['jwt_auth'], 'throttle:60,1')
    ->group(function () {
        Route::get('/api/v1/phpserverlessprojectsponsorslist', 'Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Controllers\PhpserverlessprojectSponsorsController@GetLIst');
    }
);