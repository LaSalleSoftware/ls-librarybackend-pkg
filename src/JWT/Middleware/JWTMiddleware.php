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
 * @copyright  (c) 2019-2024 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 *
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 */

namespace Lasallesoftware\Librarybackend\JWT\Middleware;

// LaSalle Software
use Lasallesoftware\Librarybackend\JWT\JWTHelpers;
use Lasallesoftware\Librarybackend\JWT\Validation\JWTValidation;
use Lasallesoftware\Librarybackend\Authentication\Models\Json_web_token;

// Laravel class
use Illuminate\Http\Request;

// Third party class
use Lcobucci\JWT\Configuration;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Signer\Key\InMemory;

// PHP class
use Closure;
use DateTimeImmutable;


class JWTMiddleware
{
    /**
     * @var Lasallesoftware\Librarybackend\JWT\JWTValidation
     */
    protected $jwtvalidation;

    /**
     * @var Lasallesoftware\Librarybackend\Authentication\Models\Json_web_token
     */
    protected $jwtModel;

    /**
     * @var Lasallesoftware\Librarybackend\JWT\JWTHelpers
     */
    protected $jwtHelpers;


    /**
     * Create a new middleware instance.
     *  
     * @param  \Lasallesoftware\Librarybackend\JWT\JWTValidation                      $jwtvalidation
     * @param  \Lasallesoftware\Librarybackend\Authentication\Models\Json_web_token   $jwtModel
     * @param  \Lasallesoftware\Librarybackend\JWT\JWTHelpers                         $jwtHelpers
     * @return void
     */
    public function __construct(JWTValidation $jwtvalidation, Json_web_token $jwtModel, JWTHelpers $jwtHelpers)
    {
        $this->jwtvalidation = $jwtvalidation;
        $this->jwtModel      = $jwtModel;
        $this->jwtHelpers    = $jwtHelpers;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Get the JWT from the request
        // https://github.com/laravel/framework/blob/0776ffdf2f01290a97f2e80b39c6e24b5067e23b/src/Illuminate/Http/Concerns/InteractsWithInput.php#L54
        $bearerToken = $request->bearerToken();

        // Get the name of the domain making this request, from the request
        $requestingDomain = $request->header('RequestingDomain');

        // Get the key for the requesting installed domain
        $installed_domain_key = $this->jwtHelpers->getKeyForGivenInstalledDomainTitle($requestingDomain);



        // Parse the incoming JWT string into an  Lcobucci\JWT object:
        //  * instantiating the same as done in Lasallesoftware\Libraryfrontend\JWT\Factory
        $signer = new Sha256();
        //  * use the JWT key for the installed domain
        $key    = InMemory::plainText($installed_domain_key);



        // (i) instantiate the the "configuration" object
        $jwtConfiguration = Configuration::forSymmetricSigner($signer, $key);

        // (ii) create the token object
        $jwtToken = $jwtConfiguration->parser()->parse($bearerToken);

        // Validate 
        $validationResult = $this->jwtvalidation->validateJWT($jwtToken, $jwtConfiguration);

        



/*
        return response()->json([
            'result status'        => ($validationResult['result']) ? 'yes, passes validation!' : 'no, fails validation',
            'result reason'        => ($validationResult['result']) ? 'passes!' : $validationResult['claim'] . ' , this claim is invalid',
            'RequestingDomain'     => $requestingDomain,
            'installed_domain_key' => $installed_domain_key,
            'token'                => $request->bearerToken(),
            'iss claim'            => $jwtToken->claims()->get('iss'),
            'aud claim'            => $jwtToken->claims()->get('aud'),
            'exp claim'            => $jwtToken->claims()->get('exp'),
            'iat claim'            => $jwtToken->claims()->get('iat'),
            'jti claim'            => $jwtToken->claims()->get('jti'),
            'interval'             => 'PT' . config('lasallesoftware-librarybackend.lasalle_jwt_iat_claim_valid_for_how_many_seconds') . 'S',
        ], 200);
*/        





        

        if (!$validationResult['result']) {
            return response()->json([
                'error'  => 'invalid token',
                'reason' => $validationResult['claim'] . ' claim is invalid',
            ], 403);
        }

        $this->jwtModel->saveWithJWT($bearerToken);

        

        return $next($request);
    }
}
