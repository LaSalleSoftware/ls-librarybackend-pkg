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

namespace Lasallesoftware\Librarybackend\Authentication\Models;

// LaSalle Software
use Lasallesoftware\Librarybackend\Authentication\Models\PersonbydomainNovaFormProcessing;

// Laravel classes
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

// Laravel facades
use Illuminate\Support\Facades\DB;


/**
 * This is the model class for personbydomain.
 *
 * This is the table for logging into the app.
 *
 * @package Lasallesoftware\Librarybackend\Authentication\Models
 */
class Personbydomain extends Authenticatable
{
    use Notifiable, PersonbydomainNovaFormProcessing;

    ///////////////////////////////////////////////////////////////////
    //////////////          PROPERTIES              ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The database table used by the model.
     *
     * @var string
     */
    public $table = 'personbydomains';

    /**
     * Which fields may be mass assigned
     * @var array
     */
    protected $fillable = [
        'person_id',
        'person_first_name',
        'person_surname',
        'email',
        'installed_domain_id',
        'installed_domain_title',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'email_verified_at',
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'banned_at'         => 'datetime',
    ];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;


    ///////////////////////////////////////////////////////////////////
    //////////////         MODEL EVENTS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * The "booting" method of the model.
     *
     * Laravel will execute this function automatically
     * https://github.com/laravel/framework/blob/e6c8aa0e39d8f91068ad1c299546536e9f25ef63/src/Illuminate/Database/Eloquent/Model.php#L197
     *
     * @return void
     */
    protected static function boot()
    {
        // parent's boot function should occur first
        parent::boot();

        // Do this when the "creating" model event is dispatched
        // https://laracasts.com/discuss/channels/eloquent/is-there-any-way-to-listen-for-an-eloquent-event-in-the-model-itself
        //
        static::creating(function($personbydomain) {
            self::processTheCreateNovaForm($personbydomain);
        });

        // Do this when the "updating" model event is dispatched
        static::updating(function($personbydomain) {
            self::processTheUpdateNovaForm($personbydomain);
        });
    }


    ///////////////////////////////////////////////////////////////////
    //////////////        RELATIONSHIPS             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /*
     * One to many (inverse) relationship with persons.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function person()
    {
        return $this->belongsTo('Lasallesoftware\Librarybackend\Profiles\Models\Person');
    }

    /*
     * One to many (inverse) relationship with installed_domain.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function installed_domain()
    {
        return $this->belongsTo('Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain');
    }

    /*
    * One to many (inverse) relationship with email.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function email()
    {
        return $this->belongsTo('Lasallesoftware\Librarybackend\Profiles\Models\Email');
    }

    /*
    * One to many relationship with login.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function login()
    {
        return $this->hasMany('Lasallesoftware\Librarybackend\Authentication\Models\Login');
    }

    /*
    * Many to many relationship with lookup_role.
    *
    * Method name must be:
    *    * the model name,
    *    * NOT the table name,
    *    * singular;
    *    * lowercase.
    *
    * @return Eloquent
    */
    public function lookup_role()
    {
        return $this->belongsToMany(
            'Lasallesoftware\Librarybackend\Authentication\Models\Lookup_role',
            'personbydomain_lookup_roles',
            'personbydomain_id',
            'lookup_role_id'
        );
    }

    /*
     * A post may have one, and only one, personbydomain.
     *
     * The post database table has the field "personbydomain_id". So the post model specifies "hasOne".
     * The personbydomain is the "inverse of the relationship" (per the lexicon of https://laravel.com/docs/5.8/eloquent-relationships#one-to-one)
     *
     * Method name must be the model name, *not* the table name
     *
     * @return Eloquent
     */
    public function post()
    {
        return $this->belongsTo('Lasallesoftware\Blogbackend\Models\Post');
    }

    /*
     * One client may have lots of personbydomains associated with it.
     * One personbydomain may be associated with a lot of different clients.
     *
     * Method name must be:
     *    * the model name,
     *    * NOT the table name,
     *    * singular;
     *    * lowercase.
     *
     * @return Eloquent
     */
    public function client()
    {
        return $this->belongsToMany('Lasallesoftware\Librarybackend\Profiles\Models\Client', 'personbydomain_client', 'personbydomain_id', 'client_id');
    }

    
    ///////////////////////////////////////////////////////////////////
    //////////////          LOCAL SCOPES            ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Is it the owner role?
     *
     * @return bool
     */
    public function scopeIsOwner()
    {
        return $this->lookup_role()->where('lookup_role_id', 1)->exists();
    }

    /**
     * Is it the super administrator role?
     *
     * @return bool
     */
    public function scopeIsSuperadministrator()
    {
        return $this->lookup_role()->where('lookup_role_id', 2)->exists();
    }

    /**
     * Is it the administrator role?
     *
     * @return bool
     */
    public function scopeIsAdministrator()
    {
        return $this->lookup_role()->where('lookup_role_id', 3)->exists();
    }

    /**
     * Is it the client role?
     *
     * @return bool
     */
    public function scopeIsClient()
    {
        return $this->lookup_role()->where('lookup_role_id', 4)->exists();
    }

    ///////////////////////////////////////////////////////////////////
    //////////////          OTHER STUFF             ///////////////////
    ///////////////////////////////////////////////////////////////////

    /**
     * Does the current user (ie, personbydomain) have the specified role?
     *
     * @param  string  $role
     * @return bool
     */
    public function hasRole($role)
    {
        if ($role == strtolower('owner')) {
            return $this->IsOwner();
        }

        if ($role == strtolower('superadministrator')) {
            return $this->IsSuperadministrator();
        }

        if ($role == strtolower('administrator')) {
            return $this->IsAdministrator();
        }

        if ($role == strtolower('client')) {
            return $this->IsClient();
        }

        return false;
    }

    /**
     * Is an individual user banned?
     *
     * @param  integer  $id
     * @return boolean
     */
    public function isBanned(int $id): bool 
    {
        $user = $this->where([
            ['id', '=', '1'], 
            ['banned_enabled', '=', '1']
        ])->first();

        return $user ? true : false;
    }

    /**
     * Get the client_id associated with the given personbydomain_id, from the personbydomain_client pivot table. 
     * 
     *   **Note that the first record found is returned**
     * 
     * 
     * 
     * See the unit test: Tests\Unit\Library\Clients\PersonbydomainGetClientIdTest
     *
     * @param   int $personbydomain_id
     * @return  int                         The client_id is returned when found. Else, return 0.
     */
    public function getClientId($personbydomain_id)
    {
        // This check is here so my unit tests don't get "Error: Call to a member function client() on null"
        if (is_null($personbydomain_id)) return 0;

        $result = Personbydomain::find($personbydomain_id)->client()->first();
        return (!is_null($result)) ? $result->id : 0;
    }
}