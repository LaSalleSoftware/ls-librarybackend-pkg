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
 */

namespace Lasallesoftware\Librarybackend\JWT\Validation;

// LaSalle Software
use Lasallesoftware\Librarybackend\Authentication\Models\Installed_domains_jwt_key;
use Lasallesoftware\Librarybackend\Authentication\Models\Json_web_token;
use Lasallesoftware\Librarybackend\Helpers\GeneralHelpers;
use Lasallesoftware\Librarybackend\Profiles\Models\Installed_domain;
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\Models\Uuid;

// Laravel classes
use Illuminate\Http\Response;

// Third party classes
//https://github.com/lcobucci/jwt/blob/3.3/README.md
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\ValidationData;


/**
 * These are the JWT "claims" I am using for validation:
 *   ** ISS claim (issuer --> who issued the JWT. What it means to me = front-end domain)
 *   ** AUD claim (audience --> who is supposed to receive the JWT. What it means to me = back-end domain)
 *   ** IAT claim (issued at --> time JWT was issued. What it means to me = created_at timestamp --> FYI)
 *   ** EXP claim (expiration time --> time on or after JWT not accepted. What it means to me = best before date)
 *
 * (see https://tools.ietf.org/html/rfc7519#section-4)
 */
class JWTValidation
{
    use GeneralHelpers;

    protected $time;

    public function __construct()
    {
        $this->time = time();
    }

    /**
     * Validate the given json web token.
     *
     * @param  Lcobucci\JWT\Builder  $jwtToken
     * @return array
     */
    public function validateJWT($jwtToken)
    {
        if (!$this->isJWTDuplicate($jwtToken))   return ['result' => false, 'claim' => 'signature'];

        if (!$this->isSignatureValid($jwtToken)) return ['result' => false, 'claim' => 'signature'];

        if (!$this->isIssClaimValid($jwtToken))  return ['result' => false, 'claim' => 'iss'];
        
        if (!$this->isAudClaimValid($jwtToken))  return ['result' => false, 'claim' => 'aud'];

        if (!$this->isExpClaimValid($jwtToken))  return ['result' => false, 'claim' => 'exp'];

        if (!$this->isIatClaimValid($jwtToken))  return ['result' => false, 'claim' => 'iat'];

        if (!$this->isJtiClaimValid($jwtToken))  return ['result' => false, 'claim' => 'jti'];

        return ['result' => true];
    }

    /**
     * Has this JWT been used before?
     *
     * @param  Lcobucci\JWT\Builder  $jwtToken    The Json Web Token from the requesting domain
     * @return bool                               true  = the JWT has *NOT* expired based on its EXP claim
     *                                            false = the JWT expired based on its EXP claim
     */
    public function isJWTDuplicate($jwtToken)
    {
        return (is_null(Json_web_token::where('jwt', $jwtToken)->first())) ? true : false;
    }

    /**
     * Is the JWT's signature valid?
     *
     * The JWT's must be signed.
     * Using SHA256.
     * The key must be in the front-end's "LASALLE_JWT_KEY" env parameter, and in the back-end's database.
     *
     * https://github.com/lcobucci/jwt/blob/3.3/README.md
     *
     * @param  Lcobucci\JWT\Builder  $jwtToken    The Json Web Token from the requesting domain
     * @return bool                               true  = the signature is valid
     *                                            false = the signature is not valid
     */
    public function isSignatureValid($jwtToken)
    {
        // STEP 1: What is the id of the installed_domains table for the ISS claim?
        $installed_domain = Installed_domain::where('title', $jwtToken->getClaim('iss'))->first();
        if (is_null($installed_domain)) return false;

        // STEP 2: What is the key in the (API side) database?
        $row = Installed_domains_jwt_key::where('installed_domain_id', $installed_domain->id)->latest()->first();
        if (is_null($row)) return false;

        // STEP 3: Now that we have the key from our end, is the incoming JTW's signature valid?
        //         The client side's key has to be the same as the key we fetched from the db.
        $key    = $row->key;
        $signer = new Sha256();

        return ($jwtToken->verify($signer, $key)) ? true : false;
    }

