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

namespace Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors;

// Laravel facade
use Illuminate\Support\Facades\DB;


class PhpserverlessprojectsponsorsHelpers
{
    public function getSponsors(): array
    {
        $sponsors = $this->getSponsorsData();

        return $this->transformSponsors($sponsors);
    }
    

    /**
     * Get sponsors data
     *
     * @return object
     */
    public function getSponsorsData()
    {
        $sponsors = DB::table('phpserverlessproject_sponsors')
            ->where('enabled', true)
            //->where('former_sponsor', false)
            ->orderBy('full_name', 'asc')
            ->get()
        ;

        return $sponsors;
    }    

    /**
     * Transform a single podcast_show collection for the front-end.
     *
     * @param  object   $sponsors   PHP Serverless Project sponsors
     * @return array
     */
    public function transformSponsors($sponsors): array
    {
        $transformedSponsors = [];

        foreach ($sponsors as $sponsor) {

            $transformedSponsor = [
                'full_name'                => $sponsor->full_name,
                'image_url'                => $this->getImageURL($sponsor->image_url),
                'image_thumbnail_url'      => $this->getImageURL($sponsor->image_thumbnail_url),
                'profile_full'             => $sponsor->profile_full,
                'profile_short'            => $sponsor->profile_short,
                'link_to_sponsor_website'  => $sponsor->link_to_sponsor_website,
                'email_address'            => $sponsor->email_address,
                'sponsor_type'             => $this->getSponsorType($sponsor),
                'former_sponsor'           => $sponsor->former_sponsor,
                'enabled'                  => $sponsor->enabled,
             ];

             $transformedSponsors[] = $transformedSponsor;
        }

        return $transformedSponsors;        
    }

    private function getImageURL($url): string
    {
        if ($this->isEmpty($url)) {
            //return "https://NEED_A_DEFAULT_IMAGE_HERE!!.png";
        }

        return $url;
    }

    private function getSponsorType($sponsor): string
    {
        if ($sponsor->basecamp_sponsor) return 'Basecamp subscription sponsor';
        if ($sponsor->restream_sponsor) return 'Restream.io subscription sponsor';

        return "Umbrella sponsor";
    }


    private function isEmpty($text): bool
    {
        if (is_null($text)) return true;

        if (empty($text)) return true;

        if ('' === $text) return true;

        return false;
    }
}