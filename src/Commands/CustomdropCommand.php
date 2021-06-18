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

// Laravel classes
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;

/**
 * Class CustomdropCommand
 *
 * Drops all the database tables.
 *
 * Adapted from
 * https://github.com/laravel/framework/blob/5.7/src/Illuminate/Database/Console/Migrations/FreshCommand.php
 *
 * @package Lasallesoftware\Librarybackend\Commands\CustomdropCommand
 */
class CustomdropCommand extends CommonCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrarybackend:customdrop';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'The LaSalle Software custom command that drops all the database tables.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        /*
        echo "\n\n====================================================================\n";
        echo "              ** start lslibrarybackend:customdrop **";
        echo "\n====================================================================\n";
        */

        if (strtolower(app('config')->get('app.env')) == "production") {
            $this->info('Cancelled running lslibrarybackend:customdrop because this is a production environment.');
            return;
        }

        if (! $this->confirm("Do you really want to drop your database?")) {
            $this->info('Did not drop database tables because you did not confirm.');
            return;
        }

        $database = $this->input->getOption('database');

        //$this->dropAllViews($database);
        //$this->info('Dropped all views successfully.');

        $this->dropAllTables($database);

        $this->info('  ...just successfully dropped all your database tables...');

        echo "\n  -------------------------------\n";
        if ($this->confirm("  *** do you want to run php artisan migrate? ***")) {
            $this->call('migrate');
            echo "\n";
            $this->info('  ...just ran php artisan migrate...');
        } else {
            $this->info(' (did *not* run php artisan migrate)');
        }

        echo "\n  -------------------------------\n";
        if ($this->confirm("  *** do you want to run php artisan lslibrarybackend:customseed?***")) {
            $this->call('lslibrarybackend:customseed');
            echo "\n";
            $this->info('  ...just ran php artisan lslibrarybackend:customseed...');
        } else {
            $this->info(' (did *not* run php artisan lslibrarybackend:customseed)');
        }

        //echo "\n\n";

        echo "\n\n====================================================================\n";
        echo "              ** lslibrarybackend:customdrop is finished **";
        echo "\n====================================================================\n\n";
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use'],
        ];
    }
}
