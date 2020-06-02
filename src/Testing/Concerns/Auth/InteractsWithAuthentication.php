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

/**
 * Well, I realized that I could do my own "InteractsWithAuthentication". The joys of open source!
 * So, I will create http tests that are more targeted for my own testing. Yeah!
 */

namespace Lasallesoftware\Librarybackend\Testing\Concerns\Auth;

// LaSalle Software
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;

// Laravel
use Illuminate\Contracts\Auth\Authenticatable as UserContract;
use Illuminate\Support\Facades\Auth;

trait InteractsWithAuthentication
{
    /**
     * Login Bob using LasalleGuard's Login method.
     *
     * @param  string|null $driver  Null means that the default guard driver is used.
     *                              LasalleGuard is the default driver!
     * @return $this
     */
    public function loginBobWithLoginMethod($driver = null)
    {
        // $bobTheUser is and instance of \Illuminate\Contracts\Auth\Authenticatable
        $bobTheUser = Personbydomain::find(1)->first();

        $this->app['auth']->guard($driver)->login($bobTheUser);
        $this->app['auth']->shouldUse($driver);

        return $this;
    }

    /**
     * Login Bob using LasalleGuard's attempt method.
     *
     * @param  array $credentials  Login credentials
     * @param  string|null $driver        Null means that the default guard driver is used.
     *                                    LasalleGuard is the default driver!
     * @return $this
     */
    public function loginBobWithAttemptMethod($credentials, $driver = null)
    {
        $this->app['auth']->guard($driver)->attempt($credentials);
        $this->app['auth']->shouldUse($driver);

        return $this;
    }

    /**
     * Logout Bob using LasalleGuard's logout method.
     *
     * @param  string|null $driver        Null means that the default guard driver is used.
     *                                    LasalleGuard is the default driver!
     * @return $this
     */
    public function logoutBobWithLogoutMethod($driver = null)
    {
        $this->app['auth']->guard($driver)->logout();
        $this->app['auth']->shouldUse($driver);

        return $this;
    }

    /**
     * What does lasalleguard::user() return?
     *
     * @param  string|null $driver        Null means that the default guard driver is used.
     *                                    LasalleGuard is the default driver!
     * @return $this
     */
    public function seeWhatUserMethodReturns($driver = null)
    {
        $this->user = $this->app['auth']->guard($driver)->user();

        return $this;
    }

    /**
     * What does lasalleguard::id() return?
     *
     * @param  string|null $driver        Null means that the default guard driver is used.
     *                                    LasalleGuard is the default driver!
     * @return $this
     */
    public function seeWhatIdMethodReturns($driver = null)
    {
        $this->id = $this->app['auth']->guard($driver)->id();

        return $this;
    }

    /**
     * What does lasalleguard::loginUsingId() return?
     *
     * @param  int $id
     * @param  string|null $driver        Null means that the default guard driver is used.
     *                                    LasalleGuard is the default driver!
     * @return $this
     */
    public function seeWhatLoginusingidMethodReturns($id, $driver = null)
    {
        $this->app['auth']->guard($driver)->loginUsingId($id);

        return $this;
    }

    public function getTheNameFromTheGetNameMethod()
    {
        return $this->app['auth']->getName();
    }
}
