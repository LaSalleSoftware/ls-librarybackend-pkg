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

namespace Lasallesoftware\Librarybackend\Authentication\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectSomeRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            return $next($request);
        }

        if (($request->path() == "home")                 ||
            ($request->path() == "/")                    ||
            ($request->path() == "nova")                 ||
            ($request->path() == "nova/dashboards")      ||
            ($request->path() == "nova/dashboards/main") ||
            ($request->path() == "nova/resources") )
        {
            $default_path = config('lasallesoftware-librarybackend.web_middleware_default_path');
            return redirect($default_path);
        }

        return $next($request);
    }
}