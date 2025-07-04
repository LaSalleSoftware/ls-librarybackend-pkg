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
 * Class CreateJsonwebtokensTable
 *
 * Incoming JWT from installed_domains. A JWT should be used just once. This table stores the incoming JWT's so that
 * a check can be done to see if that incoming JWT was already used. If yes, the request is rejected. There are two
 * assumptions here: i) that it is basically impossible to generated the same JWT twice; and, ii) since a legit JWT
 * is unique, therefore a duplicate JWT is deemed not legit.
 *
 */
class CreateJsonwebtokensTable extends BaseMigration
{
    /**
     * The name of the database table
     *
     * @var string
     */
    protected $tableName = "json_web_tokens";

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

                $table->text('jwt');

                // https://laravel.io/forum/02-04-2016-is-there-a-way-to-specify-the-length-of-an-index
                // $table->index([DB::raw('jwt(100)')]);
                $table->rawIndex('jwt(100)', 'jwt_index');

                $table->timestamp('created_at')->useCurrent();
            });
        }
    }
}
