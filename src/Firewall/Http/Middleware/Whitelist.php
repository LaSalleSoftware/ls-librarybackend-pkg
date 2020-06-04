<?php

namespace Lasallesoftware\Librarybackend\Firewall\Http\Middleware;

// LaSalle Software
use Lasallesoftware\Librarybackend\Helpers\GeneralHelpers;

// PHP
use Closure;

class Whitelist
{
    use GeneralHelpers;

    public function bob()
    {
        return "bob!";
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure                  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Are we supposed to be doing this check?
        if (strtolower(config('lasallesoftware-librarybackend.web_middleware_do_whitelist_check') != 'yes')) {
            return $next($request);
        }

        // Yes, we are supposed to be doing this check for white listed IP addresses...

        // ...get the white listed IP addresses
        $whitelistedIpAddresses = $this->getWhitelistedIpAddresses();

        // ...get the remote IP addresses where the request is coming from
        $remoteIpAddress = $this->getRemoteIpAddress();

        // ...do the comparison
        if ($this->isValueInArray($remoteIpAddress, $whitelistedIpAddresses)) {

            // The remote IP address is white listed
            return $next($request);
        }

        // The remote IP address is NOT white listed
        abort(401, __('lasallesoftwarelibrarybackend::auth.unauthorized'));
    }


    /**
     * What is the IP address the request is coming from. Equivalent to $_SERVER ['REMOTE_ADDR']
     *
     * A major reason for creating this method is to mock its returned value at Tests\Feature\Middleware\Whitelist\
     *
     * @return string
     */
    public function getRemoteIpAddress()
    {
        return request()->ip();
    }

    /**
     * Get the IP addresses that are white listed
     *
     * A major reason for creating this method is to mock its returned value at Tests\Feature\Middleware\Whitelist\
     *
     * @return array
     */
    public function getWhitelistedIpAddresses()
    {
        $whitelistedIpAddressesFromEnv = explode(',',env('LASALLE_WEB_MIDDLEWARE_WHITELIST_IP_ADDRESSES'));

        $whitelistedIpAddressesFromConfig = config('lasallesoftware-librarybackend.web_middleware_whitelist_ip_addresses');

        return array_merge(
            $whitelistedIpAddressesFromEnv,
            $whitelistedIpAddressesFromConfig
        );
    }

}
