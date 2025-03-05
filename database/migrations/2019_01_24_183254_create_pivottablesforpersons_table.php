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

class CreatePivottablesforpersonsTable extends BaseMigration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ((!Schema::hasTable('person_address'))  &&
            ($this->doTheMigration(env('APP_ENV'), env('LASALLE_APP_NAME')))) {
        
            Schema::create('person_address', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->integer('person_id')->unsigned()->index();
                $table->foreign('person_id')->references('id')->on('persons');
                $table->integer('address_id')->unsigned()->index();
                $table->foreign('address_id')->references('id')->on('addresses');
            });
        }

        if ((!Schema::hasTable('person_email'))  &&
            ($this->doTheMigration(env('APP_ENV'), env('LASALLE_APP_NAME')))) {
        
            Schema::create('person_email', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->integer('person_id')->unsigned()->index();
                $table->foreign('person_id')->references('id')->on('persons');
                $table->integer('email_id')->unsigned()->index();
                $table->foreign('email_id')->references('id')->on('emails');
            });
        }

        if ((!Schema::hasTable('person_social'))  &&
            ($this->doTheMigration(env('APP_ENV'), env('LASALLE_APP_NAME')))) {
        
            Schema::create('person_social', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->integer('person_id')->unsigned()->index();
                $table->foreign('person_id')->references('id')->on('persons');
                $table->integer('social_id')->unsigned()->index();
                $table->foreign('social_id')->references('id')->on('socials');
            });
        }

        if ((!Schema::hasTable('person_telephone'))  &&
            ($this->doTheMigration(env('APP_ENV'), env('LASALLE_APP_NAME')))) {
        
            Schema::create('person_telephone', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->integer('person_id')->unsigned()->index();
                $table->foreign('person_id')->references('id')->on('persons');
                $table->integer('telephone_id')->unsigned()->index();
                $table->foreign('telephone_id')->references('id')->on('telephones');
            });
        }

        if ((!Schema::hasTable('person_website'))  &&
            ($this->doTheMigration(env('APP_ENV'), env('LASALLE_APP_NAME')))) {
        
            Schema::create('person_website', function (Blueprint $table)
            {
                $table->engine = 'InnoDB';

                $table->increments('id')->unsigned();

                $table->integer('person_id')->unsigned()->index();
                $table->foreign('person_id')->references('id')->on('persons');
                $table->integer('website_id')->unsigned()->index();
                $table->foreign('website_id')->references('id')->on('websites');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('person_address');
        Schema::dropIfExists('person_email');
        Schema::dropIfExists('person_social');
        Schema::dropIfExists('person_telephone');
        Schema::dropIfExists('person_website');
    }
}
