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
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Authentication\CustomGuards;

// LaSalle Software
use Lasallesoftware\Librarybackend\Authentication\Models\Login as LoginModel;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;

// Laravel Framework
use Illuminate\Auth\Events\Attempting;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Auth\Events\CurrentDeviceLogout;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\OtherDeviceLogout;
use Illuminate\Auth\Events\Validated;

use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\StatefulGuard;

// Not supporting basic auth
//use Illuminate\Contracts\Auth\SupportsBasicAuth;
use Illuminate\Contracts\Auth\UserProvider;

// I do not want to use cookies for authentication 
//use Illuminate\Contracts\Cookie\QueueingFactory as CookieJar;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Contracts\Session\Session;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Traits\Macroable;

use RuntimeException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


// Sure, being in the same folder means I do not need a "use" statement. 
// OTOH, I want to see each class that I am using, because it helps me in my efforts
// to upgrade my custom guard to the latest Laravel Framework version.
// I'm upgrading from L6 to L7, and L8 is due in July 2020! 
use Illuminate\Auth\GuardHelpers;


/**
 * Class LasalleGuard.
 *
 * Custom guard for LaSalle Software.
 *
 * Based on https://github.com/laravel/framework/blob/5.8/src/Illuminate/Auth/SessionGuard.php.
 * For reference, I commented out some code that is in the original SessionGuard.php, instead of deleting it outright.
 *
 * @package Lasallesoftware\Librarybackend\Authentication\CustomGuards
 */

//class SessionGuard implements StatefulGuard, SupportsBasicAuth
class LasalleGuard implements StatefulGuard
{
    use GuardHelpers, Macroable;


    /**
     * The name of the Guard. Typically "session".
     *
     * Corresponds to guard name in authentication configuration.
     *
     * @var string
     */
    protected $name;

    /**
     * The user we last attempted to retrieve.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    protected $lastAttempted;

    /**
     * Indicates if the user was authenticated via a recaller cookie.
     *
     * The viaRemember() method exists in the StatefulGuard contract, and I am implementing this contract even
     * though I am not implementing this feature, so this property is always false.
     *
     * @var bool
     */
    protected $viaRemember = false;

    /**
     * The session used by the guard.
     *
     * @var \Illuminate\Contracts\Session\Session
     */
    protected $session;

    /**
     * The Illuminate cookie creator service.
     *
     * Not implementing this feature.
     *
     * @var \Illuminate\Contracts\Cookie\QueueingFactory
     */
    //protected $cookie;

    /**
     * The request instance.
     *
     * @var \Symfony\Component\HttpFoundation\Request
     */
    protected $request;

    /**
     * The event dispatcher instance.
     *
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * Indicates if the logout method has been called.
     *
     * @var bool
     */
    protected $loggedOut = false;

    /**
     * Indicates if a token user retrieval has been attempted.
     *
     * Not implementing this feature.
     *
     * @var bool
     */
    //protected $recallAttempted = false;

    /**
     * The LaSalle Software's Login model instance.
     *
     * @var Lasallesoftware\Librarybackend\Authentication\Models\Login;
     */
    protected $loginModel;


    /**
     * Create a new authentication guard.
     *
     * @param  string                                                       $name
     * @param  \Illuminate\Contracts\Auth\UserProvider                      $provider
     * @param  \Symfony\Component\HttpFoundation\Request|null               $request
     * @param  \Illuminate\Contracts\Session\Session                        $session
     * @param  Lasallesoftware\Librarybackend\Authentication\Models\Login   $loginModel  (added by Bob)
     * @return void
     */
    public function __construct($name,
                                UserProvider $provider,
                                Session $session,
                                Request $request,
                                LoginModel $loginModel)
    {
        $this->name       = $name;
        $this->session    = $session;
        $this->request    = $request;
        $this->provider   = $provider;


        // added by Bob
        $this->loginModel = $loginModel;
    }


    ///////////////////////////////////////////////////////////////////
    ////////      START: METHODS FROM THE GUARD CONTRACT       ////////
    ///////////////////////////////////////////////////////////////////


    // Well, gee, this class does not implement the guard contract, nor is there a "use" statement for the guard
    // contract. So what's happening? Well, this class implements the StatefulGuard contact which extends the
    // guard contract. So, if this class was not going to implement the StatefulGuard contract, then this class
    // would implement the Guard contract directly. Laravel's RequestGuard class does this exactly: implements the
    // Guard contract directly because it does not implement StatefulGuard.
    // https://github.com/laravel/framework/blob/5.8/src/Illuminate/Auth/RequestGuard.php
    // https://github.com/laravel/framework/blob/5.8/src/Illuminate/Auth/TokenGuard.php

