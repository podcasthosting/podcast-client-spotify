<?php
/**
 * User: Fabio Bacigalupo
 * Date: 10.07.18
 * Time: 14:34
 */

namespace podcasthosting\PodcastClientSpotify;

use Buzz\Browser;
use Buzz\Client\Curl;
use Http\Client\HttpClient;
use podcasthosting\PodcastClientSpotify\Exceptions\AuthException;
use Tuupola\Http\Factory\RequestFactory;
use Tuupola\Http\Factory\ResponseFactory;

class AuthClient
{
    /**
     * URI to work with
     */
    const API_URI = 'https://ws.spotify.com';

    /**
     *
     */
    const ENDPOINT_OAUTH_TOKEN = '/oauth/token';

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
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * DeliveryClient constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param HttpClient|null $httpClient
     */
    public function __construct(string $clientId, string $clientSecret, HttpClient $httpClient = null)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;

        if (!is_null($httpClient)) {
            $this->httpClient = $httpClient;
        } else {
            $this->httpClient = new Browser(new Curl([], new ResponseFactory()), new RequestFactory());
        }
    }

    /**
     * @return false|mixed|string
     * @throws AuthException
     */
    public function getToken()
    {
        $body = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];

        $ret = $this->httpClient->post($this->getUrl(self::ENDPOINT_OAUTH_TOKEN), $this->getHeaders(), http_build_query($body));
        //$ret = $this->httpClient->get($this->getUrl(self::ENDPOINT_OAUTH_TOKEN) . "?" . http_build_query($body), $this->getHeaders());
        $code = $ret->getStatusCode();
        $body = json_decode($ret->getBody()->getContents());

        switch ($code) {
            case 200:
            case 201:
                return $body;
            case 400:
            case 401:
                throw new AuthException($body->error . ': ' . $body->error_description, $code);
            default:
                throw new \UnexpectedValueException("Call failed with code: {$code}.", $code);
        }
    }

    private function getUrl($endPoint = null)
    {
        return self::API_URI . $endPoint;
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
}