    /**
     * (ISS claim) Is the incoming request coming from a valid domain?
     *
     * The iss claim specifies the domain originating the request. This domain must match exactly a domain in the
     * "installed_domains" database table's "title" field. This table is populated during installation.
     *
     * The JWT "iss" claim https://tools.ietf.org/html/rfc7519#section-4.1.1
     *
     * @param  Lcobucci\JWT\Builder  $jwtToken    The Json Web Token from the requesting domain
     * @return bool                               true  = incoming request is coming from a valid domain
     *                                            false = incoming request is *NOT* coming from a valid domain
     */
    public function isIssClaimValid($jwtToken)
    {
        $url = $this->removeHttp($jwtToken->getClaim('iss'));
        return (is_null(Installed_domain::where([['title','=', $url],['enabled','=', 1]])->first())) ? false : true;
    }

    /**
     * (AUD claim) Is the incoming request intended for the API back-end domain?
     *
     * The "aud" (audience) claim identifies the recipients that the JWT is intended for. This domain must match exactly
     * the domain specified in the config: lasallesoftware-library.lasalle_app_domain_name
     *
     * The JWT "aud" claim https://tools.ietf.org/html/rfc7519#section-4.1.3
     *
     * @param  Lcobucci\JWT\Builder  $jwtToken    The Json Web Token from the requesting domain
     * @return bool                               true  = incoming request is intended for a valid domain
     *                                            false = incoming request is *NOT* intended for a valid domain
     */
    public function isAudClaimValid($jwtToken)
    {
        $url = $this->removeHttp($jwtToken->getClaim('aud'));
        return (config('lasallesoftware-librarybackend.lasalle_app_domain_name') == $url) ? true : false;
    }

    /**
     * (EXP claim) Has the incoming request expired?
     *
     * The "exp" (expiration time) claim identifies the expiration time on or after which the JWT MUST
     * NOT be accepted for processing.  The processing of the "exp" claim requires that the current date/time
     * MUST be before the expiration date/time listed in the "exp" claim.
     *
     * The JWT "exp" claim https://tools.ietf.org/html/rfc7519#section-4.1.4
     *
     * @param  Lcobucci\JWT\Builder  $jwtToken    The Json Web Token from the requesting domain
     * @return bool                               true  = the JWT has *NOT* expired based on its EXP claim
     *                                            false = the JWT expired based on its EXP claim
     */
    public function isExpClaimValid($jwtToken)
    {
        return ($jwtToken->getClaim('exp') >= time()) ? true : false;
    }

    /**
     * (IAT claim) Has the incoming request expired based on when the JTW was issued and the back-end app's config param?
     *
     * The "iat" (issued at) claim identifies the time at which the JWT was issued.  This claim can be used to
     * determine the age of the JWT.  Its value MUST be a number containing a NumericDate value.
     *
     * The JWT "iat" claim https://tools.ietf.org/html/rfc7519#section-4.1.6
     *
     * @param  Lcobucci\JWT\Builder  $jwtToken    The Json Web Token from the requesting domain
     * @return bool                               true  = the JWT is valid based on its IAT claim & lasalle_jwt_iat_claim_valid_for_how_many_seconds
     *                                            false = the JWT is *NOT* valid based on its IAT claim & lasalle_jwt_iat_claim_valid_for_how_many_seconds
     */
    public function isIatClaimValid($jwtToken)
    {
        $iatIsValidUntil = $jwtToken->getClaim('iat') +
            config('lasallesoftware-library.lasalle_jwt_iat_claim_valid_for_how_many_seconds')
        ;

        return (time() <= $iatIsValidUntil) ? true : false;
    }

    /**
     * (JTI claim) The front-end creates a 40 character random string for the JTI claim, simply for the sake of 
     * having a value. This value has no meaning whatsoever. This is an optional claim and basically I am not 
     * using it. Except that this claim cannot be null.
     *
     * The "jti" (JWT ID) claim provides a unique identifier for the JWT. The identifier value MUST be assigned in a
     * manner that ensures that there is a negligible probability that the same value will be accidentally assigned
     * to a different data object; if the application uses multiple issuers, collisions MUST be prevented among values
     * produced by different issuers as well.  The "jti" claim can be used to prevent the JWT from being replayed.
     * The "jti" value is a case-sensitive string.  Use of this claim is OPTIONAL.
     *
     * The JWT "jti" claim https://tools.ietf.org/html/rfc7519#section-4.1.7
     *
     * @param  Lcobucci\JWT\Builder  $jwtToken    The Json Web Token from the requesting domain
     * @return bool                               true (valid), false (not valid)
     */
    public function isJtiClaimValid($jwtToken)
    {
        return (!is_null($jwtToken->getClaim('jti'))) ? true : false;
    }
}