    // The following methods in the Guard contract reside in the GuardHelper trait:
    //  ** public function check();
    //  ** public function guest();

    // The following methods in the Guard contract reside in this class:
    //  ** public function user();
    //  ** public function id();  (in GuardHelpers but overridden here)
    //  ** public function validate(array $credentials = []);
    //  ** public function setUser(Authenticatable $user);

    /**
     * Get the currently authenticated user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function user()
    {
        // if the user logged out, then abort!
        if ($this->loggedOut) {
            return;
        }

        // If we've already retrieved the user for the current request we can just
        // return it back immediately. We do not want to fetch the user data on
        // every call to this method because that would be tremendously slow.
        if (! is_null($this->user)) {
            return $this->user;
        }

        // Laravel says that you are logged in by virtue of having your (users) id resident in the session.
        // I need that, and also that your (personbydomains) id and login token have a record in the logins table.
        // One (personbydomains) user can have multiple logins, which is tracked by the sessions and by the logins table.
        // No record in the logins table, no login! So, if someone is banned, all their records in the logins table
        // are deleted, and despite all the session records, that person is logged out.

        // get the personbydomains database table's primary_id from the session
        $id = $this->getSessionKey($this->getName());

        // if this personbydomain is banned, then do not authenticate
        if (Personbydomain::where('id', $id)->pluck('banned_enabled')->first() == 1) {
            return;
        }

        // get the LaSalle loginToken (as opposed to Laravel's _token) from the from the session
        $loginToken = $this->getSessionKey('loginToken');

        // get the record from the logins database table
        $resultGetLogin = $this->readLoginRecordByLogintoken($loginToken);

        // if there is a record in the logins database table,
        // and there is a record in the personbydomains database table, then we have the logged in person!
        // if ((! is_null($resultGetLogin)) && ($this->user = $this->provider->retrieveById($id))) {
        if ((! is_null($resultGetLogin)) && ($this->user = $this->getUserById($id))) {


                // IF THE EMERGENECY BAN IS ENABLED (set the env var/config param to true) 
                // THEN LOGOUT THIS LOGGED-IN USER
                if ($this->emergencyBanAllUsersFromLoggingIn()) {

                    $this->logout();                    
                    return;
                }



            $this->loginModel->updateTheUpdateFieldsWithTheTokenAndUserId($loginToken, $id);
            $this->fireAuthenticatedEvent($this->user);
        }


        // IF THE EMERGENECY BAN IS ENABLED (set the env var/config param to true) 
        // THEN LOGOUT THIS LOGGED-IN USER
        if ($this->emergencyBanAllUsersFromLoggingIn()) {
               
            return;
        }

        return $this->user;
    }


    // See below @ "METHODS THAT ARE IN THE ORIGINAL SESSIONGUARD.php BUT ARE NOT IN A CONTRACT"
    // This is where this function resides in SessionGuard.php
    //protected function userFromRecaller($recaller)

    // See below @ "METHODS THAT ARE IN THE ORIGINAL SESSIONGUARD.php BUT ARE NOT IN A CONTRACT"
    // This is where this function resides in SessionGuard.php
    // protected function recaller()  
 
    
     /**
     * Get the ID for the currently authenticated user.
     *
     * This method is implemented in the Illuminate\Auth\GuardHelpers trait BUT OVERRIDEN HERE.
     * This override is straight from Laravel.
     *
     * @return int|null
     */
    public function id()
    {
        if ($this->loggedOut) {
            return;
        }

        return $this->user()
            ? $this->user()->getAuthIdentifier()
            : $this->session->get($this->getName());
    }


    // See below @ "METHODS FROM THE STATEFULGUARD CONTRACT" 
    // This is where this function resides in SessionGuard.php
    // public function once(array $credentials = [])

    // See below @ "METHODS FROM THE STATEFULGUARD CONTRACT" 
    // This is where this function resides in SessionGuard.php
    // public function onceUsingId($id)


    /**
     * Validate a user's credentials.
     *
     * @param  array  $credentials
     * @return bool
     */
    public function validate(array $credentials = [])
    {
        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        return $this->hasValidCredentials($user, $credentials);
    }


