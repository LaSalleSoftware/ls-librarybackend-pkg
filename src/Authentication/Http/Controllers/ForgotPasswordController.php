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

namespace Lasallesoftware\Librarybackend\Authentication\Http\Controllers;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Http\Controllers\CommonController;

// Laravel Framework
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


class ForgotPasswordController extends CommonController
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;



    public function __construct()
    {
        if (! $this->executeTheWhitelistManually() ) {
            // The remote IP address is NOT white listed
            abort(401, __('lasallesoftwarelibrarybackend::auth.unauthorized'));
        }
    }

    

    /**
     * Display the form to request a password reset link.
     * 
     * Overrides Illuminate\Foundation\Auth\SendsPasswordResetEmails::showLinkRequestForm()
     *
     * @return \Illuminate\View\View
     */
    public function showLinkRequestForm()
    {
        return view( config('lasallesoftware-librarybackend.path_to_back_end_authentication_view_path') . '.passwords.email' );        
    }
}
