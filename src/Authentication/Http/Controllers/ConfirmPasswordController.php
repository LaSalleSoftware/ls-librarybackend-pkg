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
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Authentication\Http\Controllers;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Http\Controllers\CommonController;
use Lasallesoftware\Laravelapp\Providers\RouteServiceProvider;

// Laravel Framework
use Illuminate\Foundation\Auth\ConfirmsPasswords;

class ConfirmPasswordController extends CommonController
{
    /*
    |--------------------------------------------------------------------------
    | Confirm Password Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password confirmations and
    | uses a simple trait to include the behavior. You're free to explore
    | this trait and override any functions that require customization.
    |
    */
    use ConfirmsPasswords;

    /**
     * Where to redirect users when the intended url fails.
     *
     * @var string
     */
    //protected $redirectTo = '/home';
    protected $redirectTo = RouteServiceProvider::HOME;

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
     * Display the password confirmation view.
     * 
     * OverridesIlluminate\Foundation\Auth\ConfirmsPasswords::showConfirmForm()
     *
     * @return \Illuminate\View\View
     */
    public function showConfirmForm()
    {
        return view( config('lasallesoftware-librarybackend.path_to_back_end_authentication_view_path') . '.passwords.confirm' );
    }
}
