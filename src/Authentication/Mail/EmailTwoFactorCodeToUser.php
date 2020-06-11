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
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Authentication\Mail;


// Laravel Framework
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


/**
 * Send the two factor authentication code to the user
 *
 * @return void
 */
class EmailTwoFactorCodeToUser extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The 2FA data.
     * 
     * @var array
     */
    protected $data; 

    /**
     * Create a new message instance.
     *
     * @param  array  $twofactorauthenticationData    The 2FA data 
     * @return void
     */
    public function __construct($twofactorauthenticationData)
    {
        $this->data = $twofactorauthenticationData;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(__('lasallesoftwarelibrarybackend::auth.2fa_email_subject'))
                    ->from(config('mail.from.address'), config('mail.from.name'))
                    ->view(config('lasallesoftware-librarybackend.path_to_back_end_authentication_view_path') . '.emails.2fa.sendcodetouser')
                        ->with(['twofactorauthenticationData' => $this->data])
        ;
    }
}

