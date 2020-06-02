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

namespace Lasallesoftware\Librarybackend\APIRequestsToTheBackend;

// LaSalle Software
use Lasallesoftware\Blogfrontend\JWT\Factory;
use Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator;

// Laravel Framework
use Illuminate\Support\MessageBag;

// Third party classes
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;


/**
 * API Http Requests to the admin backend.
 *
 * @package Lasallesoftware\Librarybackend\APIRequestsToTheBackend\HttpRequestToAdminBackend
 */
trait HttpRequestToAdminBackend
{
    /**
     * @var Lasallesoftware\Blogfrontend\JWT\Factory
     */
    protected $factory;

    /**
     * @var Lasallesoftware\Librarybackend\UniversallyUniqueIDentifiers\UuidGenerator
     */
    protected $uuidGenerator;

    /**
     * The message bag instance.
     *
     * @var \Illuminate\Support\MessageBag
     */
    protected $messages;


    /**
     * DisplaySinglePostController constructor.
     *
     * @param  Lasallesoftware\Blogfrontend\JWT\Factory  $factory
     */
    public function __construct(Factory $factory, UuidGenerator $uuidGenerator)
    {
        $this->factory       = $factory;
        $this->uuidGenerator = $uuidGenerator;
    }

    public function makeUuid($comment, $lasallesoftareEventId = 9)
    {
        return $this->uuidGenerator->createUuid($lasallesoftareEventId, $comment, 1);
    }

    /**
     * Send a request to the LaSalle administrative back-end.
     *
     * @param  string  $uuid            Universal unique identification token
     * @param  string  $endpointPath    The endpoint path. Does *not* include the back-end's URL
     * @param  string  $httpRequest     The HTTP request. Generally, "GET" or "POST"
     * @param  string  $slug            A slug, optional
     * @param  array   $postData        Data, in an array, to send in a post request. Optional.
     * 
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendRequestToLasalleBackend($uuid, $endpointPath, $httpRequest, $slug = null, $postData = null)
    {
        $jwt = $this->getJWT($uuid);

        $headers = $this->getHeader($endpointPath, $jwt);

        $apiUrl = $this->getBackendURL();

        $query = $this->getQuery($slug);

        $client = new Client();

        try {

            if ($httpRequest == 'GET') {

                $response = $client->request('GET', $apiUrl . $endpointPath, [
                    'headers'         => $headers,
                    'connect_timeout' => 10,
                    'query'           => $query,
                ]);
            }

            if ($httpRequest == 'POST') {
                // http://docs.guzzlephp.org/en/stable/quickstart.html#post-form-requests
                $response = $client->request('POST', $apiUrl . $endpointPath, [
                    'headers'         => $headers,
                    'connect_timeout' => 10,
                    'form_params'     => $postData,
                    //'debug' => true
                ]);
            }

            return $response;

        } catch (RequestException $e) {
            return $this->createTheErrorMessageBag($e->getResponse());

        } catch (\Exception $e) {
            return $this->createTheErrorMessageBag($e->getResponse());
        }
    }

    /**
     * Our request returned an error? Well, let's create the MessageBag instance for the view.
     *
     * @param  \GuzzleHttp\Psr7\Response  $response
     */
    public function createTheErrorMessageBag($response)
    {
        $body           = json_decode($response->getBody());
        $this->messages = new MessageBag();

        $this->messages->add('StatusCode', $response->getStatusCode());
        
        if (isset($body->error)) {
            $this->messages->add('Error', $body->error);
        } else {
            $this->messages->add('Error', 'no error was specified');
        }

        if (isset($body->reason)) {
            $this->messages->add('Reason', $body->reason);
        } else {
            $this->messages->add('Error', 'no reason was specified');
        }

        return;
    }

    

    /**
     * Get the URL to the admin back-end API.
     *
     * @return string
     */
    public function getBackendURL()
    {
        return env('LASALLE_ADMIN_API_URL');
    }

    /**
     * Get the featured image.
     *
     * @param  string  $featuredImage        The featured image returned from the admin back-end.
     * @return string
     */
    public function getFeaturedImage($featuredImage)
    {
        if ($featuredImage == "none") {
            return config('lasallesoftware-frontendapp.lasalle_featured_image_default_image');
        }

        return $featuredImage;
    }

    /**
     * Get the featured image type.
     *
     * @param  string  $featuredImageType        The featured image type returned from the admin back-end.
     * @return string
     */
    public function getFeaturedImageType($featuredImageType)
    {
        if ($featuredImageType == "none") {
            return config('lasallesoftware-frontendapp.lasalle_featured_image_default_type');
        }

        return $featuredImageType;
    }

    /**
     * Get the social media featured image.
     *
     * @param  string  $featuredImageSocialMediaMetaTag        The social media featured image returned from the admin back-end.
     * @return string
     */
    public function getFeaturedImageSocialMediaMetaTag($featuredImageSocialMediaMetaTag)
    {
        if ($featuredImageSocialMediaMetaTag == "none") {
            return config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_default_image');
        }

        return $featuredImageSocialMediaMetaTag;
    }

    /**
     * Display the error page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function displayErrorView()
    {
        return view(config('lasallesoftware-frontendapp.lasalle_path_to_front_end_view_path') . '.errors.main', [
            'status_code'         => $this->messages->first('StatusCode'),
            'error'               => $this->messages->first('Error'),
            'reason'              => $this->messages->first('Reason'),
            'featured_image'      => $this->getFeaturedImage('none'),
            'featured_image_type' => $this->getFeaturedImageType('none'),
            'featured_image_social_media_meta_tag' => $this->getFeaturedImageSocialMediaMetaTag('none'),
            'copyright'           => env('LASALLE_COPYRIGHT_IN_FOOTER'),
        ]);
    }

    /**
     * Format date from a date string.
     *
     * @param  string  $date     2019-09-29T04:00:00.000000Z
     * @return string
     */
    public function formatDate($date)
    {
        return date(config('lasallesoftware-frontendapp.lasalle_date_format'),strtotime($date));
    }

