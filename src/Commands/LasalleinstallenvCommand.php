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
use Illuminate\Support\Str;
use Lasallesoftware\Librarybackend\Common\Commands\CommonCommand;

/**
 * Class LasalleinstallpartoneCommand.
 *
 * First of two artisan command installation scripts
 */
class LasalleinstallenvCommand extends CommonCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrarybackend:lasalleinstallenv';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LaSalle Software environment variables installation.';

    /**
     * Create a new config command instance.
     */
    public function __construct(Filesystem $files)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // -------------------------------------------------------------------------------------------------------------
        // START: INTRO
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('========================================================================================');
        $this->line('                       Welcome to my LaSalle Software\'s');
        $this->line('               Environment Variables Installation Artisan Command!');
        $this->line('========================================================================================');
        $this->line('  You are installing LaSalle Software\'s '.mb_strtoupper(env('LASALLE_APP_NAME')).' application.');
        $this->line('========================================================================================');
        $this->line('  You are installing to your '.$this->getLaravel()->environment().' environment.');
        $this->line('========================================================================================');
        $this->line('  This command sets some variables in your .env file.');
        $this->line('========================================================================================');
        $this->line('  Read https://lasallesoftware.ca/docs/v2/gettingstarted_installation_backendapp ');
        $this->line('  *BEFORE* running this command.');
        $this->line('========================================================================================');
        $this->line('  Thank you for installing my LaSalle Software!');
        $this->line('  --Bob Bloom');
        $this->line('========================================================================================');
        // -------------------------------------------------------------------------------------------------------------
        // END: INTRO
        // -------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // START: ARE YOU SURE YOU WANT TO RUN THIS COMMAND?
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n\n";
        $this->line('--------------------------------------------------------------------------------');
        $this->line('  Please confirm that you really want to run this installation artisan command');
        $this->line('--------------------------------------------------------------------------------');
        $this->alert('Are you sure that you want to run this command?');
        $runConfirmation = $this->ask('<fg=yellow>(type the word "yes" to continue)</>');
        if ($runConfirmation != strtolower('yes')) {
            $this->line('<fg=red;bg=yellow>You did *not* type "yes", so I am NOT going to continue running this command. Bye!</>');
            $this->echoOutro();

            return;
        }
        $this->comment('ok... you said that you want to continue running this command. Let us continue then...');
        // -------------------------------------------------------------------------------------------------------------
        // END: ARE YOU SURE YOU WANT TO RUN THIS COMMAND?
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: CREATE THE .ENV FILE WHEN IT DOES NOT EXIST
        // -------------------------------------------------------------------------------------------------------------
        if (!file_exists($this->laravel->environmentFilePath())) {

            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line('  .ENV file creation:');
            $this->line('-----------------------------------------------------------------------');
            $this->comment("Your environment file does not exist, so let's create it...");
            $this->makeEnv();
            $this->info("Your .env file now exists!");
        } 
        // -------------------------------------------------------------------------------------------------------------
        // END: CREATE THE .ENV FILE WHEN IT DOES NOT EXIST
        // -------------------------------------------------------------------------------------------------------------




        // -------------------------------------------------------------------------------------------------------------
        // START: APP_KEY
        // -------------------------------------------------------------------------------------------------------------
        if (env('APP_KEY') == '') {
            $this->line('-----------------------------------------------------------------------');
            $this->line('  APP_KEY environment variable:');
            $this->line('-----------------------------------------------------------------------');
            $this->comment("Your APP_KEY environment variable has not been set.");
            $this->comment("So, let's set your APP_KEY environment variable now.");
            $this->comment('Setting your APP_KEY...');
            $this->call('key:generate');
            $this->info("Your env's APP_KEY is now set!");
        }
        // -------------------------------------------------------------------------------------------------------------
        // END: APP_KEY
        // -------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE APP_NAME PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your APP_NAME environment variable');
        $this->line('-----------------------------------------------------------------------');
        $this->comment("What is your application's name (APP_NAME)?");
        $this->comment('An example is: LaSalle Software Administration App');
        $appName = $this->ask('(I do *not* check for syntax or for anything, so please type c-a-r-e-f-u-l-l-y!)');
        $this->comment('You typed "'.$appName.'".');
        $this->comment('Attempting to set APP_NAME in your .env to "'.$appName.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyAppName', $appName, true);
        $this->info("Attempt to modify your env's APP_NAME to ".$appName.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE APP_NAME PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE APP_URL PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  APP_URL environment variable:');
        $this->line('-----------------------------------------------------------------------');
        $this->comment("What is your application's full URL (APP_URL)?");
        $this->comment('  * MUST start with "http://" or "https://"');
        $this->comment('  * NO trailing slash!');
        $this->comment('  * example: https://lasallesoftware.ca');
        $this->comment('  * example: https://lasallesoftware.ca:8888');
        $appURL = $this->ask('(I do *not* check for syntax or for anything, so please type c-a-r-e-f-u-l-l-y!)');
        $this->comment('Attempting to set APP_URL in your .env to "'.$appURL.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyAppURL', $appURL, false);
        $this->info("Attempt to modify your env's APP_URL to ".$appURL.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE APP_URL PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE SET LASALLE_APP_DOMAIN_NAME PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your LASALLE_APP_DOMAIN_NAME environment variable');
        $this->line('-----------------------------------------------------------------------');
        $this->comment('This is done automatically based on your APP_URL.');
        $lasalleAppDomainName = $this->getLasalleAppDomainName($appURL);
        $this->comment('Attempting to set LASALLE_APP_DOMAIN_NAME in your .env to "'.$lasalleAppDomainName.'""...');
        $this->writeEnvironmentFileWithNewKey('DummyLasalleAppDomainName', $lasalleAppDomainName, false);
        $this->info("Attempt to modify your env's LASALLE_APP_DOMAIN_NAME to ".$lasalleAppDomainName.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE SET LASALLE_APP_DOMAIN_NAME PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE SET NOVA_DOMAIN_NAME PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your NOVA_DOMAIN_NAME environment variable');
        $this->line('-----------------------------------------------------------------------');
        $this->comment('This is done automatically based on your LASALLE_APP_DOMAIN_NAME.');
        $lasalleAppDomainName = $this->getLasalleAppDomainName($appURL);
        $this->comment('Attempting to set LASALLE_APP_DOMAIN_NAME in your .env to "'.$lasalleAppDomainName.'""...');
        $this->writeEnvironmentFileWithNewKey('DummyNovaDomainName', $lasalleAppDomainName, false);
        $this->info("Attempt to modify your env's NOVA_DOMAIN_NAME to ".$lasalleAppDomainName.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE SET NOVA_DOMAIN_NAME PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------


  
        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE LASALLE_JWT_KEY PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your LASALLE_JWT_KEY environment variable');
        $this->line('-----------------------------------------------------------------------');
        $lasalleJwtKey = Str::random(64);
        $this->comment('Attempting to set LASALLE_JWT_KEY in your .env to "'.$lasalleJwtKey.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyJwtKey', $lasalleJwtKey, false);
        $this->info("Attempt to modify your env's LASALLE_JWT_KEY to ".$lasalleJwtKey.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE LASALLE_JWT_KEY PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE LASALLE_JWT_AUD_CLAIM PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your LASALLE_JWT_AUD_CLAIM environment variable');
        $this->line('-----------------------------------------------------------------------');
        $this->comment(' ');
        $this->comment('This front-end app needs to know what administrative back-end app it belongs to.');
        $this->comment('So, you need to specify the name of the relevant admin app.');
        $this->comment('The name of  your admin app is the value of the "LASALLE_APP_DOMAIN_NAME" in its .env file.');
        $this->comment("Please go to your administrative app's .env file, and copy its LASALLE_APP_DOMAIN_NAME value here.");
        $lasalleJwtAudClaim = $this->ask('What is the name of your admin app?');
        $this->comment('Attempting to set LASALLE_JWT_AUD_CLAIM in your .env to "'.$lasalleJwtAudClaim.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyLasalleJwtAudClaim', $lasalleJwtAudClaim, false);
        $this->info("Attempt to modify your env's LASALLE_JWT_AUD_CLAIM to ".$lasalleJwtAudClaim.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE LASALLE_JWT_AUD_CLAIM PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE LASALLE_ADMIN_API_URL PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your LASALLE_ADMIN_API_URL environment variable');
        $this->line('-----------------------------------------------------------------------');
        $this->comment(' ');
        $this->comment('This front-end app needs to know what administrative back-end app it belongs to.');
        $this->comment('So, you need to specify the url of the relevant admin app.');
        $this->comment('The url of  your admin app is the value of the "APP_URL" in its .env file.');
        $this->comment("Please go to your administrative app's .env file, and copy its APP_URL value here.");
        $lasalleAdminApiUrl = $this->ask('What is the URL of your admin app?');
        $this->comment('Attempting to set LASALLE_ADMIN_API_URL in your .env to "'.$lasalleAdminApiUrl.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyLasalleAdminApiUrl', $lasalleAdminApiUrl, false);
        $this->info("Attempt to modify your env's LASALLE_ADMIN_API_URL to ".$lasalleAdminApiUrl.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE LASALLE_ADMIN_API_URL PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE DB_DATABASE PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your DB_DATABASE environment variable');
        $this->line('-----------------------------------------------------------------------');
        $this->comment(' ');
        $this->comment('This front-end app uses the same database that your back-end admin app uses.');
        $this->comment("This means that can use your admin app's database settings in this front-end app's database settings.");
        $this->comment('Well, actually, there are times when you need different settings even though both apps use the same database.');
        $this->comment("You're going to have to figure out exactly what database settings to use for this front-end app!");
        $this->comment(' ');
        $this->comment('Also: are you installing with Forge? Then Forge, likely, already updated this environment variable,');
        $this->comment('so this artisan command cannot do this update -- so just hit ENTER and edit your .env manually.');
        $this->comment(' ');
        $this->comment('So, with all that said, it is time to enter the name of your database. If you are entering');
        $this->comment("the same database settings as exists in your admin app's .env file, then please enter the DB_DATABASE");
        $this->comment('value in your admin app here.');
        $this->comment(' ');
        $appDbDatabase = $this->ask('(What is the name of your database?)');
        $this->comment('Attempting to set DB_DATABASE in your .env to "'.$appDbDatabase.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyDbDatabase', $appDbDatabase, false);
        $this->info("Attempt to modify your env's DB_DATABASE to ".$appDbDatabase.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE DB_DATABASE PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE DB_USERNAME PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your DB_USERNAME environment variable');
        $this->line('-----------------------------------------------------------------------');
        $this->comment(' ');
        $this->comment('This front-end app uses the same database that your back-end admin app uses.');
        $this->comment("This means that can use your admin app's database settings in this front-end app's database settings.");
        $this->comment('Well, actually, there are times when you need different settings even though both apps use the same database.');
        $this->comment("You're going to have to figure out exactly what database settings to use for this front-end app!");
        $this->comment(' ');
        $this->comment('Also: are you installing with Forge? Then Forge, likely, already updated this environment variable,');
        $this->comment('so this artisan command cannot do this update -- so just hit ENTER and edit your .env manually.');
        $this->comment(' ');
        $this->comment("So, with all that said, it is time to enter your database's username. If you are entering");
        $this->comment("the same database settings as exists in your admin app's .env file, then please enter the DB_USERNAME");
        $this->comment('value in your admin app here.');
        $appDbUsername = $this->ask("What is the name of your database's user?");
        $this->comment('Attempting to set DB_USERNAME in your .env to "'.$appDbUsername.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyDbUsername', $appDbUsername, false);
        $this->info("Attempt to modify your env's DB_USERNAME to ".$appDbUsername.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE DB_USERNAME PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: SET THE DB_PASSWSORD PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting your DB_PASSWORD environment variable');
        $this->line('-----------------------------------------------------------------------');
        $this->comment(' ');
        $this->comment('This front-end app uses the same database that your back-end admin app uses.');
        $this->comment("This means that can use your admin app's database settings in this front-end app's database settings.");
        $this->comment('Well, actually, there are times when you need different settings even though both apps use the same database.');
        $this->comment("You're going to have to figure out exactly what database settings to use for this front-end app!");
        $this->comment(' ');
        $this->comment('Also: are you installing with Forge? Then Forge, likely, already updated this environment variable,');
        $this->comment('so this artisan command cannot do this update -- so just hit ENTER and edit your .env manually.');
        $this->comment(' ');
        $this->comment("So, with all that said, it is time to enter your database's user password. If you are entering");
        $this->comment("the same database settings as exists in your admin app's .env file, then please enter the DB_PASSWORD");
        $this->comment('value in your admin app here.');
        $appDbPassword = $this->ask("What is your database's user password?");
        $this->comment('Attempting to set DB_PASSWORD in your .env to "'.$appDbPassword.'"...');
        $this->writeEnvironmentFileWithNewKey('DummyDbPassword', $appDbPassword, false);
        $this->info("Attempt to modify your env's DB_PASSWORD to ".$appDbPassword.' is finished!');
        // -------------------------------------------------------------------------------------------------------------
        // END: SET THE DB_PASSWSORD PARAM IN .ENV
        // -------------------------------------------------------------------------------------------------------------
    



        // -------------------------------------------------------------------------------------------------------------
        // START: DONE!
        // -------------------------------------------------------------------------------------------------------------
        $this->echoOutro();

        echo "\n\n";
        $this->info('************************************************************************************************');
        $this->info('  Please run "php artisan lslibrarybackend:lasalleinstalladminapp" to complete your installation');
        $this->info('************************************************************************************************');
        echo "\n\n";
        // -------------------------------------------------------------------------------------------------------------
        // END: DONE!
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
        $this->info('              ** lslibrarybackend:lasalleinstallenv has finished **');
        $this->info('====================================================================');
        echo "\n\n";
    }

    /**
     * Create the .env file
     *
     * @return void
     */
    protected function makeEnv()
    {
        $envexampleFile = file_get_contents(base_path() . '/.env.example');
        file_put_contents($this->laravel->environmentFilePath(), $envexampleFile);
    }

    /**
     * @param text $patternToSearchFor        The text being searched
     * @param text $envFileDummyKey           The dummy key to be replaced in .env
     * @param bool $useQuotesInTheReplacement Do you want to use quotes in the replacement string?
     */
    protected function writeEnvironmentFileWithNewKey($patternToSearchFor, $envFileDummyKey, $useQuotesInTheReplacement = true)
    {
        $envFile = file_get_contents($this->laravel->environmentFilePath());

        $pattern = $this->pattern($patternToSearchFor);

        $replacement = $useQuotesInTheReplacement ? "'".$envFileDummyKey."'" : $envFileDummyKey;

        $envFile = preg_replace($pattern, $replacement, $envFile);

        file_put_contents($this->laravel->environmentFilePath(), $envFile);
    }

    /**
     * Return the LASALLE_APP_DOMAIN_NAME, which is based on the APP_URL.
     *
     * The APP_URL *must* start with "http://" or "https://". However, if it does not, the APP_URL is returned,
     * just so something is returned.
     *
     * @param text $appURL The APP_URL
     *
     * @return string
     */
    protected function getLasalleAppDomainName($appURL)
    {
        if ('http://' == substr($appURL, 0, 7)) {
            return substr($appURL, 7, strlen($appURL));
        }

        if ('https://' == substr($appURL, 0, 8)) {
            return substr($appURL, 8, strlen($appURL));
        }

        return $appURL;
    }
}