    // See below @ "METHODS FROM THE SUPPORTSBASICAUTH CONTRACT THAT EXIST IN THE ORIGINAL SESSIONGUARD.PHP 
    // BUT ARE NOT USED IN MY LASALLEGUARD.php BECAUSE I DO NOT IMPLEMENT THE SUPPORTSBASICAUTH CONTRACT"
    // This is where this function resides in SessionGuard.php
    // public function basic($field = 'email', $extraConditions = [])

    // See below @ "METHODS FROM THE SUPPORTSBASICAUTH CONTRACT THAT EXIST IN THE ORIGINAL SESSIONGUARD.PHP 
    // BUT ARE NOT USED IN MY LASALLEGUARD.php BECAUSE I DO NOT IMPLEMENT THE SUPPORTSBASICAUTH CONTRACT"
    // This is where this function resides in SessionGuard.php
    // public function onceBasic($field = 'email', $extraConditions = [])

    // This is where these functions would be in SessionGuard.php
    // public function onceBasic($field = 'email', $extraConditions = [])
    // protected function basicCredentials(Request $request, $field)
    // protected function failedBasicResponse()

    // See below
    // This is where this function resides in SessionGuard.php 
    // public function attempt(array $credentials = [], $remember = false)


    /**
     * Set the current user.
     *
     * This method is implemented in the Illuminate\Auth\GuardHelpers trait BUT OVERRIDEN HERE.
     * Override straight from Laravel, I did not touch anything!
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return $this
     */
    public function setUser(AuthenticatableContract $user)
    {
        $this->user = $user;

        $this->loggedOut = false;

        $this->fireAuthenticatedEvent($user);

        return $this;
    }
    ///////////////////////////////////////////////////////////////////
    ////////      END: METHODS FROM THE GUARD CONTRACT       //////////
    ///////////////////////////////////////////////////////////////////


    ///////////////////////////////////////////////////////////////////
    //////    START: METHODS FROM THE STATEFULGUARD CONTRACT     //////
    ///////////////////////////////////////////////////////////////////

    /**
     * Attempt to authenticate a user using the given credentials.
     *
     * From the Illuminate\Contracts\Auth\StatefulGuard contract (but not using this interface!).
     *
     * @param  array  $credentials
     * @param  bool   $remember  ALWAYS FALSE BECAUSE I AM NOT IMPLEMENTING THIS FEATURE
     * @return bool
     */
    public function attempt(array $credentials = [], $remember = false)
    {
        $this->fireAttemptEvent($credentials, $remember);

        $this->lastAttempted = $user = $this->provider->retrieveByCredentials($credentials);

        // If an implementation of UserInterface was returned, we'll ask the provider
        // to validate the user against the given credentials, and if they are in
        // fact valid we'll log the users into the application and return true.
        if ($this->hasValidCredentials($user, $credentials)) {
            $this->login($user, $remember);

            return true;
        }

        // If the authentication attempt fails we will fire an event so that the user
        // may be notified of any suspicious attempts to access their account from
        // an unrecognized user. A developer may listen to this event as needed.
        $this->fireFailedEvent($user, $credentials);

        return false;
    }


    // See below @ "METHODS THAT ARE IN THE ORIGINAL SESSIONGUARD.php BUT ARE NOT IN A CONTRACT"
    // This is where these functions are in SessionGuard.php
    // protected function hasValidCredentials($user, $credentials)


    /**
     * Log the given user ID into the application.
     *
     * @param  mixed  $id
     * @param  bool   $remember
     * @return \Illuminate\Contracts\Auth\Authenticatable|false
     */
    public function loginUsingId($id, $remember = false)
    {
        // modified by Bob
        //if (! is_null($user = $this->provider->retrieveById($id))) {
        if (! is_null($user = $this->getUserById($id))) {
            $this->login($user, $remember);

            return $user;
        }

        return false;
    }

