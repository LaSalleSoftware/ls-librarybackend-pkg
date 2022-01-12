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

class CreatePersonsTable extends BaseMigration
{
    /**
     * The name of the database table
     *
     * @var string
     */
    protected $tableName = "persons";

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

                $table->increments('id');

                $table->text('name_calculated')->nullable();

                $table->string('salutation')->nullable();
                $table->string('first_name')->nullable();
                $table->string('middle_name')->nullable();
                $table->string('surname');
                $table->string('position')->nullable();
                $table->string('description')->nullable();
                $table->text('comments')->nullable();

                $table->text('profile')->nullable();
                $table->string('featured_image')->nullable();

                $table->date('birthday')->nullable();
                $table->date('anniversary')->nullable();
                $table->date('deceased')->nullable();
                $table->string('comments_date')->nullable();

                $table->uuid('uuid')->nullable();

                $table->timestamp('created_at')->useCurrent();
                $table->integer('created_by')->unsigned()->default(1);
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
