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
 * @copyright  (c) 2019-2022 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * 
 * @see        https://lasallesoftware.ca
 * @see        https://packagist.org/packages/lasallesoftware/ls-librarybackend-pkg
 * @see        https://github.com/LaSalleSoftware/ls-librarybackend-pkg
 *
 */

namespace Lasallesoftware\Librarybackend\Common\Http\Controllers;

// LaSalle Software
use Lasallesoftware\Librarybackend\Helpers\GeneralHelpers;
use \Lasallesoftware\Librarybackend\Firewall\Http\Middleware\Whitelist;

// Laravel Framework
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

// Laravel Facade
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CommonController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    use GeneralHelpers;

    /**
     * Send the "No Posts Found" error reponse.
     *
     * @return Response
     */
    public function sendTheNoPostsFoundErrorResponse()
    {
        // 200 OK
        // 201 Created
        // 202 Accepted
        // 401 Unauthorized
        // 404 Not found
        // 418 I'm a teapot  https://httpstatuses.com/418

        return response()->json([
            'error'  => __('lasallesoftwareblogbackend::blogbackend.error_status_code_404'),
            'reason' => __('lasallesoftwareblogbackend::blogbackend.error_reason_no_posts_found'),
        ], 404);

    }

    /**
     * Get the installed domain specified in the request's header.
     *
     * @param  Illuminate\Http\Request  $request
     * @return string                             Such as "hackintosh.lsv2-basicfrontend-app.com" (omit quotes).
     */
    public function getRequestingDomainFromTheHeader($request)
    {
        return $request->header('RequestingDomain');
    }

    /**
     * The request comes from which installed_domain_id?
     *
     * @param  Illuminate\Http\Request  $request
     * @return int
     */
    public function getInstalledDomainId($request)
    {
        // The request is coming from which installed domain (returns the "title" field of the installed_domains db table)?
        $installedDomainTitle = $this->getInstalledDomainFromTheRequest($request);

        // The post belongs to which installed domain?
        return $this->getInstalledDomainIdFromTheTitleField($installedDomainTitle);
    }

    /**
     * Get the installed domain specified in the request header, stripped of the "http:\\".
     *
     * @param  Illuminate\Http\Request  $request
     * @return mixed
     */
    public function getInstalledDomainFromTheRequest($request)
    {
        return $this->removeHttp($this->getRequestingDomainFromTheHeader($request));
    }

    /**
     * Get the ID of the "installed_domains" database table from the title field.
     *
     * @param  string  $installedDomainTitle       The value of the title field.
     * @return mixed
     */
    public function getInstalledDomainIdFromTheTitleField($installedDomainTitle)
    {
        return DB::table('installed_domains')
            ->where('title', $installedDomainTitle)
            ->pluck('id')
            ->first()
        ;
    }

    /**
     * Get the author's name from the personbydomain's ID
     *
     * @param  int      $personbydomainId
     * @return string
     */
    public function getAuthorNameFromThePersonbydomain($personbydomainId)
    {
        $personbydomain = DB::table('personbydomains')
            ->where('id', $personbydomainId)
            ->first()
        ;

        return $personbydomain->person_first_name . " " . $personbydomain->person_surname;
    }

    /**
     * Get the featured image info.
     *
     * @param  object  $post
     * @return array
     */
    public function getFeaturedImage($post)
    {
        $featured_image = [];

        if ($post->featured_image_external_file) {
            $featured_image['image']           = $post->featured_image_external_file;
            $featured_image['social_meta_tag'] = $post->featured_image_external_file;
            $featured_image['type']            = 'external_file';
            return $featured_image;
        }

        if ($post->featured_image_code) {
            $featured_image['image']           = $post->featured_image_code;
            // if the type = 'code', then cannot use the selected image. Therefore, use the default social media image URL instead
            $featured_image['social_meta_tag'] = config('lasallesoftware-librarybackend.lasalle_social_media_meta_tag_default_image');
            $featured_image['type']            = 'code';
            return $featured_image;
        }

        // https://nova.laravel.com/docs/2.0/resources/file-fields.html#how-files-are-stored
        // Please note that storing images locally works when the front-end domain is the same as the back-end domain.
        // However, when the front-end and back-end domains are not the same, have to use the cloud (S3)
        if ($post->featured_image_upload) {
            $disk = config('lasallesoftware-librarybackend.lasalle_filesystem_disk_where_images_are_stored');
            $featured_image['image']           = Storage::disk($disk)->url($post->featured_image_upload);
            $featured_image['social_meta_tag'] = Storage::disk($disk)->url($post->featured_image_upload);
            $featured_image['type']            = 'upload';
            return $featured_image;
        }

        // have to return values even though there are no images specified, otherwise the front-end will get errors.
        $featured_image['image']           = 'none';
        $featured_image['social_meta_tag'] = 'none';
        $featured_image['type']            = 'none';
        return $featured_image;
    }


    /**
     * Run the IP whitelist middleware manually.
     * https://github.com/LaSalleSoftware/ls-librarybackend-pkg/issues/68
     *
     * @return bool
     */
    public function executeTheWhitelistManually(): bool
    {
        $whitelist = new Whitelist;
        return $whitelist->isAllow();
    }
}
