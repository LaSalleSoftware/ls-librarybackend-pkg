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
 *
 */

namespace Lasallesoftware\Librarybackend\Database\DatabaseSeeds;

// LaSalle Software
use Lasallesoftware\Librarybackend\Profiles\Models\Email;
use Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain;
use Lasallesoftware\Librarybackend\Profiles\Models\Person;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;

// Laravel Framework
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BaseSeeder extends Seeder
{
    /**
     * Yes or no: populate the database with test data?
     *
     * @return bool
     */
    public function doPopulateWithTestData()
    {
        // do not want test data in the production database, regardless of the override
        if (app('config')->get('app.env') == "production") {
            return false;
        }

        // well, if it's the testing environment, then of course we want the test data in the database
        if (app('config')->get('app.env') == "testing") {
            return true;
        }

        // if there's an override, and we're not in the production environment, then we want the test data in the db
        if (config('lasallesoftware-librarybackend.seed_database_with_test_data', false)) {
            return true;
        }

        return false;
    }

    /**
     * Get the next ID of the installeddomain_domaintype db table
     *
     * @return int|mixed
     */
    public function getNewInstalleddomain_domaintypeId()
    {
        $id = DB::table('installeddomain_domaintype')
            ->orderBy('id', 'desc')
            ->take(1)
            ->value('id')
        ;

        return $id ? $id + 1 : 1;
    }

    /**
     * Get the ID of this app in the installed_domains db table
     *
     * @return mixed
     */
    public function getInstalledDomainId()
    {
        $lasalle_app_domain_name= app('config')->get('lasallesoftware-librarybackend.lasalle_app_domain_name');

        return DB::table('installed_domains')
            ->where('title', $lasalle_app_domain_name)
            ->value('id')
         ;
    }

    /**
     * Get the most recent inserted record in the persons db table
     *
     * @return mixed
     */
    public function getLatestPerson()
    {
        return Person::orderBy('id', 'desc')->first();
    }

    /**
     * Get the record of the specified id in the persons db table.
     *
     * @param  $id
     * @return mixed
     */
    public function getPerson($id)
    {
        return Person::find($id);
    }

    /**
     * Get the most recent inserted record in the emails db table
     *
     * @return mixed
     */
    public function getLatestEmail()
    {
        return Email::orderBy('id', 'desc')->first();
    }

    /**
     * Get the most recent inserted record in the personbydomains db table
     *
     * @return mixed
     */
    public function getLatestPersonbydomain()
    {
        return Personbydomain::orderBy('id', 'desc')->first();
    }

    /**
     * get the domain's title for the specified ID in the installed_domains db table
     *
     * @param  $id
     * @return mixed
     */
    public function getDomainTitle($id)
    {
        $domain = Installed_domain::find($id);
        return $domain['title'];
    }
}
