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
use Lasallesoftware\Librarybackend\Authentication\Models\TwoFactorAuthentication;
use Lasallesoftware\Librarybackend\Authentication\Mail\EmailTwoFactorCodeToUser;
use Lasallesoftware\Librarybackend\Common\Http\Controllers\CommonController;
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;

// Laravel Framework
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;


/**
 * Class Login2FAController
 *
 * @package Lasallesoftware\Librarybackend\Authentication\Http\Controllers
 */
class Login2FAController extends CommonController
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo;

    /**
     * The TwoFactorAuthentication instance
     *
     * @var Lasallesoftware\Librarybackend\Authentication\Models\TwoFactorAuthentication
     */
    protected $twofactorauthentication; 

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
    public function __construct(TwoFactorAuthentication $twofactorauthentication, UuidGenerator $uuidGenerator)
    {
        
        if (! $this->executeTheWhitelistManually() ) {
            // The remote IP address is NOT white listed
            abort(401, __('lasallesoftwarelibrarybackend::auth.unauthorized'));
        }


        $this->middleware('guest')->except('logout');

        //$this->middleware('Nova.guest:'.config('Nova.guard'))->except('logout');

        $this->twofactorauthentication = $twofactorauthentication;

        $this->uuidGenerator = $uuidGenerator;

        $this->redirectTo = config('lasallesoftware-librarybackend.web_middleware_default_path');
    }



    /***************************************************************************************************************
     * 2FA STEP 1:  Display the form to input the email address
     * 
     * @return \Illuminate\Http\Response
     ***************************************************************************************************************/
    public function stepOne()
    {
        return view( config('lasallesoftware-librarybackend.path_to_back_end_authentication_view_path') . '.login.2fa.step1' );
    }



    /***************************************************************************************************************
     * 2FA STEP 1A: Verify the inputted email address. 
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     ***************************************************************************************************************/
    public function stepOneA(Request $request)
    {
        // validate the email address
        $request->validate([
            'email' => 'required|email|exists:personbydomains',
        ]);

        // if the email is validated, then proceed to the next step
        return $this->stepTwo($request->get('email'));
    }


    /***************************************************************************************************************
     * 2FA STEP 2: Send the code
     *
     * @param  string  $email   Email address
     * @param  array   $error   To send errors back to the view (https://laravel.com/docs/7.x/validation#manually-creating-validators)
     * @return void
     ***************************************************************************************************************/
    public function stepTwo($email, $error = null)
    {
        // send the code
        $this->send2faCode($email);

        // proceed to the next step
        return $this->stepTwoA($email, $error);
    }

    /***************************************************************************************************************
     * 2FA STEP 2A: Display the form to input the code that was just sent
     *
     * @param  string  $email   Email address
     * @param  array   $error   To send errors back to the view (https://laravel.com/docs/7.x/validation#manually-creating-validators)
     * @return void
     ***************************************************************************************************************/
    public function stepTwoA($email, $error = null)
    {
        // display the form to input the code that was just sent
        return view( config('lasallesoftware-librarybackend.path_to_back_end_authentication_view_path') . '.login.2fa.step2', [
            'email' => $email,
        ])->withErrors($error);
    }



    /***************************************************************************************************************
     * 2FA STEP 2B: Does the code match? If so, then display the form so the user can input their password
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     ***************************************************************************************************************/
    public function stepTwoB(Request $request)
    { 
        // increment the number of times this two factor authentication code has tried to be validated
        $this->twofactorauthentication->incrementNumberOfAttemptsToValidate($request->get('email'));


        // is the same 2FA code being validated too many times? if so, send a new 2FA code
        $attempts_allowed = config('lasallesoftware-librarybackend.number_of_attempts_allowed_to_validate_a_two_factor_code');

        if ($this->twofactorauthentication->totalNumberOfAttemptsToValidate($request->get('email')) > $attempts_allowed) {

            // exceeded allowed attempts: send a fresh 2FA code
            return $this->stepTwo($request->get('email'), ['two_factor_code' => __('lasallesoftwarelibrarybackend::auth.2fa_step2_error_exceedallowedattempts')]);
        }


        // has the two factor code expired?
        if ($this->twofactorauthentication->isTwoFactorCodeExpired($request->get('email'))) {

            // code has expired: send a fresh 2FA code
            return $this->stepTwo($request->get('email'), ['two_factor_code' => __('lasallesoftwarelibrarybackend::auth.2fa_step2_error_expired')]);
        }


        // is the two factor code correct?
        if ($this->twofactorauthentication->getTwoFactorCodeValue($request->get('email')) != $request->get('two_factor_code')) {

            // the code the user inputted is wrong: return to two factor code input form
            return $this->stepTwoA($request->get('email'), ['two_factor_code' => __('lasallesoftwarelibrarybackend::auth.2fa_step2_error_wrongcode')]);
        }


        // if the 2FA code validates then display the form so the user can input their password
        return $this->stepThree($request);
    }

    /***************************************************************************************************************
     * 2FA STEP 3: Display the form to input password
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  array                     $error   To send errors back to the view (https://laravel.com/docs/7.x/validation#manually-creating-validators)
     * @return void
     ***************************************************************************************************************/
    public function stepThree(Request $request, $error = null)
    {
        return view( config('lasallesoftware-librarybackend.path_to_back_end_authentication_view_path') . '.login.2fa.step3', [
            'email'           => $request->get('email'),
            'two_factor_code' => $request->get('two_factor_code')
        ])->withErrors($error);
    }



    /***************************************************************************************************************
     * 2FA STEP 3A: Does the password validate? If so, then login
     * 
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|
     ***************************************************************************************************************/
    public function stepThreeA(Request $request)
    {
        $password = DB::table('personbydomains')->where('email', $request->get('email'))->pluck('password')->first();
        if (! Hash::check($request->get('password'), $password)) {

            // wrong password

            // If the class is using the ThrottlesLogins trait, we can automatically throttle
            // the login attempts for this application. We'll key this by the username and
            // the IP address of the client making these requests into this application.
            if ($this->hasTooManyLoginAttempts($request)) {
                $this->fireLockoutEvent($request);

                return $this->sendLockoutResponse($request);
            }

            // try again
            return $this->stepThree($request, ['password' => __('lasallesoftwarelibrarybackend::auth.2fa_step3_error_wrongpassword')]);
        }


        // I want to ensure that the email and two factor authentication code are ok, just in case something was done to circumvent the process. 
        // Not sure what! But it makes me feel better. 
        if ($this->twofactorauthentication->getTwoFactorCodeValue($request->get('email')) != $request->get('two_factor_code')) {

            // The code is wrong! Abort the entire authentication!
            return $this->stepOne();
        }
        // If there is no record for the email in the twofactorauthorization database table, then abort the entire authication!
        if (! $this->twofactorauthentication->where('email', '=', $request->get('email'))->exists()) {

            // There should be a record for this email in the twofactorauthorization database table, but there is not. Abort authentication process!
            return $this->stepOne();
        }


        // delete the 2FA code
        $this->twofactorauthentication->deleteTwoFactorAuthenticationWithEmail($request->get('email'));

        // create a uuid
        $this->uuidGenerator->createUuid(4, "from Login2FAController::stepFive()");

        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }




    /**
     * Send the 2FA code 
     *
     * @param string  $email
     * @return void
     */
    public function send2faCode($email)
    {
        // create the code
        $code = Str::lower(Str::random(7));

        $data = [
            'email'           => $email,
            'two_factor_code' => $code,
        ];

        // delete any existing twofactorauthentication records for the email address that wants to log in
        $this->twofactorauthentication->deleteTwoFactorAuthenticationWithEmail($data['email']);

        // insert the code into the database
        $this->twofactorauthentication->createNewTwoFactorAuthenticationRecord($data);

        // email the code
        Mail::to($email)->queue(new EmailTwoFactorCodeToUser($data));
    }
}
