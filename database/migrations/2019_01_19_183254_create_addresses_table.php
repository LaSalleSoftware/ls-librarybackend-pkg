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

class CreateAddressesTable extends BaseMigration
{
    /**
     * The name of the database table
     *
     * @var string
     */
    protected $tableName = "addresses";

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

                $table->integer('lookup_address_type_id')->unsigned();
                $table->foreign('lookup_address_type_id')->references('id')->on('lookup_address_types');

                $table->string('title')->nullable();

                $table->text('address_calculated')->nullable();

                $table->string('address_line_1');
                $table->string('address_line_2')->nullable();
                $table->string('address_line_3')->nullable();
                $table->string('address_line_4')->nullable();
                $table->string('city')->nullable();
                $table->string('province')->nullable();
                $table->string('country')->nullable();
                $table->string('postal_code')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->string('description')->nullable();
                $table->text('comments')->nullable();

                $table->text('profile')->nullable();
                $table->string('featured_image')->nullable();
                $table->string('map_link')->nullable();

                $table->uuid('uuid')->nullable();

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
