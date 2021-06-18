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

namespace Lasallesoftware\Librarybackend\Testing\Concerns\Uuid;

// LaSalle Software
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;

trait InteractsWithUuid
{
    /**
     * The UuidGenerator instance
     *
     * @var Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator
     */
    protected $uuidGenerator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function makeUuidgenerator()
    {
        $this->uuidGenerator = \App::make(UuidGenerator::class);
    }

    /**
     * Create a UUID
     *
     * @return void
     */
    public function createUuid()
    {
        $this->uuidGenerator->createUuid(3, "from InteractsWithUuid::createUuid()");
        return $this;
    }
}
