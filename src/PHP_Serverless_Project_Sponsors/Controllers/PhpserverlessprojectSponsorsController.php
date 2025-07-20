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


 namespace Lasallesoftware\Librarybackend\PHP_Serverless_Project_Sponsors\Controllers;

// LaSalle Software
use Lasallesoftware\Librarybackend\Common\Http\Controllers\CommonController;

// Laravel Framework
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class PhpserverlessprojectSponsorsController extends CommonController
{
    /**
     * Find the requested post, and provide the response.
     *
     * @param  Illuminate\Http\Request  $request
     * @return mixed
     */
    public function GetLIst(Request $request)
    {
        // Create an UUID
        // SKIP THIS
        //$comment = 'Lasallesoftware\Blogbackend\Http\Controllers\AllBlogPostsController->AllBlogPosts()';
        //$uuid = $this->createAnUuid(2, $comment, 1);

        return response()->json([
            'sponsors' => $this->get_PHP_Serverless_Project_Sponsors(),
        ], 200);
    }
}