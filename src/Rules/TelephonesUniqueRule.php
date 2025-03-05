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
 *
 */

namespace Lasallesoftware\Librarybackend\Rules;

// Laravel contract
use Illuminate\Contracts\Validation\Rule;

// Laravel facade
use Illuminate\Support\Facades\DB;


class TelephonesUniqueRule implements Rule
{
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        // if the telephone number submitted via the form is, indeed, unique, then this validation passes, so return true
        if ($this->isUnique()) {
            return true;
        }

        // if the telephone number submitted via the form is *not* unique, then this validation fails, so return false
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('lasallesoftwarelibrarybackend::general.rules_telephones_unique_message');
    }

    private function isUnique()
    {
        $request = request();

        // STEP 1: figure out what the "telephone_calculated" field would be using the form's submission data
        $telephone_calculated = $this->calculatedTelephone();

        // STEP 2: see if there is a record in the addresses db table with the "would be" calculated field
        $result = DB::table('telephones')
            ->where('telephone_calculated', '=', $telephone_calculated)
            ->where('id', '<>', $request->resourceId)
            ->first()
        ;

        // if there is a match, then the address submitted via the form is *not* unique, therefore return false
        if ($result) {
            return false;
        }

        // if there is *no* match, then the address submitted via the form is, indeed, unique, so return true
        return true;
        //return false;
    }

    private function calculatedTelephone()
    {
        $request = request();

        // based on Lasallesoftware\Librarybackend\Profiles\Models\Telephone

        $country_code     = $this->formatCountryCode($request->country_code);
        $area_code        = $this->formatAreaCode($request->area_code);
        $telephone_number = $this->formatTelephoneNumber($request->telephone_number);
        $extension        = $this->formatExtension($request->extension);

        return $country_code . $area_code . $telephone_number . $extension;
    }

    private function formatCountryCode($text)
    {
        return trim($text) . ' ';
    }

    private function formatAreaCode($text)
    {
        $text = $this->stripCharactersFromText(trim($text));

        if (strlen($text) == 3) {
            return '(' . $text . ') ';
        }
        return $text . ' ';
    }

    private function formatTelephoneNumber($text)
    {
        $text = $this->stripCharactersFromText(trim($text));

        if (strlen($text) == 7) {
            return substr($text, 0,3) . '-' . substr($text, 3,4);
        }

        return $text;
    }

    private function formatExtension($text)
    {
        $text = trim($text);

        if (strlen($text) > 0) {
            return ' ' . $text;
        }
        return $text;
    }

    private function stripCharactersFromText($text)
    {
        return str_replace(['(', ')', '-', '_', '{', '}', '[', ']'], '', $text);
    }
}
