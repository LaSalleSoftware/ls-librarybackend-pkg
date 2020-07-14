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
 */

namespace Lasallesoftware\Librarybackend\APP_KEY_rotation;

// LaSalle Software class
use Lasallesoftware\Librarybackend\APP_KEY_rotation\ReEncryptDatabaseFields;
use Lasallesoftware\Librarybackend\Profiles\Models\Website;

// Laravel Facade
use Illuminate\Support\Facades\DB;


class ReEncryptDatabaseFields
{
    public function reEncryptTheWebsiteCommentsField()
    {
       // new ReEncryption object
       $reencryption = new ReEncryption;

       $websites = Website::all();
       foreach ($websites as $website) {
           
           // decrypt
           $decryptedValue = $reencryption->getDecryptedValueUsingPreviousAPP_KEY($website->comments);

           // re-encrypt with new APP_KEY
           $reencryptedValue = $reencryption->getEncryptedValueUsingCurrentAPP_KEY($decryptedValue);

           // update the database record
           DB::table('websites')->where('id', $website->id)->update(['comments' => $reencryptedValue]);
       }
    }
}