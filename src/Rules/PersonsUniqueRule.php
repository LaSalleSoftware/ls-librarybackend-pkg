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

namespace Lasallesoftware\Librarybackend\Rules;

// Laravel contract
use Illuminate\Contracts\Validation\Rule;

// Laravel facade
use Illuminate\Support\Facades\DB;


class PersonsUniqueRule implements Rule
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
        return __('lasallesoftwarelibrarybackend::general.rules_persons_unique_message');
    }

    private function isUnique()
    {
        $request = request();

        // STEP 1: figure out what the "address_calculated" field would be using the form's submission data
        $name_calculated = $this->calculatedName();

        // STEP 2: see if there is a record in the addresses db table with the "would be" calculated field
        $result = DB::table('persons')
            ->where('name_calculated', '=', $name_calculated)
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

    private function calculatedName()
    {
        $request = request();

        // this calculation is recreated from Lasallesoftware\Librarybackend\Profiles\Models\Person

        $first_name  = $this->washName($request->first_name) . " ";
        $middle_name = $request->middle_name == null ? '' : $this->washName($request->middle_name) . " ";
        $surname     = $this->washName($request->surname);

        return $first_name . $middle_name . $surname;
    }

    private function washName($name)
    {
        return trim(strtolower($name));
    }
}
