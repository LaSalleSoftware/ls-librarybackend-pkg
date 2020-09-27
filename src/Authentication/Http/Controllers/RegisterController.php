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

namespace Lasallesoftware\Librarybackend\Authentication\Http\Controllers;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Http\Controllers\CommonController;
use Lasallesoftware\Laravelapp\Providers\RouteServiceProvider;
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;
use Lasallesoftware\Librarybackend\Profiles\Models\Person;
use Lasallesoftware\Librarybackend\Profiles\Models\Email;
use Lasallesoftware\Librarybackend\Profiles\Models\Person_email;
use Lasallesoftware\Librarybackend\Authentication\Models\Personbydomain;
use Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain;

// Laravel Framework
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Carbon;

// Laravel facades
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends CommonController
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    //protected $redirectTo = '/home';

    /**
     * UuidGenerator instance.
     *
     * @var UuidGenerator
     */
    protected $uuidGenerator;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(UuidGenerator $uuidGenerator)
    {
        $this->middleware('guest');

        $this->uuidGenerator = $uuidGenerator;
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => ['required', 'string', 'max:255'],
            'surname'    => ['required', 'string', 'max:255'],
            'email'      => ['required', 'string', 'email', 'max:255', 'unique:personbydomains'],
            'password'   => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // create a uuid first
        $uuid = $this->uuidGenerator->createUuid(5, "Created by the Register Form.");
        $now  = Carbon::now(null);

        // emails table
        $email = new Email();

        $email->lookup_email_type_id = 1;
        $email->email_address        = trim($data['email']);
        $email->description          = 'Created by the Register Form.';
        $email->comments             = 'Created by the Register Form.';
        $email->uuid                 = $uuid;
        $email->created_at           = $now;
        $email->created_by           = 1;

        $email->save();


        // persons table
        $person = new Person();

        $person->first_name  = trim($data['first_name']);
        $person->surname     = trim($data['surname']);
        $person->description = 'Created by the Register Form.';
        $person->comments    = 'Created by the Register Form.';
        $person->uuid        = $uuid;
        $person->created_at  = Carbon::now(null);
        $person->created_by  = 1;

        $person->save();


        // person_email table
        $person_email = new Person_email();

        $person_email->person_id = $person->id;
        $person_email->email_id  = $email->id;

        $person_email->save();


        // personbydomains table
        $lasalle_app_domain_name = app('config')->get('lasallesoftware-librarybackend.lasalle_app_domain_name');
        $installed_domain = Installed_domain::where('title', $lasalle_app_domain_name)->first();

        $personbydomain = new Personbydomain();

        $personbydomain->person_id              = $person->id;
        $personbydomain->person_first_name      = $person->first_name;
        $personbydomain->person_surname         = $person->surname;
        $personbydomain->email                  = $email->email_address;
        $personbydomain->password               = Hash::make($data['password']);
        $personbydomain->installed_domain_id    = $installed_domain->id;
        $personbydomain->installed_domain_title = $installed_domain->title;
        $personbydomain->uuid                   = $uuid;
        $personbydomain->created_at             = $now;
        $personbydomain->created_by             = 1;

        $personbydomain->save();


        // assign the user role (personbydomain_lookup_roles)
        DB::table('personbydomain_lookup_roles')->insert(
            ['personbydomain_id' => $personbydomain->id,
                'lookup_role_id' => config('lasallesoftware-librarybackend.lasalle_app_default_user_role')
            ]
        );


        return $personbydomain;
     }

    /**
     * Show the application registration form.
     * 
     * Override Illuminate\Foundation\Auth\RegistersUsers::showRegistrationForm()
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        return view( config('lasallesoftware-librarybackend.path_to_back_end_authentication_view_path') . '.register' );
    }
}
