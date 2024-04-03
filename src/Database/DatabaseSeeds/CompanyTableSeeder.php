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
 * @copyright  (c) 2019-2024 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Database\DatabaseSeeds;

// LaSalle Software
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;

// Laravel Framework
use Illuminate\Support\Facades\DB;

// Third party classes
use Illuminate\Support\Carbon;;
use Faker\Generator as Faker;

class CompanyTableSeeder extends BaseSeeder
{
    /**
     * @var Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator
     */
    protected $uuidGenerator;

    public function __construct(UuidGenerator $uuidGenerator)
    {
        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        $uuid = "created_during_initial_seeding";

        if ($this->doPopulateWithTestData()) {

            DB::table('companies')->insert([
                'id' => 1,
                'name' => 'House of Blues',
                'description' => null,
                'comments' => 'House of Blues IS the ultimate night out. It\'s where great food sets the stage for amazing live concerts. From VIP experiences with the world\'s best artists to our world-famous Gospel Brunch on Sundays, House of Blues is truly where music and food feed the soul.',
                'profile' => null,
                'featured_image' => null,
                'uuid' => $uuid,
                'created_at' => Carbon::now(),
                'created_by' => 1,
                'updated_at' => null,
                'updated_by' => null,
                'locked_at' => null,
                'locked_by' => null,
            ]);
        }
    }
}
