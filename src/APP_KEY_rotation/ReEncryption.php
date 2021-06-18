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
 */

namespace Lasallesoftware\Librarybackend\APP_KEY_rotation;

// Laravel classes
use Illuminate\Support\Str;
use Illuminate\Encryption\Encrypter;

// Laravel facades
use Illuminate\Support\Facades\Crypt;


class ReEncryption
{
    /**
     * Convert an existing encrypted value to a new encryption using a new key.
     * 
     * Problem: Laravel does not have a built-in way to rotate the encryption key.
     * 
     * Situation: a field in the websites database table is encrypted. Encryption uses the key. How to rotate keys?
     * 
     * Solution: use this class to do the actual re-encryption. 
     * 
     * Just before you generate a new key, paste the existing key in a new environment variable.
     * 
     * The key currently in use is defined in .env as "APP_KEY", and in config/app.php as "key". 
     * 
     * The key that was previously in use is defined in .env as "LASALLE_PREVIOUS_APP_KEY", 
     * and in config/lasallesoftware-librarybackend.php as "lasalle_previous_app_key". 
     * 
     * Re-encryption Process:
     *   * decrypt the existing value with the previous key
     *   * encrypt that decrypted value 
     * 
     *
     * @param  string  $encryptedValue    A value that was encrypted with the *previous* APP_KEY
     * @return string  
     */
    public function reEncryptValue($encryptedValue)
    {
        // *********************************************************************
        // STEP 1: decrypt the given encrypted value using the previous APP_KEY
        // *********************************************************************

        // DECRYPT using the encrypt instance made with the PREVIOUS APP_KEY
        $decryptedValue = $this->getDecryptedValueUsingPreviousAPP_KEY($encryptedValue);  


        // *********************************************************************
        // STEP 2: now that the given encrypted value, that was encrypted with 
        //         the previous APP_KEY, is decrypted, it can be encrypted with
        //         the current APP_KEY
        // *********************************************************************

        // ENCRYPT using the encryption instance made with the CURRENT (aka new) APP_KEY
        return $this->getEncryptedValueUsingCurrentAPP_KEY($decryptedValue);
    }



    /**************************************************************************************** 
     *                          MAIN ENCRYPT/DECRYPT METHODS                                *
     **************************************************************************************** */

    /**
     * Get the DECRYPTED value of a given encrypted value, that was encrypted using the PREVIOUS APP_Key env var
     *
     * @param  string   $encryptedValue     Value that was encrypted using the previous APP_KEY environment variable
     * @return string
     */
    public function getDecryptedValueUsingPreviousAPP_KEY($encryptedValue)
    {
        // get the previous APP_KEY
        $previousAppKey = $this->getPrevious_APP_KEY();

        // verify the previous APP_KEY
        $previousAppKey = (string)$this->verifyAppKey($previousAppKey);

        // create an encrypt instance using the previous APP_KEY
        $previousEncrypter = $this->getEncrypterInstance($previousAppKey, $this->getCipher());
        
        // decrypt using the encrypt instance made with the previous APP_KEY
        return $previousEncrypter->decrypt($encryptedValue, false);  
    }


    /**
     * Get the ENCRYPTED value of a given decrypted value.
     * Encrypted with the PREVIOUS APP_Key environment variable.
     * This method exists for test assertions.
     *
     * @param  string   $decryptedValue     Just a plain, human readable, string.
     * @return string
     */
    public function getEncryptedValueUsingPreviousAPP_KEY($decryptedValue)
    {
        // get the previous APP_KEY
        $previousAppKey = $this->getPrevious_APP_KEY();

        // verify the previous APP_KEY
        $previousAppKey = (string)$this->verifyAppKey($previousAppKey);

        // create an encrypt instance using the previous APP_KEY
        $previousEncrypter = $this->getEncrypterInstance($previousAppKey, $this->getCipher());
        
        // enrypt using the encrypt instance made with the previous APP_KEY
        return $previousEncrypter->encrypt($decryptedValue, false);  
    }


    /**
     * Get the DECRYPTED value of a given encrypted value, that was encrypted using the CURRNET APP_Key env var. 
     * This method exists for test assertions.
     *
     * @param  string   $encryptedValue     Value that was encrypted using the CURRENT APP_KEY environment variable
     * @return string
     */
    public function getDecryptedValueUsingCurrentAPP_KEY($encryptedValue)
    {
        // get the previous APP_KEY
        $currentAppKey = $this->getCurrent_APP_KEY();

        // verify the previous APP_KEY
        $currentAppKey = (string)$this->verifyAppKey($currentAppKey);

        // create an encrypt instance using the previous APP_KEY
        $currentEncrypter = $this->getEncrypterInstance($currentAppKey, $this->getCipher());
        
        // decrypt using the encrypt instance made with the previous APP_KEY
        return $currentEncrypter->decrypt($encryptedValue, false);  
    }
    

    /**
     * Get the ENCRYPTED value of a given encrypted value, that was encrypted using the CURRENT APP_Key env var.
     *
     * @param  string   $decryptedValue     Just a plain, human readable, string.
     * @return string
     */
    public function getEncryptedValueUsingCurrentAPP_KEY($decryptedValue)
    {
        // get the previous APP_KEY
        $currentAppKey = $this->getCurrent_APP_KEY();

        // verify the previous APP_KEY
        $currentAppKey = (string)$this->verifyAppKey($currentAppKey);

        // create an encrypt instance using the previous APP_KEY
        $previousEncrypter = $this->getEncrypterInstance($currentAppKey, $this->getCipher());
        
        // decrypt using the encrypt instance made with the previous APP_KEY
        return $previousEncrypter->encrypt($decryptedValue, false);  
    }



    /**************************************************************************************** 
     *                               SUPPORTING METHODS                                     *
     **************************************************************************************** */
    
    /**
     * Instantiate Encrypter instance based
     * on key and cipher
     * 
     * @param string $key
     * @param string $cipher
     * 
     * @return Encrypter
     */
    public function getEncrypterInstance($key, $cipher)
    {
        return new Encrypter($key, $cipher);   
    }

    /**
     * Verify the given app key
     * 
     * @param string $key
     * 
     * @return string
     */
    public function verifyAppKey($key)
    {
        if (Str::startsWith($key, 'base64:')) {
            return base64_decode(substr($key, 7));
        }

        return $key;
    }

    /**
     * Get the cipher
     *
     * @return string
     */
    public function getCipher()
    {
        return config('app.cipher');
    }

    /**
     * Get the previous APP_KEY that was used before the current APP_KEY replaced it.
     *
     * @return string
     */
    public function getPrevious_APP_KEY()
    {
       return config('lasallesoftware-librarybackend.lasalle_previous_app_key');
    }

    /**
     * Get the current APP_KEY environment variable
     *
     * @return void
     */
    public function getCurrent_APP_KEY()
    {
        return config('app.key');
    }
}