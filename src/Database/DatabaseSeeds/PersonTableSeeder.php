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

namespace Lasallesoftware\Librarybackend\Database\DatabaseSeeds;

// LaSalle Software
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;

// Laravel Framework
use Illuminate\Support\Facades\DB;

// Third party classes
use Illuminate\Support\Carbon;;
use Faker\Generator as Faker;

class PersonTableSeeder extends BaseSeeder
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

        DB::table('persons')->insert([
            'id'             => 1,
            'name_calculated' => 'system -- do not modify',
            'salutation'     => null,
            'first_name'     => 'system',
            'middle_name'    => 'system',
            'surname'        => 'system',
            'position'       => null,
            'description'    => 'system -- do not delete!',
            'comments'       => 'system -- do not delete!',
            'profile'        => 'system -- do not delete!',
            'featured_image' => null,
            'birthday'       => null,
            'anniversary'    => null,
            'deceased'       => null,
            'comments_date'  => null,
            'uuid'           => $uuid,
            'created_at'     => Carbon::now(),
            'created_by'     => 1,
            'updated_at'     => null,
            'updated_by'     => null,
            'locked_at'      => null,
            'locked_by'      => null,

        ]);


        if ($this->doPopulateWithTestData()) {

            DB::table('persons')->insert([
                'id'             => 2,
                'name_calculated' => 'Bob Bloom',
                'salutation'     => 'Mr.',
                'first_name'     => 'Bob',
                'middle_name'    => '',
                'surname'        => 'Bloom',
                'position'       => 'Developer',
                'description'    => 'This person must be an "owner".',
                'comments'       => null,
                'profile'        => null,
                'featured_image' => null,
                'birthday'       => null,
                'anniversary'    => null,
                'deceased'       => null,
                'comments_date'  => null,
                'uuid'           => $uuid,
                'created_at'     => Carbon::now(),
                'created_by'     => 1,
                'updated_at'     => null,
                'updated_by'     => null,
                'locked_at'      => null,
                'locked_by'      => null,
            ]);


            //*** NOVA NOTE!! ***//
            //
            // Anyone can log into Nova in "local".
            // However, only those emails listed in the NovaServiceProvider.php can log into Nova
            // in non-local environments (eg, testing).
            //
            // https://nova.laravel.com/docs/2.0/installation.html#authorizing-nova


            DB::table('persons')->insert([
                'id'             => 3,
                'name_calculated' => 'Blues Boy King',
                'salutation'     => 'Mr.',
                'first_name'     => 'Blues Boy',
                'middle_name'    => '',
                'surname'        => 'King',
                'position'       => null,
                'description'    => null,
                'comments'       => null,
                'profile'        => null,
                'featured_image' => null,
                'birthday'       => null,
                'anniversary'    => null,
                'deceased'       => null,
                'comments_date'  => null,
                'uuid'           => $uuid,
                'created_at'     => Carbon::now(),
                'created_by'     => 1,
                'updated_at'     => null,
                'updated_by'     => null,
                'locked_at'      => null,
                'locked_by'      => null,
            ]);

            DB::table('persons')->insert([
                'id'             => 4,
                'name_calculated' => 'Stevie Ray Vaughan',
                'salutation'     => 'Mr.',
                'first_name'     => 'Stevie',
                'middle_name'    => 'Ray',
                'surname'        => 'Vaughan',
                'position'       => null,
                'description'    => null,
                'comments'       => null,
                'profile'        => null,
                'featured_image' => null,
                'birthday'       => null,
                'anniversary'    => null,
                'deceased'       => null,
                'comments_date'  => null,
                'uuid'           => $uuid,
                'created_at'     => Carbon::now(),
                'created_by'     => 1,
                'updated_at'     => null,
                'updated_by'     => null,
                'locked_at'      => null,
                'locked_by'      => null,
            ]);


            if ($this->doPopulateWithTestData()) {
                factory(\Lasallesoftware\Librarybackend\Profiles\Models\Person::class, 300)->create();
            }
        }
    }
}
