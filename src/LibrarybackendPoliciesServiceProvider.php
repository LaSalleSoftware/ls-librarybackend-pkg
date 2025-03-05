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
 * @see       https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see       https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 */

namespace Lasallesoftware\Librarybackend;

// Laravel class
use Illuminate\Support\Facades\Gate;

/**
 * Trait LibrarybackendPoliciesServiceProvider
 *
 * Adapted from https://github.com/laravel/framework/blob/5.8/src/Illuminate/Foundation/Support/Providers/AuthServiceProvider.php
 *
 * @package Lasallesoftware\Librarybackend
 */
trait LibrarybackendPoliciesServiceProvider
{
    /**
     * Register the application's policies.
     *
     * @return void
     */
    public function registerPolicies()
    {
        foreach ($this->policies() as $key => $value) {
            Gate::policy($key, $value);
        }
    }
    /**
     * Get the policies defined on the provider.
     *
     * @return array
     */
    public function policies()
    {
        return [
            'Lasallesoftware\Librarybackend\Profiles\Models\Lookup_address_type'   => 'Lasallesoftware\Librarybackend\Policies\Lookup_address_typePolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain'      => 'Lasallesoftware\Librarybackend\Policies\Installed_domainPolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Lookup_email_type'     => 'Lasallesoftware\Librarybackend\Policies\Lookup_email_typePolicy',

            'Lasallesoftware\Librarybackend\LaSalleSoftwareEvents\Models\Lookup_lasallesoftware_event'
                                                                            => 'Lasallesoftware\Librarybackend\Policies\Lookup_lasallesoftware_eventPolicy',

            'Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role'     => 'Lasallesoftware\Librarybackend\Policies\Lookup_rolePolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Lookup_social_type'    => 'Lasallesoftware\Librarybackend\Policies\Lookup_social_typePolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Lookup_telephone_type' => 'Lasallesoftware\Librarybackend\Policies\Lookup_telephone_typePolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Lookup_website_type'   => 'Lasallesoftware\Librarybackend\Policies\Lookup_website_typePolicy',

            'Lasallesoftware\Librarybackend\Profiles\Models\Address'               => 'Lasallesoftware\Librarybackend\Policies\AddressPolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Email'                 => 'Lasallesoftware\Librarybackend\Policies\EmailPolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Social'                => 'Lasallesoftware\Librarybackend\Policies\SocialPolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Telephone'             => 'Lasallesoftware\Librarybackend\Policies\TelephonePolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Website'               => 'Lasallesoftware\Librarybackend\Policies\WebsitePolicy',

            'Lasallesoftware\Librarybackend\Profiles\Models\Company'               => 'Lasallesoftware\Librarybackend\Policies\CompanyPolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Person'                => 'Lasallesoftware\Librarybackend\Policies\PersonPolicy',

            'Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain'  => 'Lasallesoftware\Librarybackend\Policies\PersonbydomainPolicy',
            'Lasallesoftware\Librarybackend\Authentication\Models\Login'           => 'Lasallesoftware\Librarybackend\Policies\LoginPolicy',

            'Lasallesoftware\Librarybackend\Authentication\Models\Installed_domains_jwt_key'
                    => 'Lasallesoftware\Librarybackend\Policies\Installed_domains_jwt_keyPolicy',
            'Lasallesoftware\Librarybackend\Profiles\Models\Client'                =>  'Lasallesoftware\Librarybackend\Policies\ClientPolicy',   
            
            'Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Models\Phpserverlessproject_sponsors' 
                    => 'Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Policies\Phpserverlessproject_sponsorsPolicy',
        ];
    }
}
