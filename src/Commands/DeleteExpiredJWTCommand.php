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

namespace Lasallesoftware\Librarybackend\Commands;

// LaSalle Software
use Lasallesoftware\Librarybackend\Authentication\Models\Json_web_token;
use Lasallesoftware\Librarybackend\Common\Commands\CommonCommand;

/**
 * Class DeleteExpiredJWTCommand
 *
 * Deletes logins database table records that have become inactive.
 *
 * This command is supposed to be run automatically via scheduler.
 *
 * @package Lasallesoftware\Librarybackend\Commands\DeleteExpiredJWTCommand
 */
class DeleteExpiredJWTCommand extends CommonCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrarybackend:deleteexpiredjwt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired jwt records.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $login = new Json_web_token();
        $login->deleteExpired();


        $this->info('Expired jwt records cleared!');
    }
}
