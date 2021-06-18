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
 * @copyright  (c) 2019-2021 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Helpers;


/**
 * This is the General Helper class.
 *
 * @package Lasallesoftware\Librarybackend\Helpers\GeneralHelpers
 */
trait GeneralHelpers
{
    /**
     * Remove the "http://" or "https://" from the URL
     *
     * @param  string     $url   The URL.
     * @return string
     */
    public function removeHttp(string $url): string
    {
        if (substr($url, 0, 7) == "http://") return substr($url, 7, strlen($url));

        if (substr($url, 0, 8) == "https://") return substr($url, 8, strlen($url));

        return $url;
    }

    /**
     * Is a value in an array?
     *
     * This helper exists as the base comparison in the Whitelist middleware
     * (Lasallesoftware\Librarybackend\Firewall\Http\Middleware\Whitelist). I took this comparison out of the middleware and put
     * it here so I can unit test this comparison easily.
     *
     * Also, I looked through the Laravel array helpers and did not like any of them for this particular situation.
     *
     *
     * @param  mixed  $needle
     * @param  array  $haystack
     * @return bool
     */
    public function isValueInArray($needle, array $haystack) : bool
    {
        return (in_array($needle, $haystack)) ? true : false;
    }

    /**
     * A quick text sanitize.
     * 
     * Added for the contact form package.
     *
     * @param   string    $text   Text to undergo a quick sanitize
     * @return  string
     */
    public function quickSanitize($text) : string
    {
        $santizedText = trim($text);
        $santizedText = strip_tags($santizedText);

        return $santizedText;
    }
}
