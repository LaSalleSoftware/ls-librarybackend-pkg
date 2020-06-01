<?php

/**
 * This file is part of the Lasalle Software library package. 
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
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/ls-library-pkg
 * @link       https://github.com/LaSalleSoftware/ls-library-pkg
 * 
 * @see        https://github.com/laravel/ui/blob/2.x/stubs/migrations/2014_10_12_100000_create_password_resets_table.php
 *
 */

// LaSalle Software
use Lasallesoftware\Library\Database\Migrations\BaseMigration;

// Laravel classes
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePasswordResetsTable extends BaseMigration
{
    /**
     * The name of the database table
     *
     * @var string
     */
    protected $tableName = "password_resets";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ((!Schema::hasTable($this->tableName)) &&
            ($this->doTheMigration(env('APP_ENV'), env('LASALLE_APP_NAME')))) {

            Schema::create($this->tableName, function (Blueprint $table) {
                $table->string('email')->index();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }
    }
}