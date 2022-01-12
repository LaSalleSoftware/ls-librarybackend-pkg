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

namespace Lasallesoftware\Librarybackend\Rules;

// Laravel contract
use Illuminate\Contracts\Validation\Rule;

// Laravel facade
use Illuminate\Support\Facades\DB;


class AddressesUniqueRule implements Rule
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
        // if the address submitted via the form is, indeed, unique, then this validation passes, so return true
        if ($this->isUnique()) {
            return true;
        }

        // if the address submitted via the form is *not* unique, then this validation fails, so return false
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('lasallesoftwarelibrarybackend::general.rules_addresses_unique_message');
    }

    private function isUnique()
    {
        // STEP 1: figure out what the "address_calculated" field would be using the form's submission data

        $request = request();

        // this calculation is recreated from Lasallesoftware\Librarybackend\Profiles\Models\Address

        $address_line_1 = trim($request->address_line_1) . ', ';
        $address_line_2 = $request->address_line_2 == null ? '' : trim($request->address_line_2) . ', ';
        $address_line_3 = $request->address_line_3 == null ? '' : trim($request->address_line_3) . ', ';
        $address_line_4 = $request->address_line_4 == null ? '' : trim($request->address_line_4) . ', ';
        $city           = $request->city           == null ? '' : trim($request->city)           . ', ';
        $province       = $request->province       == null ? '' : trim($request->province)       . ', ';
        $country        = $request->country        == null ? '' : trim($request->country)        . '  ';
        $postal_code    = $request->postal_code    == null ? '' : trim($request->postal_code);

        $address_calculated = $address_line_1 .
            $address_line_2 .
            $address_line_3 .
            $address_line_4 .
            $city .
            $province .
            $country .
            $postal_code
        ;

        // STEP 2: see if there is a record in the addresses db table with the "would be" calculated field
        $result = DB::table('addresses')
            ->where('address_calculated', '=', $address_calculated)
            ->where('id', '<>', $request->resourceId)
            ->first()
        ;

        // if there is a match, then the address submitted via the form is *not* unique, therefore return false
        if ($result) {
            return false;
        }

        // if there is *no* match, then the address submitted via the form is, indeed, unique, so return true
        return true;
    }
}