    /**
     * Log a user into the application.
     *
     * From the Illuminate\Contracts\Auth\StatefulGuard contract.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember  ALWAYS FALSE BECAUSE I AM NOT IMPLEMENTING THIS FEATURE
     * @return void
     */
    public function login(AuthenticatableContract $user, $remember = false)
    {
        // STEP 1: CREATE THE TOKEN
        $loginToken = $this->getLoginToken();

        // STEP 2: SAVE THE personbydomains PRIMARY ID AND THE LOGIN TOKEN TO THE SESSION
        $this->updateSession($user->id, $loginToken);

        // STEP 4: CREATE A NEW RECORD IN THE LOGINS DATABASE TABLE
        // prep the data first into an array
        $data = [
            'personbydomain_id' => $user->id,
            'token'             => $loginToken,
            'uuid'              => $GLOBALS['uuid_generator_uuid'],
            'created_by'        => 1,  // system
        ];

        // result is either the ID of the record just inserted, or false
        $result = $this->loginModel->createNewLoginsRecord($data);

        // STEP 4: FIRE THE EVENT
        // If we have an event dispatcher instance set we will fire an event so that
        // any listeners will hook into the authentication events and run actions
        // based on the login and logout events fired from the guard instances.
        $this->fireLoginEvent($user, $remember);

        // STEP 5: SET THE USER PROPERTY
        $this->setUser($user);
    }

    /**
     * Log a user into the application without sessions or cookies. 
     *
     * @param  array  $credentials
     * @return bool
     */
    public function once(array $credentials = [])
    {
        $this->fireAttemptEvent($credentials);

        if ($this->validate($credentials)) {
            $this->setUser($this->lastAttempted);

            return true;
        }

        return false;
    }

    // see below @ "METHODS THAT ARE IN THE ORIGINAL SESSIONGUARD.php BUT ARE NOT IN A CONTRACT"
    // This is where this function would be in SessionGuard.php
    // protected function updateSession($id)

    // Not implemented
    // This is where these functions are in SessionGuard.php
    // protected function ensureRememberTokenIsSet(AuthenticatableContract $user)
    // protected function queueRecallerCookie(AuthenticatableContract $user)
    // protected function createRecaller($value)

    /**
     * Log the given user ID into the application without sessions or cookies.
     * 
     * @param  mixed  $id
     * @return \Illuminate\Contracts\Auth\Authenticatable|bool
     */
    public function onceUsingId($id)
    {
        // modified by Bob
        //if (! is_null($user = $this->provider->retrieveById($id))) {
        if (! is_null($user = $this->getUserById($id))) {
            $this->setUser($user);
            return $user;
        }
        return false;
    }

    /**
     * Determine if the user was authenticated via "remember me" cookie.
     *
     * @return bool
     */
    public function viaRemember()
    {
        // modified by Bob
        // not implementing this feature
        //return $this->viaRemember;
        return false;
    }

    /**
     * Log the user out of the application.
     *
     * @return void
     */
    public function logout()
    {
        $user = $this->user();

        // If we have an event dispatcher instance, we can fire off the logout event
        // so any further processing can be done. This allows the developer to be
        // listening for anytime a user signs out of this application manually.
        $this->loginModel->deleteExistingLoginsRecordByLogintoken($this->session->get('loginToken'));
        $this->clearUserDataFromStorage();

        // commented out by Bob
        /*
        if (! is_null($this->user)) {
            $this->cycleRememberToken($user);
        }
        */

        if (isset($this->events)) {
            $this->events->dispatch(new Events\Logout($this->name, $user));
        }

        // Once we have fired the logout event we will clear the users out of memory
        // so they are no longer available as the user is no longer considered as
        // being signed into this application and should not be available here.
        $this->user = null;

        $this->loggedOut = true;
    }

    ///////////////////////////////////////////////////////////////////
    //////     END: METHODS FROM THE STATEFULGUARD CONTRACT     ///////
    ///////////////////////////////////////////////////////////////////



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////     START: METHODS FROM THE SUPPORTSBASICAUTH CONTRACT THAT EXIST IN THE ORIGINAL SESSIONGUARD.PHP  ///////
    //////            BUT ARE NOT USED IN MY LASALLEGUARD.php BECAUSE I DO NOT IMPLEMENT THE                  ///////
    //////            SUPPORTSBASICAUTH CONTRACT                                                               ///////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    // ** public function basic($field = 'email', $extraConditions = []);
    // ** public function onceBasic($field = 'email', $extraConditions = []);

    // There are a number of methods that the original SESSIONGUARD.php uses to implement the SUPPORTSBASICAUTH contract
    // but I am not listing them!

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //////       END: METHODS FROM THE SUPPORTSBASICAUTH CONTRACT THAT EXIST IN THE ORIGINAL SESSIONGUARD.PHP  ///////
    //////            BUT ARE NOT USED IN MY LASALLEGUARD.php BECAUSE I DO NOT IMPLEMENT THE                  ///////
    //////            SUPPORTSBASICAUTH CONTRACT                                                               ///////
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////



    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////   START: METHODS THAT ARE IN THE ORIGINAL SESSIONGUARD.php BUT ARE NOT IN A CONTRACT      //////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////

