<?php
/**
 * User: Fabio Bacigalupo
 * Date: 10.07.18
 * Time: 14:34
 */

namespace podcasthosting\PodcastClientSpotify\Analytics;

use Buzz\Browser;
use Buzz\Client\Curl;
use Http\Client\HttpClient;
use podcasthosting\PodcastClientSpotify\Exceptions\{
    AuthException, DomainException, DuplicateException
};
use Tuupola\Http\Factory\RequestFactory;
use Tuupola\Http\Factory\ResponseFactory;

class Client
{
    /**
     * URI to work with
     * https://ws.spotify.com/analytics/api/CLIENTID/aggregatedepisodes/YYYY/MM/DD
     */
    const API_URI = 'https://ws.spotify.com/analytics/';

    /**
     * @var string
     */
    private $token;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var array
     */
    private $headers = [
        'Content-Type' => 'application/json',
    ];

    /**
     * DeliveryClient constructor.
     *
     * @param String $token Issued by the Spotify operations team to authenticate against the API
     * @param HttpClient|null $httpClient
     */
    public function __construct(String $token, String $uri = null, HttpClient $httpClient = null)
    {
        $this->token = $token;

        if (!is_null($uri)) {
            $this->uri = $uri;
        } else {
            $this->uri = self::API_URI;
        }

        if (!is_null($httpClient)) {
            $this->httpClient = $httpClient;
        } else {
            $this->httpClient = new Browser(new Curl([], new ResponseFactory()), new RequestFactory());
        }
    }

    /**
     * Used to create a feed that will start being ingested
     * and will be available in the Spotify client as soon as we index it,
     * that can take at most 24 hours.
     *
     * @param String $name Is an internal name used for book-keeping and doesn’t represent the name that will be
     * shown in the spotify client.
     * @param String $uri Needs to use http(s) protocol and be publicly accessible. This is the identifier for this
     * podcast.
     * @return Result
     * @throws DuplicateException
     * @throws AuthException
     * @throws DomainException
     */
    public function create($name, $uri)
    {
        $body = json_encode([
            'name' => $name,
            'url' => $uri,
        ]);

        $ret = $this->httpClient->post($this->getUrl(), $this->getHeaders(), $body);
        $code = $ret->getStatusCode();
        $body = json_decode($ret->getBody()->getContents());

        switch ($code) {
            case 200:
            case 201:
                return $body;
                //return new Result();
            case 401:
                throw new AuthException();
            case 403:
                throw new DomainException($body->reason);
            case 409:
                throw new DuplicateException($body->reason);
            default:
                throw new \UnexpectedValueException("Call failed with code: {$code}.");
        }
    }

    private function getUrl($attach = null)
    {
        return self::API_URI
            . DIRECTORY_SEPARATOR
            . ($attach ? DIRECTORY_SEPARATOR . $attach : null)
            . '?oauth_token=' . $this->getToken();
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        return $this->headers;
    }

    /**
     * @param array $headers
     */
    public function setHeaders(array $headers): void
    {
        $this->headers = $headers;
    }

    /**
     * @return String
     */
    public function getToken(): String
    {
        return $this->token;
    }
}