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
 * @copyright  (c) 2019-2024 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 *
 * @see       https://lasallesoftware.ca
 * @see       https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see       https://github.com/LaSalleSoftware/ls-librarybackend-pkg
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

    Route::get('/home', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\HomeController@index')->name('home');


// Authentication Routes...
    if (config('lasallesoftware-librarybackend.enable_two_factor_authentication')) {
        Route::get('login',           'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\Login2FAController@stepOne')->name('login');
        Route::post('login2fa_step1A', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\Login2FAController@stepOneA')->name('step1A');
        Route::post('login2fa_step2B', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\Login2FAController@stepTwoB')->name('step2B');
        Route::post('login2fa_step3A', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\Login2FAController@stepThreeA')->name('step3A');
    } else {
        Route::get('login',   'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\LoginController@showLoginForm')->name('login');
        Route::post('login',  'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\LoginController@login');
    }    
    Route::get('logout',  'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\LogoutController@showLogoutForm')->name('nova.logout');
    Route::post('logout', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\LogoutController@logout')->name('logout');

// Registration Routes...
    if (!config('lasallesoftware-librarybackend.suppress_registration_routes')) {
        Route::get('register',  'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\RegisterController@showRegistrationForm')->name('register');
        Route::post('register', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\RegisterController@register');
    }

// Password Reset Routes...
    if (!config('lasallesoftware-librarybackend.suppress_reset_password_routes')) {
        Route::get('password/reset',  'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\ForgotPasswordController@showLinkRequestForm')->name('password.request');
        Route::post('password/email', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
        Route::get('password/reset/{token}', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\ResetPasswordController@showResetForm')->name('password.reset');
        Route::post('password/reset', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\ResetPasswordController@reset')->name('password.update');
    }

// Email Verification Routes...
    if (!config('lasallesoftware-librarybackend.suppress_registration_verification_routes')) {
        Route::get('email/verify',      'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\VerificationController@show')->name('verification.notice');
        Route::get('email/verify/{id}', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\VerificationController@verify')->name('verification.verify');
        Route::get('email/resend',      'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\VerificationController@resend')->name('verification.resend');
    }

// Password Confirm Routes...
    Route::get('password/confirm',  'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\ConfirmPasswordController@showConfirmForm')->name('password.confirm');
    Route::post('password/confirm', 'Lasallesoftware\Librarybackend\Authentication\Http\Controllers\ConfirmPasswordController@confirm');

});


