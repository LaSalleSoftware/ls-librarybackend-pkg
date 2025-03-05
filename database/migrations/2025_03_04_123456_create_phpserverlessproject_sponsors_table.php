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
 * @copyright  (c) 2019-2024 The South LaSalle Trading Corporation
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



class CreatePhpserverlessprojectSponsorsTable extends BaseMigration
{
    /**
     * The name of the database table
     *
     * @var string
     */
    protected $tableName = "phpserverlessproject_sponsors";

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        if ((!Schema::hasTable($this->tableName)) &&
            ($this->doTheMigration(env('APP_ENV'), env('LASALLE_APP_NAME')))) {


            /* =====================================================================================
               The way I have sponsors set up is ok. But, I need something specifically for my 
               PHP Serverless Project sponsors.

               This table is just for my PHP Serverless Project sponsors.

               This table is stand-alone. It is not referencing/relating to any of my other tables.
               
               https://laravel.com/docs/12.x/migrations#column-modifiers for "->comment('text')"
               ===================================================================================== */

            Schema::create($this->tableName, function (Blueprint $table) {
                $table->engine = 'InnoDB';

                // https://laravel.com/docs/12.x/migrations#column-method-increments
                $table->increments('id')->unsigned();

                $table->string('full_name')->unique()->comment('NOT related to my profile tables');

                $table->string('image_url')->nullable();
                $table->string('image_thumbnail_url')->nullable();

                $table->text('profile');

                $table->string('email_address')->comment('NOT related to my email table'); // **NOT** related to the email table

                $table->boolean('umbrella_sponsor')->default(true);
                $table->boolean('basecamp_sponsor')->default(false);
                $table->boolean('restream_sponsor')->default(false);


                $table->text('internal_comment')->nullable()->comment('My own comments. Not for publishing');        
 

                $table->boolean('enabled')->default(true);


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
