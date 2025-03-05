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
 */

namespace Lasallesoftware\Librarybackend\JWT;

use Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain;
use Lasallesoftware\Librarybackend\Authentication\Models\Installed_domains_jwt_key;

class JWTHelpers
{
    private $installed_domain;

    private $installed_domains_jwt_key;


    public function __construct(Installed_domain $installed_domain, Installed_domains_jwt_key $installed_domains_jwt_key)
    {
        $this->installed_domain          = $installed_domain;
        $this->installed_domains_jwt_key = $installed_domains_jwt_key;
    }


    /**
     * Get the key used to construct the JWT, given the name (ie, the "title" field) of an installed domain.
     * 
     * Returning 0 (zero) means that the given installed domain title was not found.
     *
     * @param  string       $installed_domain_title           The "title" field of an installed domain
     * @return string
     */
    public function getKeyForGivenInstalledDomainTitle(string $installed_domain_title) : string
    {
        // Step 1: get the installed domain's ID
        $installed_domain_id = $this->installed_domain->getIdGivenTitle($installed_domain_title);

        if ($installed_domain_id == 0) return 0;

        // Step 2: get the key, given the installed domain's ID
        $key = $this->installed_domains_jwt_key->getKeyGivenId($installed_domain_id);

        return $key;
    }
}