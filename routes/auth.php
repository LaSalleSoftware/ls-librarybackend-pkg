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

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
|
| From php artisan make:en
|
| https://github.com/laravel/framework/blob/f769989694cdcb77e53fbe36d7a47cd06371998c/src/Illuminate/Routing/Router.php#L1178
|
*/

Route::group(['middleware' => ['web']], function () {

    Route::get('/home', 'Lasallesoftware\Library\Authentication\Http\Controllers\HomeController@index')->name('home');

// Authentication Routes...
    Route::get('login',   'Lasallesoftware\Library\Authentication\Http\Controllers\LoginController@showLoginForm')->name('login');
    Route::post('login',  'Lasallesoftware\Library\Authentication\Http\Controllers\LoginController@login');
    Route::get('logout',  'Lasallesoftware\Library\Authentication\Http\Controllers\LogoutController@showLogoutForm')->name('nova.logout');
    Route::post('logout', 'Lasallesoftware\Library\Authentication\Http\Controllers\LogoutController@logout')->name('logout');

// Registration Routes...
    Route::get('register',  'Lasallesoftware\Library\Authentication\Http\Controllers\RegisterController@showRegistrationForm')->name('register');
    Route::post('register', 'Lasallesoftware\Library\Authentication\Http\Controllers\RegisterController@register');

// Password Reset Routes...
    Route::get('password/reset',  'Lasallesoftware\Library\Authentication\Http\Controllers\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    Route::post('password/email', 'Lasallesoftware\Library\Authentication\Http\Controllers\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    Route::get('password/reset/{token}', 'Lasallesoftware\Library\Authentication\Http\Controllers\ResetPasswordController@showResetForm')->name('password.reset');
    Route::post('password/reset', 'Lasallesoftware\Library\Authentication\Http\Controllers\ResetPasswordController@reset')->name('password.update');

// Email Verification Routes...
    Route::get('email/verify',      'Lasallesoftware\Library\Authentication\Http\Controllers\VerificationController@show')->name('verification.notice');
    Route::get('email/verify/{id}', 'Lasallesoftware\Library\Authentication\Http\Controllers\VerificationController@verify')->name('verification.verify');
    Route::get('email/resend',      'Lasallesoftware\Library\Authentication\Http\Controllers\VerificationController@resend')->name('verification.resend');

// Password Confirm Routes...
    Route::get('password/confirm',  'Lasallesoftware\Library\Authentication\Http\Controllers\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
    Route::post('password/confirm', 'Lasallesoftware\Library\Authentication\Http\Controllers\ConfirmPasswordController@confirm');

});


