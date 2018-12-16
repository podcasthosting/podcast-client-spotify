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
    const API_URI = 'https://ws.spotify.com/analytics/api/';

    /**
     * @var string
     */
    private $token;

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
     * @var String
     */
    private $clientId;

    /**
     * DeliveryClient constructor.
     *
     * @param String $token Issued by the Spotify operations team to authenticate against the API
     * @param String $clientId
     * @param String|null $uri
     * @param HttpClient|null $httpClient
     */
    public function __construct(String $token, String $clientId, HttpClient $httpClient = null)
    {
        $this->token = $token;

        $this->clientId = $clientId;

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
    public function get(\DateTime $date)
    {
        $ret = $this->httpClient->get($this->getUrl($date), $this->getHeaders());
        $code = $ret->getStatusCode();
        $body = json_decode($ret->getBody()->getContents());

        switch ($code) {
            case 200:
            case 201:
                return $body;
                //return new Result();
            case 401:
                throw new AuthException();
            default:
                throw new \UnexpectedValueException("Call failed with code: {$code}.", $code);
        }
    }

    private function getUrl(\DateTime $date)
    {
        return self::API_URI
            . $this->clientId . '/'
            . 'aggregatedepisodes/'
            . $date->format('Y') . '/'
            . $date->format('m') . '/'
            . $date->format('d')
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