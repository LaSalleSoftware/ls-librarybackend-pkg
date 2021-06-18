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
 * @copyright  (c) 2019-2021 The South LaSalle Trading Corporation
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

// LaSalle Software class
use Lasallesoftware\Librarybackend\Common\Commands\CommonCommand;


class DusktestusetwofactorauthenvCommand extends CommonCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrarybackend:dusktestusetwofactorauthenv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The LaSalle Software custom command to use .env.dusk.twofactorauthentication for Dusk tests.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (strtolower(app('config')->get('app.env')) == "production") {
            $this->info('Cancelled running lslibrarybackend:dusktestusetwofactorauthenv because this is a production environment.');
            return;
        }


        // does .env.dusk.local exist?
        if (! file_exists(base_path('.env.dusk.local'))) {
            echo "\n\n";
            $this->info('The .env.dusk.local file does not exist. Aborting...');
            echo "\n\n";
            return;            
        }


        // does .env.dusk.twofactorauthentication exist?
        if (! file_exists(base_path('.env.dusk.twofactorauthentication'))) {
            echo "\n\n";
            $this->info('The .env.dusk.twofactorauthentication file does not exist. Aborting...');
            echo "\n\n";
            return;            
        }


        // copy .env.dusk.local to .env.dusk.local.backup
        copy(base_path('.env.dusk.local'), base_path('.env.dusk.local.backup'));

        // copy .env.dusk.twofactorauthentication to .env.dusk.local
        copy(base_path('.env.dusk.twofactorauthentication'), base_path('.env.dusk.local'));


        $this->info('Ready to run "php artisan dusk --group authenticationTwofactorauthentication".');
    }
}