    /**
     * Update the session with the given ID and Login Token.
     *
     * @param  string  $id
     * @param  string  $loginToken
     * @return void
     */
    protected function updateSession($id, $loginToken)
    {
        // added by Bob
        $this->session->put('loginToken', $loginToken);


        $this->session->put($this->getName(), $id);

        $this->session->migrate(true);
    }

    /**
     * Pull a user from the repository by its "remember me" cookie token.
     *
     * Not implementing this feature.
     *
     * @param  \Illuminate\Auth\Recaller  $recaller
     * @return mixed
     */
    //protected function userFromRecaller($recaller) {}

    /**
     * Get the decrypted recaller cookie for the request.
     *
     * Not implementing this feature.
     *
     * @return \Illuminate\Auth\Recaller|null
     */
    //protected function recaller() {}


    /**
     * Determine if the user matches the credentials.
     *
     * @param  mixed  $user
     * @param  array  $credentials
     * @return bool
     */
    protected function hasValidCredentials($user, $credentials)
    {
        return ! is_null($user) && $this->provider->validateCredentials($user, $credentials);
    }

    /**
     * Remove the user data from the session and cookies.
     *
     * @return void
     */
    protected function clearUserDataFromStorage()
    {
        $this->session->remove($this->getName());
        $this->session->remove('loginToken');

        // commented out by Bob
        /*
        if (! is_null($this->recaller())) {
            $this->getCookieJar()->queue($this->getCookieJar()
                ->forget($this->getRecallerName()));
        }
        */
    }

    // Not implemented
    // protected function cycleRememberToken(AuthenticatableContract $user)

    /**
     * Log the user out of the application on their current device only.
     *
     * @return void
     */
    public function logoutCurrentDevice()
    {
        $user = $this->user();

        $this->clearUserDataFromStorage();

        // If we have an event dispatcher instance, we can fire off the logout event
        // so any further processing can be done. This allows the developer to be
        // listening for anytime a user signs out of this application manually.
        if (isset($this->events)) {
            $this->events->dispatch(new CurrentDeviceLogout($this->name, $user));
        }

        // Once we have fired the logout event we will clear the users out of memory
        // so they are no longer available as the user is no longer considered as
        // being signed into this application and should not be available here.
        $this->user = null;

        $this->loggedOut = true;
    }

    /**
     * Invalidate other sessions for the current user.
     *
     * The application must be using the AuthenticateSession middleware.
     *
     * Laravel also provides a mechanism for invalidating and "logging out" a user's sessions that are active on
     * other devices WITHOUT invalidating the session on their current device. Before getting started, you should
     * make sure that the  Illuminate\Session\Middleware\AuthenticateSession middleware is present and un-commented
     * in your app/Http/Kernel.php class' web middleware group.
     *
     * https://laravel.com/docs/5.8/authentication#invalidating-sessions-on-other-devices
     *
     * @param  string  $password
     * @param  string  $attribute
     * @return bool|null
     */
    public function logoutOtherDevices($password, $attribute = 'password')   //
    {
        // TODO: logout other devices feature for a future release? For now, this feature returns false
        return false;


        /*
        if (! $this->user()) {
            return;
        }

        $result = tap($this->user()->forceFill([
            $attribute => Hash::make($password),
        ]))->save();

        $this->queueRecallerCookie($this->user());

        return $result;
        */
    }

    /**
     * Register an authentication attempt event listener.
     *
     * @param  mixed  $callback
     * @return void
     */
    public function attempting($callback)
    {
        if (isset($this->events)) {
            $this->events->listen(Events\Attempting::class, $callback);
        }
    }

    /**
     * Fire the attempt event with the arguments.
     *
     * @param  array  $credentials
     * @param  bool  $remember
     * @return void
     */
    protected function fireAttemptEvent(array $credentials, $remember = false)
    {
        if (isset($this->events)) {
            $this->events->dispatch(new Attempting(
                $this->name, $credentials, $remember
            ));
        }
    }

