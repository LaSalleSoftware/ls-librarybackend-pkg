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
use Lasallesoftware\Librarybackend\APP_KEY_rotation\ReEncryptDatabaseFields;
use Lasallesoftware\Librarybackend\Authentication\Models\Login;
use Lasallesoftware\Librarybackend\Common\Commands\CommonCommand;
use Lasallesoftware\Librarybackend\Profiles\Models\Website;

// Laravel classes
use Illuminate\Console\ConfirmableTrait;
use Symfony\Component\Console\Input\InputOption;


class ReEncryptRunMeSecondCommand extends CommonCommand
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'lslibrarybackend:reencryptrunmesecond';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Re-encrypt encrypted database fields with your new APP_KEY. RUN THIS SECOND!';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        // -------------------------------------------------------------------------------------------------------------
        // START: INTRO
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->info('================================================================================');
        $this->info('                       Welcome to my LaSalle Software\'s');
        $this->info('             Administrative Back-end App\' APP_KEY ROTATION Artisan Command!');
        $this->info('                     Artisan Command 2 of 2: Run the actual re-encryption');
        $this->info('================================================================================');

        if (file_exists($this->laravel->environmentFilePath())) {
            $this->info('  You are in the '.mb_strtoupper(env('LASALLE_APP_NAME')).' LaSalle Software Application.');
            $this->info('================================================================================');
            $this->info('  You are installing to your '.$this->getLaravel()->environment().' environment.');
            $this->info('================================================================================');
        }

        $this->info('  This artisan command performs the actual database field re-encryption. ');
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
        // RUN THESE CHECKS SILENTLY
        // -------------------------------------------------------------------------------------------------------------

        // START: APP CHECK
        if (env('LASALLE_APP_NAME') != 'adminbackendapp') {
            echo "\n\n";
            $this->line("This artisan command is specifically for my LaSalle Software's admin application.");
            $this->line('You are installing my '.mb_strtoupper(env('LASALLE_APP_NAME')).' LaSalle Software application.');
            $this->exitOutroWhenCheckFails();           

            return;
        }
        // END: APP CHECK

        // START: WEBSITES RECORDS EXIST CHECK
        if (!$this->isWebsitesRecords()) {
            echo "\n\n";
            $this->line("You have no records in the websites database table. There is nothing to re-encrypt!");
            $this->exitOutroWhenCheckFails();  

            return;
        }
        // END: WEBSITES RECORDS EXIST CHECK


        // START: APP_KEY CHECK
        if (config('app.key') == '') {
            echo "\n\n";
            $this->line("Your APP_KEY environment variable is blank. Please set it to your new APP_KEY.");
            $this->exitOutroWhenCheckFails();  

            return;
        }
        // END: APP_KEY CHECK


        // START: LASALLE_PREVIOUS_APP_KEY CHECK
        if (config('lasallesoftware-librarybackend.lasalle_previous_app_key') == '') {
            echo "\n\n";
            $this->line("Your LASALLE_PREVIOUS_APP_KEY environment variable is blank. Please set it to equal to your previous APP_KEY.");
            $this->exitOutroWhenCheckFails();  

            return;
        }
        // END: LASALLE_PREVIOUS_APP_KEY CHECK


        // START: LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN CHECK
        if (!env('LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN')) {
            echo "\n\n";
            $this->line('Your "LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN" environment variable is false. Please set it to true.');
            $this->exitOutroWhenCheckFails();  

            return;
        }
        // END: LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN CHECK


        // START: LOGINS RECORDS DELETED
        if ($this->isLoginsRecords()) {
            echo "\n\n";
            $this->line("Logins database records exist. A user(s) may be logged into your admin. Please login to your admin and delete all the logins records.");
            $this->exitOutroWhenCheckFails();  

            return;
        }
        // END: LOGINS RECORD DELETED


        // START: APP_KEY VERIFICATION
        echo "\n\n";
        $this->line('  Your "APP_KEY" environment variable is: ' . config('app.key'));
        if (! $this->confirm('Is your "APP_KEY" correct?')) {
            $this->line('Please enter the correct "APP_KEY" environment variable in your .env, and then re-run this artisan command.');
            echo "\n\n";
            return;
        }

        $this->info('Excellent! The "APP_KEY" is correct!');
        // END: APP_KEY VERIFICATION


        // START: LASALLE_PREVIOUS_APP_KEY VERIFICATION
        echo "\n\n";
        $this->line('  Your "LASALLE_PREVIOUS_APP_KEY" environment variable is: ' . config('lasallesoftware-librarybackend.lasalle_previous_app_key'));
        if (! $this->confirm('Is your "LASALLE_PREVIOUS_APP_KEY" correct?')) {
            $this->line('Please enter the correct "LASALLE_PREVIOUS_APP_KEY" environment variable in your .env, and then re-run this artisan command.');
            echo "\n\n";
            return;
        }

        $this->info('Excellent! The "LASALLE_PREVIOUS_APP_KEY" is correct!');
        // END: LASALLE_PREVIOUS_APP_KEY VERIFICATION


        // START: THE TWO KEY ENV VARS MUST NOT BE EQUAL
        if (config('app.key') == config('lasallesoftware-librarybackend.lasalle_previous_app_key')) {
            echo "\n\n";
            $this->line('Your "APP_KEY" and "LASALLE_PREVIOUS_APP_KEY" are the same. Please correct and then re-run this artisan command.');
            $this->exitOutroWhenCheckFails();  

            return;
        }
        // END: THE TWO KEY ENV VARS MUST NOT BE EQUAL

        // -------------------------------------------------------------------------------------------------------------
        // END: CHECKS
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: RUN THE RE-ENCRYPTION
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        if ($this->confirm("When you are ready to run the re-encryption, press any key to continue...")) {
            // blank on purpose
        }
        if ($this->confirm("Are you sure that you are ready to run the re-encryption?  Press any key to continue...")) {
            // blank on purpose
        }
        
        echo "\n\n";
        $this->line('  Starting the re-encryption process...');

        $this->runReEncryption();
        
        echo "\n\n";
        $this->line('  The re-encryption process is completed.');
        // -------------------------------------------------------------------------------------------------------------
        // END: RUN THE RE-ENCRYPTION
        // -------------------------------------------------------------------------------------------------------------



        // -------------------------------------------------------------------------------------------------------------
        // START: POST RE-ENCRYPTION STEPS
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->line('  Your final step is to re-set "LASALLE_EMERGENCY_BAN_ALL_USERS_FROM_ADMIN_APP_LOGIN=false" in your .env.');
        // -------------------------------------------------------------------------------------------------------------
        // END: POST RE-ENCRYPTION STEPS
        // -------------------------------------------------------------------------------------------------------------


        // -------------------------------------------------------------------------------------------------------------
        // START: FINSIHED!
        // -------------------------------------------------------------------------------------------------------------
        echo "\n\n";
        $this->info('================================================================================');
        $this->info('         Congratulations! You finished rotating your APP_KEY! ');
        $this->info('================================================================================');

        echo "\n\n";
        echo "====================================================================\n";
        echo "    ** lslibrarybackend:reencryptrunmesecond is finished **";
        echo "\n====================================================================";
        echo "\n\n";
        // -------------------------------------------------------------------------------------------------------------
        // END: FINSIHED!
        // -------------------------------------------------------------------------------------------------------------
    }


    /**
     * Run the exit outro when a check fails
     *
     * @return void
     */
    private function exitOutroWhenCheckFails()
    {
        $this->line('Exiting out of this artisan command.');
        $this->line('exiting...');
        echo "\n";
        $this->line('You are now exited from lslibrarybackend:reencryptrunmesecond.');
        echo "\n\n";
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

    /**
     * Are there any records in the logins database table?
     *
     * @return boolean
     */
    private function isLoginsRecords()
    {
        $logins = Login::all();
        return $logins->isNotEmpty();
    }

    /**
     * Run the re-encryption
     *
     * @return void
     */
    private function runReEncryption()
    {
        $reencryptdatabasefields = new ReEncryptDatabaseFields();
        $reencryptdatabasefields->reEncryptTheWebsiteCommentsField();
    }
}