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
 * @copyright  (c) 2019-2020 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 *
 * @see        https://lasallesoftware.ca
 * @see       https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see       https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 */

namespace Lasallesoftware\Librarybackend\Commands;

// LaSalle Software class
use Illuminate\Console\ConfirmableTrait;
// Laravel classes
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Lasallesoftware\Librarybackend\Common\Commands\CommonCommand;
// Third party classes
use Symfony\Component\Console\Input\InputOption;

// Third party classes

/**
 * Class LasalleinstallfrontendappCommand.
 */
class LasalleinstallfrontendappCommand extends CommonCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrarybackend:lasalleinstallfrontendapp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LaSalle Software basic front-end app installation.';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $files;

    /**
     * The base directory.
     *
     * @var string
     */
    protected $baseDir;

    /**
     * Create a new config command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();

        $this->files = $files;
        $this->baseDir = __DIR__.'/tmp';
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // -------------------------------------------------------------------------------------------------------------
        // START: IS THIS THE FRONT-END APP?
        if ('basicfrontendapp' != env('LASALLE_APP_NAME')) {
            $this->line("This installation artisan command is specifically for my LaSalle Software's front-end application.");
            $this->line('You are installing my '.mb_strtoupper(env('LASALLE_APP_NAME')).' LaSalle Software application.');
            $this->line('So I am exiting you out of this artisan command.');
            $this->line('exiting...');
            $this->line('You are now exited from lslibrarybackend:lasalleinstallfrontendapp.');
            echo "\n\n";

            return;
        }
        // END: IS THIS THE FRONT-END APP?
        // -------------------------------------------------------------------------------------------------------------

        // -------------------------------------------------------------------------------------------------------------
        // START: INTRO
        $this->line('--------------------------------------------------------------------------------');
        $this->line('                       Welcome to my LaSalle Software\'s');
        $this->line('             Basic Front-end App\' Installation Artisan Command!');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  You are installing the '.mb_strtoupper(env('LASALLE_APP_NAME')).' LaSalle Software Application.');
        echo "\n";
        $this->line('  You are installing to your '.$this->getLaravel()->environment().' environment.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  Read my INSTALLATION.md *BEFORE* running this command.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line(' This installation adds records into the database.');
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  Have you run my environment variable installation artisan command? No?');
        $this->line('  Well, then! Type "oops" to exit this artisan command, and then run');
        $this->line('  "lslibrarybackend:lasalleinstallenv". Then, re-run this artisan command.');
        $this->line('--------------------------------------------------------------------------------');
        echo "\n";
        $this->line('  Thank you for installing my LaSalle Software!');
        $this->line('  --Bob Bloom');
        $this->line('--------------------------------------------------------------------------------');
        // END: INTRO
        // -------------------------------------------------------------------------------------------------------------

        // -------------------------------------------------------------------------------------------------------------
        // START: DID YOU RUN lslibrarybackend:LASALLEINSTALLENV ALREADY?
        echo "\n\n";
        $this->alert('Did you already run lslibrarybackend:lasalleinstallenv? You must run it first!');
        $runConfirmation = $this->ask('<fg=yellow;bg=red>(type the word "oops" to exit this artisan command, or just press enter to continue...)</>');
        if ($runConfirmation == strtolower('oops')) {
            $this->line('<fg=red;bg=yellow>Good stuff! Please run "php artisan lslibrarybackend:lasalleinstallenv" and then re-run this artisan command.</>');
            $this->echoOutro();

            return;
        }
        $this->comment('ok... Let us get this show on the road...');
        // END: DID YOU RUN lslibrarybackend:LASALLEINSTALLENV ALREADY?
        // -------------------------------------------------------------------------------------------------------------

        // -------------------------------------------------------------------------------------------------------------
        // START: CREATE DATABASE RECORDS

        // create record in the "installed_domains" database table
        $this->comment(' ');
        $this->comment('Now adding a database record for this domain (installed_domains)...');
        $this->createInstalleddomainsRecord();
        $installedDomainsId = $this->getInstalleddomainsId(env('LASALLE_APP_DOMAIN_NAME'));
        $this->comment('Now adding a database record for this domain type (installeddomain_domaintype)...');
        $this->createInstalleddomaindomaintypeRecord($installedDomainsId);

        //create record in the "installed_domains_jwt_keys" database table
        $this->comment('Now adding a database record for the jwt key (installed_domains_jwt_keys)...');
        $this->createInstalleddomainsjwtkeysRecord($installedDomainsId, env('LASALLE_JWT_KEY'));
        // END: CREATE DATABASE RECORDS
        // -------------------------------------------------------------------------------------------------------------

        // -------------------------------------------------------------------------------------------------------------
        // START: FINISHED!
        $this->echoOutro();
        // END: FINISHED!
        // -------------------------------------------------------------------------------------------------------------
    }

    /**
     * Echo the final message.
     *
     * return void
     */
    protected function echoOutro()
    {
        echo "\n\n";
        $this->info('====================================================================');
        $this->info('         ** lslibrarybackend:lasalleinstallfrontendapp has finished **');
        $this->info('====================================================================');
        echo "\n\n";
    }

    /**
     * Insert this front-end app into the installed_domains table.
     */
    protected function createInstalleddomainsRecord()
    {
        DB::table('installed_domains')->insert([
            'title' => env('LASALLE_APP_DOMAIN_NAME'),
            'description' => env('LASALLE_APP_DOMAIN_NAME'),
            'enabled' => 1,
            'created_at' => Carbon::now(),
            'created_by' => 1,
            'updated_at' => null,
            'updated_by' => null,
            'locked_at' => null,
            'locked_by' => null,
        ]);
    }

    /**
     * What is the ID of the given installed_domain table?
     *
     * @param string $title Title field of the installed_domain table
     *
     * @return int
     */
    protected function getInstalleddomainsId($title)
    {
        $record = DB::table('installed_domains')->where('title', $title)->first();

        return $record->id;
    }

    /**
     * Insert into installeddomain_domaintype for this new front-end domain.
     *
     * @param int $installedDomainId The ID of this new front-end domain
     */
    protected function createInstalleddomaindomaintypeRecord($installedDomainId)
    {
        DB::table('installeddomain_domaintype')->insert([
            'installed_domain_id' => $installedDomainId,
            'lookup_domain_type_id' => 2,
        ]);
    }

    /**
     * Insert the first jwt key into the database for this front-end app.
     *
     * Using the DB facade for expediency.
     *
     * @param int    $installedDomainId The installed domain's ID
     * @param string $key               The key
     */
    protected function createInstalleddomainsjwtkeysRecord($installedDomainId, $key)
    {
        DB::table('installed_domains_jwt_keys')->insert([
            'installed_domain_id' => $installedDomainId,
            'key' => $key,
            'enabled' => 1,
            'created_at' => Carbon::now(),
            'created_by' => 1,
            'updated_at' => null,
            'updated_by' => null,
            'locked_at' => null,
            'locked_by' => null,
        ]);
    }

    /**
     * Get the console command options.
     *
     * PASTED THIS FROM THE CUSTOMDROP COMMAND! (not a refactor candidate, need this method here!)
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
