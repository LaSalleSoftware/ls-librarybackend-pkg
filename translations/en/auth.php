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

return [

    'title_register'     => 'Register',
    'first_name'         => 'First Name',
    'surname'            => 'Last Name',
    'email_address'      => 'Email Address',
    'password'           => 'Password',
    'confirm_password'   => 'Confirm Password',
    'submit_register'    => 'Register',

    'title_login'        => 'Login',
    'forgot_password'    => 'Forgot Your Password?',
    'submit_login'       => 'Login',

    'failed'            => 'These credentials do not match our records.',
    'throttle'          => 'Too many login attempts. Please try again in :seconds seconds.',

    'unauthorized'      => 'Unauthorized',

    '2fa_step1_submit'  => 'Click to Proceed',
    '2fa_step1_title'   => 'Two Factor Authorization Login: Step 1 -- Email Address',
    '2fa_step1_submit'  => 'Click to Proceed',
    '2fa_step2_title'   => 'Two Factor Authorization Login: Step 2 -- Two Factor Code (please check your email!)',
    '2fa_step3_title'   => 'Two Factor Authorization Login: Step 3 -- Password',
    
  '2fa_two_factor_code' => 'Two Factor Code',
    '2fa_email_subject' => 'Your Two Factor Authentication Code',
    '2fa_email_line1'   => 'You are in the process of logging into ' . config('app.url') . '.',
    '2fa_email_line2'   => 'Here is your two factor authentication code. Please enter this code into the form.',
    '2fa_email_warn'    => 'This is a time sensitive code that will expire shortly.',

    '2fa_step2_error_exceedallowedattempts' => 'Too many attempts! Please check your email to enter your fresh Two Factor Code',
    '2fa_step2_error_expired'               => 'Your Two Factor Code has expired! Please check your email to enter your fresh Two Factor Code',
    '2fa_step2_error_wrongcode'             => 'You entered an incorrect two factor code. Please try again.',
    '2fa_step3_error_wrongpassword'         => 'You entered an incorrect password. Please try again.',
];
