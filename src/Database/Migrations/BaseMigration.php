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
 * @copyright  (c) 2019-2025 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Database\Migrations;

// Laravel classes
use Illuminate\Database\Migrations\Migration;

// Laravel facade
use Illuminate\Support\Facades\DB;


class BaseMigration extends Migration
{
    /**
     * Should the migration be done?
     *
     * Only the admin app runs migrations in production
     *
     * @param  string  $app_env           Name of the environment
     * @param  string  $lasalle_app_name  Name of the LaSalle Software app
     * @return bool
     */
    public function doTheMigration($app_env, $lasalle_app_name)
    {
        // only the admin app runs migrations in production
        if (trim(strtolower($app_env)) == "production") {

            if (trim(strtolower($lasalle_app_name)) == "adminbackendapp") {
                return true;
            }

            return false;
        }

        return true;
    }


    /**
     * Get the field type, given the database table's name, and the field's name.
     *
     * @param  string   $tableName               The name of the table in the database.
     * @param  string   $columnName              The name of the field in the table.
     * @return string
     */
    public function getColumnType(string $tableName, string $columnName) : string
    {
        return DB::getSchemaBuilder()->getColumnType($tableName, $columnName);
    }
}