    /**
     * Format date from a date string specifically for the HTML time tag.
     *
     * https://www.w3schools.com/tags/tag_time.asp
     *
     * @param  string  $date     2019-09-29T04:00:00.000000Z
     * @return string
     */
    public function formatDateForHTMLTimeTag($date)
    {
        return date('Y-m-d',strtotime($date));
    }

    /**
     * Put together the full front-end URL with the 'page' query parameter so we can use it in our links.
     *
     * @param  string       $url             The pagination string sent by the back-end, generated by Laravel's
     *                                       pagination instance methods (previousPageUrl() and nextPageUrl())
     *                                       https://laravel.com/docs/6.x/pagination#paginator-instance-methods
     * @param  stromg       $frontEndRoute   Maybe "/blog/all" or "/category/buffalobills" or something-like-that.
     * @return string|null
     */
    public function getPageURLForPagination($url = null, $frontEndRoute)
    {
        if ((is_null($url)) || ($url == '')) {
            return null;
        }

        return env('APP_URL') . '/' . $frontEndRoute . '?page=' . $this->getPageQueryParameterFromUrlString($url) ;
    }

    /**
     * Return the value of the page query parameter in a URL.
     *
     * If the value is null, return null.
     *
     * Raison d'etre: to grab the page number in the Laravel pagination links (https://laravel.com/docs/6.x/pagination)
     *
     * @param  string     $url    Eg: 'http://hackintosh.ls-adminbackend-app.com:8888/api/v1/allarticlesblog?page=1'
     * @return mixed|null
     */
    public function getPageQueryParameterFromUrlString($url)
    {
        $queryParameterString = parse_url($url, PHP_URL_QUERY);
        parse_str($queryParameterString, $output);
        return isset($output['page']) ? $output['page'] : null;
    }

    /**
     * Get the value of the twitter:site social media meta tag.
     *
     * https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/markup
     *
     * @return string
     */
    public function getSocialMediaMetaTagSite()
    {
        return config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_site');
    }

    /**
     * Get the value of the twitter:creator social media meta tag.
     *
     * https://developer.twitter.com/en/docs/tweets/optimize-with-cards/overview/markup
     *
     * @return string
     */
    public function getSocialMediaMetaTagCreator()
    {
        if (config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_creator') == '') {
            return config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_site');
        }

        return config('lasallesoftware-frontendapp.lasalle_social_media_meta_tag_creator');
    }

    /**
     * Transform posts from the incoming API request.
     *
     * Yes, I am fully aware of that thing called https://laravel.com/docs/6.x/eloquent-resources. Need I really have
     * to play with every toy in the toy box?
     *
     * @param  array  $posts
     * @return array
     */
    public function getTransformedPosts($posts)
    {
        $transformedPosts = [];

        foreach ($posts as $post) {

            $transformedPost = [
                'title'                          => $post->title,
                'slug'                           => $post->slug,
                'author'                         => $post->author,
                'excerpt'                        => $post->excerpt,
                'featured_image'                 => $this->getFeaturedImage($post->featured_image),
                'featured_image_type'            => $this->getFeaturedImageType($post->featured_image_type),
                'publish_on'                     => $this->formatDate($post->publish_on),
                'datetime'                       => $this->formatDateForHTMLTimeTag($post->publish_on),
            ];

            $transformedPosts[] = $transformedPost;
        }

        return $transformedPosts;
    }

    /**
     * Get the JWT.
     *
     * @param  string   $uuid
     * @return void
     */
    public function getJWT($uuid)
    {
        // Looks like the constructor is not invoked or just fails when this class is called by a queue job.
        // Specifically, Lasallesoftware\Contactformfrontend\Jobs\CreateNewDatabaseRecord.
        // So, Lasallesoftware\Blogfrontend\JWT\Factory is not injected. 
        // To compensate, I have this yucky if statement.

        if (isset($this->factory)) {
            return $this->factory->createJWT($uuid);
        }

        $factory = app('Lasallesoftware\Blogfrontend\JWT\Factory');
        return $factory->createJWT($uuid);        
    }

    /**
     * Get the request header
     *
     * @param  string   $endpointPath      The endpoint path, excluding the back-end's URL.
     * @param  string   $jwt               The json web token.
     * @return void
     */
    public function getHeader($endpointPath, $jwt)
    {
        $header = [
            'Authorization'    => 'Bearer ' . $jwt,
            'RequestingDomain' => env('LASALLE_APP_DOMAIN_NAME'),
            'Accept'           => 'application/json',
        ];

        if ($endpointPath == $this->getEndpointPath('DisplayHomepageBlogPostsController')) {
            $header['NumberOfBlogPostsToDisplayOnTheHomePage'] = config('lasallesoftware-frontendapp.lasalle_number_of_recent_blog_posts_to_display_on_the_home_page');
        }

        return $header;
    }

    /**
     * Get the query for the GET requests
     *
     * @param  string   $slug               The slug.
     * @return void
     */
    public function getQuery($slug)
    {
        $request = request();
        $page    = $request->input('page');

        if (isset($page)) {
            return [
                'slug'                          => $slug,
                'itemsDisplayedOnPaginatedPage' => config('lasallesoftware-frontendapp.lasalle_pagination_number_of_items_displayed_per_page'),
                'page'                          => $page,
            ];
        }

        return ['slug' => $slug];
    }
}