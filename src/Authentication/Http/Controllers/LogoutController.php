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
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LogoutController extends CommonController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        if (! $this->executeTheWhitelistManually() ) {
            // The remote IP address is NOT white listed
            abort(401, __('lasallesoftwarelibrarybackend::auth.unauthorized'));
        }

        
        $this->middleware('auth');
    }

    /**
     * Show the application's logout form.
     *
     * Overrides Illuminate\Foundation\Auth\AuthenticatesUsers::showLoginForm()
     *
     * @return \Illuminate\Http\Response
     */
    public function showLogoutForm()
    {
        return view( config('lasallesoftware-librarybackend.path_to_back_end_authentication_view_path') . '.logout' );
    }

    /**
     * Log the user out of the application.
     *
     * From the Nova Laravel\Nova\Http\Controllers\LoginController override of AuthenticatesUsers::logout()
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect($this->redirectPath());
    }
}
