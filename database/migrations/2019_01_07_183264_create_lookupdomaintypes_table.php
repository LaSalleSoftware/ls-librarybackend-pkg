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
 */

// LaSalle Software
use Lasallesoftware\Library\Database\Migrations\BaseMigration;

// Laravel classes
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

class CreateLookupdomaintypesTable extends BaseMigration
{
    /**
     * The name of the database table
     *
     * @var string
     */
    protected $tableName = "lookup_domain_types";

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
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->string('title')->unique();
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
