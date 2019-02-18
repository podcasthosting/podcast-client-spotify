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
     * DeliveryClient constructor.
     *
     * @param String $clientId
     * @param String $token
     * @param String|null $uri
     * @param HttpClient|null $httpClient
     */
    public function __construct(String $clientId, String $token, HttpClient $httpClient = null)
    {
        $this->clientId = $clientId;

        $this->token = $token;

        if (!is_null($httpClient)) {
            $this->httpClient = $httpClient;
        } else {
            $this->httpClient = new Browser(new Curl(new ResponseFactory()), new RequestFactory());
        }
    }

    /**
     * @var String
     */
    private $clientId;

    /**
     *
     * @param \DateTime $date
     * @return Result
     * @throws AuthException
     */
    public function get(\DateTime $date)
    {
        $ret = $this->httpClient->get($this->getUrl($date), $this->getHeaders());
        $code = $ret->getStatusCode();

        switch ($code) {
            case 200:
            case 201:
                return new Result(gzdecode((string) $ret->getBody()));
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