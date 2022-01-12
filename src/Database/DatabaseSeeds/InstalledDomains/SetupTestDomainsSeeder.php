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

namespace Lasallesoftware\Librarybackend\Database\DatabaseSeeds\InstalledDomains;

// LaSalle Software
use Lasallesoftware\Librarybackend\Database\DatabaseSeeds\BaseSeeder;

// Laravel Framework
use Illuminate\Support\Facades\DB;

// Third party classes
use Illuminate\Support\Carbon;

class SetupTestDomainsSeeder extends BaseSeeder
{
    protected $now;


    public function __construct()
    {
        $this->now  = Carbon::now();
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if ($this->doPopulateWithTestData()) {

            $this->setUpSimulatedFrontendDomain1();
            $this->setUpSimulatedFrontendDomain2();
            $this->setUpSimulatedFrontendDomain3();
        }
    }

    /**
     * Create a pretend front-end domain for testing
     *
     * @return void
     */
    private function setUpSimulatedFrontendDomain1()
    {
        DB::table('installed_domains')->insert([
            'title'       => 'pretendfrontend.com',
            'description' => 'PretendFrontEnd.com for testing',
            'enabled'     => '1',
            'created_at'  => $this->now,
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        $installedDomain = $this->getLastInstalledDomain();

        DB::table('installeddomain_domaintype')->insert([
            'installed_domain_id'   => $installedDomain->id,
            'lookup_domain_type_id' => '2',
        ]);
    }

    /**
     * Create a pretend front-end domain for testing
     *
     * @return void
     */
    private function setUpSimulatedFrontendDomain2()
    {
        DB::table('installed_domains')->insert([
            'title'       => 'anotherpretendfrontend.com',
            'description' => 'AnotherPretendFrontEnd.com for testing',
            'enabled'     => '1',
            'created_at'  => $this->now,
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        $installedDomain = $this->getLastInstalledDomain();

        DB::table('installeddomain_domaintype')->insert([
            'installed_domain_id'   => $installedDomain->id,
            'lookup_domain_type_id' => '2',
        ]);
    }

    /**
     * Create hackintosh.lsv2-basicfrontend-app.com for testing
     *
     * @return void
     */
    private function setUpSimulatedFrontendDomain3()
    {
        DB::table('installed_domains')->insert([
            'title'       => 'hackintosh.lsv2-basicfrontend-app.com',
            'description' => 'hackintosh.lsv2-basicfrontend-app.com',
            'enabled'     => '1',
            'created_at'  => $this->now,
            'created_by'  => 1,
            'updated_at'  => null,
            'updated_by'  => null,
            'locked_at'   => null,
            'locked_by'   => null,
        ]);

        $installedDomain = $this->getLastInstalledDomain();

        DB::table('installeddomain_domaintype')->insert([
            'installed_domain_id' => $installedDomain->id,
            'lookup_domain_type_id' => '2',
        ]);
    }


    private function getLastInstalledDomain()
    {
        return \Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain::orderBy('id', 'desc')->first();
    }
}
