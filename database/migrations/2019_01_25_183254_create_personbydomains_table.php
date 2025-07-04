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
 * Class CreatePersonbydomainsTable
 *
 * This table authenticates logins.
 *
 * Laravel ships with the "users" table. This table is used to authenticate when people login.
 * I need an authentication table that associates someone with the domain that they can log in from.
 *
 * I decided to not use pivot tables for this table, instead de-normalizing it by including
 * fields directly in this table. The reasons are:
 *
 *  * real humans are going to look at the records a lot
 *  * this is a multiple use table:
 *    ** associate one person with multiple domains
 *    ** use this table to authenticate (login) people
 *  * mimic the "users" table that ships with Laravel
 *  * very useful to have actual names and stuff in the table when looking at table in CLI and
 *    third party database management software
 *  *
 * at this table and "just know by looking" who is associated with what domain is pretty
 */
class CreatePersonbydomainsTable extends BaseMigration
{
    /**
     * The name of the database table
     *
     * @var string
     */
    protected $tableName = "personbydomains";

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

                $table->integer('person_id')->unsigned();
                $table->foreign('person_id')->references('id')->on('persons');

                // Needed for Laravel's default AUTH
                $table->string('name');
                
                $table->text('name_calculated')->nullable();
                $table->string('person_first_name');
                //$table->foreign('person_first_name')->references('first_name')->on('persons'); --> not an index so can't FK
                $table->string('person_surname');
                //$table->foreign('person_surname')->references('surname')->on('persons'); --> not an index so can't FK

                $table->string('email');
                $table->foreign('email')->references('email_address')->on('emails');

                $table->timestamp('email_verified_at')->nullable();
                $table->string('password');
                $table->rememberToken();

                $this->createForeignIdFieldAndReference('installed_domains', 'id', 'installed_domain_id', $table, false);

                $table->string('installed_domain_title');

                $table->boolean('banned_enabled')->default(false);
                $table->timestamp('banned_at')->nullable();
                $table->string('banned_comments')->nullable();

                $table->uuid('uuid')->nullable();

                $table->timestamp('created_at')->useCurrent();
                $table->integer('created_by')->unsigned();
                //$table->foreign('created_by')->references('id')->on('persons');

                $table->timestamp('updated_at')->nullable();
                $table->integer('updated_by')->unsigned()->nullable();
                //$table->foreign('updated_by')->references('id')->on('persons');

                $table->timestamp('locked_at')->nullable();
                $table->integer('locked_by')->nullable()->unsigned();
                //$table->foreign('locked_by')->references('id')->on('persons');

                $table->unique(['email']);
                $table->unique(['person_id', 'email', 'installed_domain_id']);
            });
        }
    }
}