    /**
     * Fires the validated event if the dispatcher is set.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function fireValidatedEvent($user)
    {
        if (isset($this->events)) {
            $this->events->dispatch(new Validated(
                $this->name, $user
            ));
        }
    }

    /**
     * Fire the login event if the dispatcher is set.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  bool  $remember
     * @return void
     */
    protected function fireLoginEvent($user, $remember = false)
    {
        if (isset($this->events)) {
            $this->events->dispatch(new Login(
                $this->name, $user, $remember
            ));
        }
    }

    /**
     * Fire the authenticated event if the dispatcher is set.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function fireAuthenticatedEvent($user)
    {
        if (isset($this->events)) {
            $this->events->dispatch(new Authenticated(
                $this->name, $user
            ));
        }
    }

    /**
     * Fire the other device logout event if the dispatcher is set.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return void
     */
    protected function fireOtherDeviceLogoutEvent($user)
    {
        if (isset($this->events)) {
            $this->events->dispatch(new OtherDeviceLogout(
                $this->name, $user
            ));
        }
    }

    /**
     * Fire the failed authentication attempt event with the given arguments.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable|null  $user
     * @param  array  $credentials
     * @return void
     */
    protected function fireFailedEvent($user, array $credentials)
    {
        if (isset($this->events)) {
            $this->events->dispatch(new Failed(
                $this->name, $user, $credentials
            ));
        }
    }

    /**
     * Get the last user we attempted to authenticate.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function getLastAttempted()
    {
        return $this->lastAttempted;
    }

    /**
     * Get a unique identifier for the en session value.
     *
     * @return string
     */
    public function getName()
    {
        return 'login_'.$this->name.'_'.sha1(static::class);
    }

    /**
     * Get the event dispatcher instance.
     *
     * @return \Illuminate\Contracts\Events\Dispatcher
     */
    public function getDispatcher()
    {
        return $this->events;
    }

    /**
     * Set the event dispatcher instance.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function setDispatcher(Dispatcher $events)
    {
        $this->events = $events;
    }

    /**
     * Get the session store used by the guard.
     *
     * @return \Illuminate\Contracts\Session\Session
     */
    public function getSession()
    {
        return $this->session;
    }

    /**
     * Return the currently cached user.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Get the current request instance.
     *
     * @return \Symfony\Component\HttpFoundation\Request
     */
    public function getRequest()
    {
        return $this->request ?: Request::createFromGlobals();
    }

    /**
     * Set the current request instance.
     *
     * @param  \Symfony\Component\HttpFoundation\Request  $request
     * @return $this
     */
    public function setRequest(Request $request)
    {
        $this->request = $request;

        return $this;
    }
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////
    ////////     END: METHODS THAT ARE IN THE ORIGINAL SESSIONGUARD.php BUT ARE NOT IN A CONTRACT      //////////
    /////////////////////////////////////////////////////////////////////////////////////////////////////////////



    ///////////////////////////////////////////////////////////////////
    ////////             START: METHODS BY BOB               //////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Create the login token
     *
     * @return string
     */
    public function getLoginToken()
    {
        return Str::random(40);
    }

    /**
     * Select from the logins table the record with the given login token
     *
     * @param  string  $loginToken
     * @return collection
     */
    public function readLoginRecordByLogintoken($loginToken)
    {
        return $this->loginModel->readLoginsRecordByLogintoken($loginToken);
    }

    /**
     * Retrieve the value of the given key from the session.
     *
     * This method exists to help with unit testing.
     *
     * @param  $key  Key saved in the session
     * @return mixed
     */
    public function getSessionKey($key)
    {
        return $this->session->get($key);
    }

    /**
     * @param $id
     *
     * Retrieve a user by their unique identifier.
     *
     * This method exists to help with unit testing.
     *
     * @param  mixed  $id
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function getUserById($id)
    {
        return $this->provider->retrieveById($id);
    }

    /**
     * Are all users banned from logging in?
     * 
     * Not going to delete logins database table records, nor sessions, in case they are needed for tracing.
     * 
     * https://github.com/LaSalleSoftware/ls-library-pkg/issues/80
     *
     * @return bool
     */
    public function emergencyBanAllUsersFromLoggingIn()
    {
        return config('lasallesoftware-librarybackend.ban_all_users_from_logging_into_the_admin_backend') ? true : false;
    }

    ///////////////////////////////////////////////////////////////////
    ////////               END: METHODS BY BOB               //////////
    ///////////////////////////////////////////////////////////////////
}
