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

namespace Lasallesoftware\Librarybackend\Dusk;

// LaSalle Software
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid;


trait LaSalleHelpfulStuffForDusk
{
    /**
     * Return the second-to-last UUID model.
     *
     * After running all 243 Dusk tests and 1,931 assertions successfully, I ran "composer update". Then, the errors
     * started! One reason was the new mysterious double insertion into the uuids database table. My Nova forms properly
     * used the first uuid generated as usual, but an unused second record is consistently inserted into uuids as well in
     * all my Nova create and update forms. I did not dig into this because of the need to press ahead and accepting that
     * for now I am going to have a lot of uuid records in the uuids db table that are not used. My guess is that Nova
     * v2.0.8 is double rendering the creation and update forms so that two uuid's are being generated even though only
     * the first one is actually in the form.
     *
     * Of course, the unused uuid is now expected in my tests. Well, the uuid that is actually used is now the
     * second-to-last uuid in the uuids db table. So, in case there is some problem/situation in the future, I am whipping
     * up this function. So I have a nice clean one-liner in my tests, if I have to mess with this again in the future
     * BTW: that one-liner is: $uuid = $this->getSecondLastUuidId();
     *
     *
     * @return mixed
     */
    public function getSecondLastUuidId()
    {
        return Uuid::orderby('id', 'desc')
            ->skip(1)
            ->take(1)
            ->first()
         ;
    }
}
