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

/**
 * Class LasalleinstalladminappCommand.
 */
class LasalleinstalladminappCommand extends CommonCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrarybackend:lasalleinstalladminapp';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'LaSalle Software administrative app installation.';

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
        if (file_exists($this->laravel->environmentFilePath())) {
            if (env('LASALLE_APP_NAME') != 'adminbackendapp') {
                echo "\n\n";
                $this->line("This installation artisan command is specifically for my LaSalle Software's admin application.");
                $this->line('You are installing my '.mb_strtoupper(env('LASALLE_APP_NAME')).' LaSalle Software application.');
                $this->line('So I am exiting you out of this artisan command.');
                $this->line('exiting...');
                $this->line('You are now exited from lslibrarybackend:lasalleinstalladminapp.');
                echo "\n\n";

                return;
            }
        }


        if (!file_exists($this->laravel->environmentFilePath())) {
            echo "\n\n";
            $this->line('  Your .env environment file does not exist. ');
            $this->line('  Please run "php artisan lslibrarybackend:lasalleinstallenv", then, re-run this artisan command.');
            echo "\n\n";

            return;
        }


        // -------------------------------------------------------------------------------------------------------------
        // START: INTRO
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->info('================================================================================');
        $this->line('                       Welcome to my LaSalle Software\'s');
        $this->line('             Administrative Back-end App\' Installation Artisan Command!');
        $this->info('================================================================================');

        if (file_exists($this->laravel->environmentFilePath())) {
            $this->line('  You are installing the '.mb_strtoupper(env('LASALLE_APP_NAME')).' LaSalle Software Application.');
            $this->info('================================================================================');
            $this->line('  You are installing to your '.$this->getLaravel()->environment().' environment.');
            $this->info('================================================================================');
        }

        $this->line('  This command does stuff to set up my admin app, including Nova and database prep.');
        $this->info('================================================================================');
        $this->line('  Read https://lasallesoftware.ca/docs/v2/gettingstarted_installation_backendapp ');
        $this->line('  *BEFORE* running this command.');
        $this->info('================================================================================');
        $this->line('  Thank you for installing my LaSalle Software!');
        $this->line('  --Bob Bloom');
        $this->info('================================================================================');
        // -------------------------------------------------------------------------------------------------------------
        // END: INTRO
        // -------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // START: DID YOU RUN lslibrarybackend:LASALLEINSTALLENV ALREADY?
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->alert('You must run lslibrarybackend:lasalleinstallenv before running this artisan command? Did you run it first?');
        $runConfirmation = $this->ask('<fg=yellow;bg=red>(type the word "oops" to exit this artisan command, or just press enter to continue...)</>');
        if ($runConfirmation == strtolower('oops')) {
            $this->line('<fg=red;bg=yellow>Good stuff! Please run "php artisan lslibrarybackend:lasalleinstallenv" and then re-run this artisan command.</>');
            $this->echoOutro();

            return;
        }
        $this->comment('ok... Let us get this show on the road...');
        // -------------------------------------------------------------------------------------------------------------
        // END: DID YOU RUN lslibrarybackend:LASALLEINSTALLENV ALREADY?
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: DID YOU SET UP YOUR DATABASE?
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n\n";
        $this->alert('Is your database already set-up? Are your database env vars ok in .env?');
        $runConfirmation = $this->ask('<fg=yellow;bg=red>(type the word "yes" to continue)</>');
        if ($runConfirmation != strtolower('yes')) {
            $this->line('<fg=red;bg=yellow>OK, you want to set up your DB, and check your vars in .env, so I am NOT going to continue running this command. Bye!</>');
            $this->echoOutro();

            return;
        }
        $this->comment('ok... you said that you want to continue running this command. Let us continue then...');
        // -------------------------------------------------------------------------------------------------------------
        // END: DID YOU SET UP YOUR DATABASE?
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: THE adminbackendapp NEEDS LARAVEL's FIRST-PARTY "NOVA" ADMIN PACKAGE
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line("  Now setting up Laravel Nova's first party admin package");
        $this->line('-----------------------------------------------------------------------');

        // if NOVA is not installed
        if (!class_exists('Laravel\Nova\Nova')) {
            echo "\n";
            $this->line('  <fg=red;bg=yellow>The first party Laravel Nova commercial package is not installed.');
            $this->line('  <fg=red;bg=yellow>Nova is critical to my admin app. You buy and must install Nova!');
            $this->line('  <fg=red;bg=yellow>So please install Nova, then re-run this artisan command.');
            $this->echoOutro();

            return;
        }

        // NOVA is installed, so run "php artisan nova:install"
        $this->comment('Now running Laravel\'s first-party administration package "Nova" set-up...');
        $this->call('nova:install');

        // delete the app/nova files because we definitely do not want this user resource showing up in our Nova menu
        $this->deleteFile(app_path().'/Nova/Resource.php');
        $this->deleteFile(app_path().'/Nova/User.php');
        echo "\n";
        $this->comment('Finished the Nova set-up.');
        // -------------------------------------------------------------------------------------------------------------
        // END: THE adminbackendapp NEEDS LARAVEL's FIRST-PARTY "NOVA" ADMIN PACKAGE
        // -------------------------------------------------------------------------------------------------------------





        // -------------------------------------------------------------------------------------------------------------
        // START: DATABASE DROP, MIGRATION, AND SEEDS
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('-----------------------------------------------------------------------');
        $this->line('  Now setting up the database');
        $this->line('-----------------------------------------------------------------------');

        // Set an env var to false when production
        // (probably not really necessary)
        if (strtolower($this->getLaravel()->environment()) === 'production') {
            $this->setLasallePopulateDatabaseWithTestDataToFalse();
        }

        // Drop the database tables
        echo "\n";
        $this->comment('Do you want to DROP the existing database?');
        $runConfirmation = $this->ask('<fg=yellow;bg=red>(type the word "drop" to DROP your database)</>');
        if ($runConfirmation == strtolower('drop')) {
            $this->comment('Now about to DROP your existing database...');
            $database = $this->input->getOption('database');
            $this->dropAllTables($database);
            $this->info('Your database was dropped.');
        }

        // Migration
        echo "\n\n";
        $this->comment('Now running the database migration...');
        $this->call('migrate');
        $this->comment('Your database was migrated.');

        // Seeding
        // lslibrarybackend:customseed checks if the blog backend is installed, and if so it migrates the blog database table
        echo "\n\n";
        $this->comment('Now running the database seeding...');
        $this->call('lslibrarybackend:customseed');
        //$this->comment('Your database was seeded.');

        // Set up the first owner.
        // Only the test data populates the first owner, which happens to be "bob.bloom@lasallesoftware.ca", "secret")
        if (!env('LASALLE_POPULATE_DATABASE_WITH_TEST_DATA')) {
            echo "\n\n";
            $this->line('================================================================================');
            $this->line('                       SETTING UP YOUR FIRST OWNER');
            $this->line('================================================================================');
            $this->line(' ');
            $this->line("Your administrative app requires someone with an 'owner' role.");
            $this->line('An owner has the highest level of permissions in your admin app.');
            $this->line('Only a few people should be assigned this ownership role.');
            $this->line('--------------------------------------------------------------------------------');
            $this->line('Please set up the first owner of your admin site by specifying the first name,');
            $this->line('surname, and email address of your first owner:');
            $this->line('--------------------------------------------------------------------------------');
            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line("Admin Site Owner's First Name:");
            $this->line('-----------------------------------------------------------------------');
            echo "\n";
            $this->comment('What is the first name of your first owner?');
            $ownerFirstName = $this->ask('<bg=red>(I do *not* check for spelling or for anything, so please type c-a-r-e-f-u-l-l-y!)</>');
            $this->comment('Thank you! The first name you entered: '.$ownerFirstName);

            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line("Admin Site Owner's Surname:");
            $this->line('-----------------------------------------------------------------------');
            echo "\n";
            $this->comment('What is the surname of your first owner?');
            $ownerSurname = $this->ask('<bg=red>(I do *not* check for spelling or for anything, so please type c-a-r-e-f-u-l-l-y!)</>');
            $this->comment('Thank you! The surname you entered: '.$ownerSurname);

            echo "\n\n";
            $this->line('-----------------------------------------------------------------------');
            $this->line("Admin Site Owner's Email Address:");
            $this->line('-----------------------------------------------------------------------');
            echo "\n";
            $this->comment('What is the email address of your first owner?');
            $ownerEmailAddress = $this->ask('<bg=red>(I do *not* check for spelling or for anything, so please type c-a-r-e-f-u-l-l-y!)</>');
            $this->comment('Thank you! The email address you entered: '.$ownerEmailAddress);

            $this->createTheFirstOwner($ownerFirstName, $ownerSurname, $ownerEmailAddress);

            echo "\n\n";
            $this->line('================================================================================');
            $this->line('Congratulations! You just set up your first owner!');
            $this->line('================================================================================');
            $this->line(' ');
            $this->line('Here are your credentials to log into your admin app:');
            $this->line(' ');
            $this->comment(' email address: '.$ownerEmailAddress);
            $this->comment('      password: secret');
            $this->line(' ');
            $this->line(' ');
            $this->line('<bg=red>Note: please change your password when you log in.</>');
            $this->line('================================================================================');
        }
        // -------------------------------------------------------------------------------------------------------------
        // END: DATABASE DROP, MIGRATION, AND SEEDS
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
        $this->info('              ** lslibrarybackend:lasalleinstalladminapp has finished **');
        $this->info('====================================================================');
        echo "\n\n";
    }

    /**
     * Set LASALLE_POPULATE_DATABASE_WITH_TEST_DATA to false.
     */
    protected function setLasallePopulateDatabaseWithTestDataToFalse()
    {
        $envFile = file_get_contents($this->laravel->environmentFilePath());

        $pattern = $this->pattern('LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=true');
        $replacement = 'LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=false';
        $envFile = preg_replace($pattern, $replacement, $envFile);

        $pattern = $this->pattern('LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=TRUE');
        $replacement = 'LASALLE_POPULATE_DATABASE_WITH_TEST_DATA=false';
        $envFile = preg_replace($pattern, $replacement, $envFile);

        file_put_contents($this->laravel->environmentFilePath(), $envFile);
    }

    /**
     * Create the admin app's first owner.
     *
     * @param string $firstName    the first owner's first name
     * @param string $surname      the first owner's surname
     * @param string $emailAddress the first owner's email address
     */
    protected function createTheFirstOwner($firstName, $surname, $emailAddress)
    {
        $firstName = ucfirst($firstName);
        $surname = ucfirst($surname);

        $uuid = 'created from lasalleinstalladminapp';
        $this->createUuidsRecord($uuid);
        $this->createEmailsRecord($emailAddress, $uuid);
        $this->createPersonsRecord($firstName, $surname, $uuid);
        $this->createPersonbydomainsRecord($firstName, $surname, $emailAddress, $uuid);
    }

    /**
     * Create the UUID record for creating the first owner.
     *
     * Using the DB facade for expediency.
     *
     * @param string $uuid the uuid code for creating the first owner using this custom artisan command
     */
    protected function createUuidsRecord($uuid)
    {
        DB::table('uuids')->insert([
            'lasallesoftware_event_id' => 1,
            'uuid' => $uuid,
            'created_at' => Carbon::now(),
            'created_by' => 1,
        ]);
    }

    /**
     * Create the emails record for the first owner.
     *
     * Using the DB facade for expediency.
     *
     * @param string $emailAddress the first owner's email address
     * @param string $uuid         the uuid code for creating the first owner using this custom artisan command
     */
    protected function createEmailsRecord($emailAddress, $uuid)
    {
        DB::table('emails')->insert([
            'id' => 1,
            'lookup_email_type_id' => 1,
            'email_address' => $emailAddress,
            'description' => null,
            'comments' => null,
            'uuid' => $uuid,
            'created_at' => Carbon::now(),
            'created_by' => 1,
            'updated_at' => null,
            'updated_by' => null,
            'locked_at' => null,
            'locked_by' => null,
        ]);
    }

    /**
     * Create the persons record for the first owner.
     *
     * Using the DB facade for expediency.
     *
     * @param string $firstName the first owner's first name
     * @param string $surname   the first owner's surname
     * @param string $uuid      the uuid code for creating the first owner using this custom artisan command
     */
    protected function createPersonsRecord($firstName, $surname, $uuid)
    {
        DB::table('persons')->insert([
            'id' => 2,
            'name_calculated' => $firstName.' '.$surname,
            'salutation' => null,
            'first_name' => $firstName,
            'middle_name' => null,
            'surname' => $surname,
            'position' => null,
            'description' => 'This person must be an "owner".',
            'comments' => null,
            'profile' => null,
            'featured_image' => null,
            'birthday' => null,
            'anniversary' => null,
            'deceased' => null,
            'comments_date' => null,
            'uuid' => $uuid,
            'created_at' => Carbon::now(),
            'created_by' => 1,
            'updated_at' => null,
            'updated_by' => null,
            'locked_at' => null,
            'locked_by' => null,
        ]);

        // populate the person_email pivot table with the above email address
        DB::table('person_email')->insert([
            'id' => 1,
            'person_id' => 2,
            'email_id' => 1,
        ]);
    }

    /**
     * Create the personbydomains record for the first owner.
     *
     * Using the DB facade for expediency.
     *
     * @param string $firstName    the first owner's first name
     * @param string $surname      the first owner's surname
     * @param string $emailAddress the first owner's email address
     * @param string $uuid         the uuid code for creating the first owner using this custom artisan command
     */
    protected function createPersonbydomainsRecord($firstName, $surname, $emailAddress, $uuid)
    {
        DB::table('personbydomains')->insert([
            'person_id' => 2,
            'person_first_name' => $firstName,
            'person_surname' => $surname,
            'name_calculated' => $firstName.' '.$surname,
            'email' => $emailAddress,
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
            'installed_domain_id' => 1,
            'installed_domain_title' => app('config')->get('lasallesoftware-librarybackend.lasalle_app_domain_name'),
            'uuid' => $uuid,
            'created_at' => Carbon::now(),
            'created_by' => 1,
            'updated_at' => null,
            'updated_by' => null,
            'locked_at' => null,
            'locked_by' => null,
        ]);

        DB::table('personbydomain_lookup_roles')->insert([
            'id' => 1,
            'personbydomain_id' => 1,
            'lookup_role_id' => 1,
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
