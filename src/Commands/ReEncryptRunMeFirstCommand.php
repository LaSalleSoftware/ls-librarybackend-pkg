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

// LaSalle Software class
use Lasallesoftware\Librarybackend\Common\Commands\CommonCommand;
use Lasallesoftware\Librarybackend\Profiles\Models\Website;

// Laravel classes
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;
use Illuminate\Encryption\Encrypter;


class ReEncryptRunMeFirstCommand extends CommonCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrarybackend:reencryptrunmefirst';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-encrypt encrypted database fields with your new APP_KEY. RUN THIS FIRST!';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // START: INTRO
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->info('================================================================================');
        $this->info('                       Welcome to my LaSalle Software\'s');
        $this->info('             Administrative Back-end App\' APP_KEY ROTATION Artisan Command!');
        $this->info('                          Artisan Command 1 of 2: Prep');
        $this->info('================================================================================');

        if (file_exists($this->laravel->environmentFilePath())) {
            $this->info('  You are in the '.mb_strtoupper(env('LASALLE_APP_NAME')).' LaSalle Software Application.');
            $this->info('================================================================================');
            $this->info('  You are installing to your '.$this->getLaravel()->environment().' environment.');
            $this->info('================================================================================');
        }

        $this->info('  This command guides you through the preparation for the APP_KEY rotation process. ');
        $this->info('================================================================================');
        $this->info('  Read https://lasallesoftware.ca/docs/v2/system_reencrypting_encrypted_fields *BEFORE* running this command.');
        $this->info('================================================================================');
        $this->line('<fg=red;bg=yellow>  RE-ENCRYPTING DATABASE FIELDS HAS THE POTENTIAL OF MAKING THE ENCRYPTED DATABASE FIELDS UNREADABLE!</>');
        $this->line('<fg=red;bg=yellow>  THIS APP_KEY ROTATION PROCESS IS *NOT* FULLY AUTOMATED!</>');
        $this->line('<fg=red;bg=yellow>  THIS APP_KEY ROTATION PROCESS NEEDS YOU TO PERFORM MANUAL STEPS.</>');
        $this->line('<fg=red;bg=yellow>  PLEASE SLOW DOWN, GO STEP-BY-STEP, AND UNDERSTAND WHAT YOU ARE DOING.</>');
        $this->info('================================================================================');
        $this->info('  FOR REFERENCE ONLY:');
        $this->info('    * https://laravel.com/docs/7.x/encryption');
        $this->info('    * https://tighten.co/blog/app-key-and-you (written in the L5.x era)');
        $this->info('    * https://techsemicolon.github.io/blog/2019/06/14/laravel-app-key-rotation-policy-for-security/');
        $this->info('    * https://divinglaravel.com/app_key-is-a-secret-heres-what-its-used-for-how-you-can-rotate-it');
        $this->info('================================================================================');
        $this->info('  Thank you for using my LaSalle Software!');
        $this->info('  --Bob Bloom');
        $this->info('================================================================================');
        // -------------------------------------------------------------------------------------------------------------
        // END: INTRO
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: DID YOU BACKUP YOUR DATABASE?
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('<fg=red;>================================================================================</>');
        $this->line('<fg=white;bg=red>  There is a risk of making your encrypted database fields unreadable.</>');
        $this->line('<fg=white;bg=red>  Therefore: you absolutely must -- MUST! -- backup your database first.</>');
        $this->line('<fg=white;bg=red>  You should also ensure that your backup actually restores properly!</>');
        $this->line('<fg=white;bg=red>  An ounce (gram) of prevention is worth a pound (kg) of cure!</>');
        $this->line('<fg=red;>================================================================================</>');
        if (! $this->confirm("Is your database backed up?")) {
            $this->line('  <fg=white;bg=red>*** Please back up your database, then run this artisan command again ***</>');
            echo "\n\n";
            return;
        }

        $this->info("Your database is backed up, so let's proceed...");
        // -------------------------------------------------------------------------------------------------------------
        // END: DID YOU BACKUP YOUR DATABASE?
        // -------------------------------------------------------------------------------------------------------------




        // -------------------------------------------------------------------------------------------------------------
        // START: CHECKS
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('================================================================================');
        $this->line('  Let\'s run some checks...');
        $this->line('================================================================================');

        // START: .ENV FILE CHECK
        echo "\n";
        $this->line('  checking that you have an .env file...');
        if (!file_exists($this->laravel->environmentFilePath())) {
            echo "\n\n";
            $this->line('Whoa! You do *not* have the .env environment file!');
            $this->line('So I am exiting you out of this artisan command.');
            $this->line('exiting...');
            $this->line('You are now exited from lslibrarybackend:reencryptrunmefirst.');
            echo "\n\n";

            return;
        }
        $this->info('  Yes, you have an .env file.');
        // END: .ENV FILE CHECK

        // START: APP CHECK
        echo "\n\n";
        $this->line('  checking that you are in the admin app....');
        if (env('LASALLE_APP_NAME') != 'adminbackendapp') {
            echo "\n\n";
            $this->line("This artisan command is specifically for my LaSalle Software's admin application.");
            $this->line('You are installing my '.mb_strtoupper(env('LASALLE_APP_NAME')).' LaSalle Software application.');
            $this->line('So I am exiting you out of this artisan command.');
            $this->line('exiting...');
            $this->line('You are now exited from lslibrarybackend:reencryptrunmefirst.');
            echo "\n\n";

            return;
        }
        $this->info('  Yes, this is the administrative back-end application.');
        // END: APP CHECK

        // START: RECORDS EXIST CHECK
        echo "\n\n";
        $this->line('  checking that have records in the websites database table...');
        if (!$this->isWebsitesRecords()) {
            echo "\n\n";
            $this->line("You have no records in the websites database table. There is nothing to re-encrypt!");
            $this->line('So I am exiting you out of this artisan command.');
            $this->line('exiting...');
            $this->line('You are now exited from lslibrarybackend:reencryptrunmefirst.');
            echo "\n\n";

            return;
        }
        $this->info('  Yes, you have records in the website database table.');
        // END: RECORDS EXIST CHECK

        // START: LOGIN TO SEE ACTUAL WEBSITE COMMENTS CHECK
        echo "\n\n";
        $this->line('  Perhaps a bit crazy, but log into your admin and visually verify that your website comments field is human readable. Just an idea!');
        if ($this->confirm("press any key to continue...")) {
            // blank on purpose
        }
        // END: LOGIN TO SEE ACTUAL WEBSITE COMMENTS CHECK


        echo "\n\n";
        $this->info('================================================================================');
        $this->info("  Congratulations! Checks pass, so let's proceed... ");
        $this->info('================================================================================');
        // -------------------------------------------------------------------------------------------------------------
        // END: CHECKS
        // -------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // START: LOG OUT EVERYONE FROM THE ADMIN
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('  Everyone needs to log out of your admin. So log into your admin and delete all the "logins" records to end all the logged-in sessions.');
        // -------------------------------------------------------------------------------------------------------------
        // END: LOG OUT EVERYONE FROM THE ADMIN
        // -------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // START: UPDATE .ENV
        // -------------------------------------------------------------------------------------------------------------
        echo "\n";
        $this->line('  Then, in your .env, set "LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN=true"');
        echo "\n\n\n";
        $this->line('  In order to re-encrypt your encrypted database fields, and to use your new APP_KEY from this point onward, you need to do two things.');
        $this->line('  I am assuming that your existing APP_KEY is *NOT* your new APP_KEY!');
        echo "\n";
        $this->line('     i) copy your "APP_KEY" value to the "LASALLE_PREVIOUS_APP_KEY" environment variable');
        echo "\n";
        $this->line('    ii) paste a new value to "APP_KEY". A suggested value: '. $this->getNewAppKey() );
        echo "\n";
        $this->line('  ** Store your new "APP_KEY" value in a safe place. See https://lasallesoftware/docs/v2/system_aws_parameter_store **');
        echo "\n";
        $this->line('  Please remember to save your .env file!');

        echo "\n";
        if ($this->confirm("When you have updated your .env with the new key values, press any key to continue...")) {
            // blank on purpose
        }
        // -------------------------------------------------------------------------------------------------------------
        // END: UPDATE .ENV
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: FINSIHED!
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->info('===============================================================================================');
        $this->info('  Congratulations! You finished the first of two artisan commands needed to rotate your APP_KEY ');
        echo "\n";
        $this->info('  Please run "lslibrarybackend:reencryptrunmesecond" to finish the APP_KEY rotation process. ');
        $this->info('===============================================================================================');

        echo "\n\n";
        echo "====================================================================\n";
        echo "    ** lslibrarybackend:reencryptrunmefirst is finished **";
        echo "\n====================================================================";
        echo "\n\n";
        // -------------------------------------------------------------------------------------------------------------
        // END: FINSIHED!
        // -------------------------------------------------------------------------------------------------------------
    }


    /**
     * Are there any records in the websites database table?
     *
     * @return boolean
     */
    private function isWebsitesRecords()
    {
        $websites = Website::all();
        return $websites->isNotEmpty();
    }

    private function getNewAppKey()
   {
       return 'base64:'.base64_encode(Encrypter::generateKey($this->getCipher()));
   }

   private function getCipher()
   {
       return config('app.cipher');
   }
}