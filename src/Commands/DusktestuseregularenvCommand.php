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
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Commands;

// LaSalle Software class
use Lasallesoftware\Librarybackend\Common\Commands\CommonCommand;


class DusktestuseregularenvCommand extends CommonCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrarybackend:dusktestuseregularenv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The LaSalle Software custom command to use .env.dusk.local for Dusk tests.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        if (strtolower(app('config')->get('app.env')) == "production") {
            $this->info('Cancelled running lslibrarybackend:dusktestuseregularenv because this is a production environment.');
            return;
        }

        
        // does .env.dusk.local.backup exist?
        if (! file_exists(base_path('.env.dusk.local.backup'))) {
            echo "\n\n";
            $this->info('The .env.dusk.local.backup file does not exist. Aborting...');
            echo "\n\n";
            return;            
        }


        // copy .env.dusk.local.backup to .env.dusk.local
        copy(base_path('.env.dusk.local.backup'), base_path('.env.dusk.local'));


        // delete .env.dusk.local.backup
        unlink(base_path('.env.dusk.local.backup'));


        $this->info('Ready to run Dusk tests that use the regular .env.dusk.local.');
    }
}