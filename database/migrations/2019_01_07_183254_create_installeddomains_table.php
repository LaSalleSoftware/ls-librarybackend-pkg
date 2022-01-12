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
 * @see       https://lasallesoftware.ca
 * @see       https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see       https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 */

// LaSalle Software
use Lasallesoftware\Librarybackend\Database\Migrations\BaseMigration;

// Laravel classes
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreateLookupdomainsTable
 *
 * Domains administered with this database (on the administrative app)
 */
class CreateInstalleddomainsTable extends BaseMigration
{
    /**
     * The name of the database table
     *
     * @var string
     */
    protected $tableName = "installed_domains";

    /**
     * Run the migrations.
     *
     *  *** THIS IS A LOOKUP TABLE, BUT THIS TABLE IS EASIER TO UNDERSTAND CALLING IT "installed_domains" INSTEAD  ***
     *  *** OF "lookup_domains". AS WELL, IT IS NOT MAINTAINED IN THE ADMIN                                        ***
     *
     * @return void
     */
    public function up()
    {
        if ((!Schema::hasTable($this->tableName)) &&
            ($this->doTheMigration(env('APP_ENV'), env('LASALLE_APP_NAME')))) {

            Schema::create($this->tableName, function (Blueprint $table) {
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->string('title')->unique()->comment('Must be same as the APP_URL environment variable.');
                $table->string('description')->nullable();

                $table->boolean('enabled')->default(true);

                $table->timestamp('created_at')->useCurrent();
                $table->integer('created_by')->unsigned();
                //$table->foreign('created_by')->references('id')->on('persons');

                $table->timestamp('updated_at')->nullable();
                $table->integer('updated_by')->unsigned()->nullable();
                //$table->foreign('updated_by')->references('id')->on('persons');

                $table->timestamp('locked_at')->nullable();
                $table->integer('locked_by')->unsigned()->nullable();
                //$table->foreign('locked_by')->references('id')->on('persons');
            });
        }
    }
}
