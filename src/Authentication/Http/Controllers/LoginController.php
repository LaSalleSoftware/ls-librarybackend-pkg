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
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Authentication\Http\Controllers;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Http\Controllers\CommonController;
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;

// LaSalle Software App
//use App\Providers\RouteServiceProvider;

// Laravel Framework
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

/**
 * Class LoginController
 *
 * @package Lasallesoftware\Librarybackend\Authentication\Http\Controllers
 */
class LoginController extends CommonController
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * The UuidGenerator instance
     *
     * @var Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator
     */
    protected $uuidGenerator;


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UuidGenerator $uuidGenerator)
    {
        if (! $this->executeTheWhitelistManually() ) {
            // The remote IP address is NOT white listed
            abort(401, __('lasallesoftwarelibrarybackend::auth.unauthorized'));
        }

        
        $this->middleware('guest')->except('logout');

        //$this->middleware('Nova.guest:'.config('Nova.guard'))->except('logout');

        $this->uuidGenerator = $uuidGenerator;

        $this->redirectTo = config('lasallesoftware-librarybackend.web_middleware_default_path');
    }

    /**
     * Show the application's login form.
     *
     * Overrides Illuminate\Foundation\Auth\AuthenticatesUsers::showLoginForm()
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        return view( config('lasallesoftware-librarybackend.path_to_back_end_authentication_view_path') . '.login.no_2fa.login' );
    }

    /**
     * Handle a login request to the application.
     * 
     * Overrides Illuminate\Foundation\Auth\AuthenticatesUsers::login(Request $request)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        // first thing is to create a uuid
        $this->uuidGenerator->createUuid(4, "from LoginControler::login()");

        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }
}
