<?php
/**
 * User: Fabio Bacigalupo
 * Date: 10.07.18
 * Time: 14:34
 */

namespace podcasterhosting;

use Buzz\Browser;
use Buzz\Client\Curl;
use Http\Client\HttpClient;

class DeliveryClient
{
    /**
     * URI to work with
     */
    const API_URI = 'https://podcast-delivery.spotify.com/podcastapi';

    /**
     *
     */
    const API_ENDPOINT = '/rss-feeds';

    /**
     * @var String
     */
    private $token;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @var string
     */
    private $version = 'v1';

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
    public function __construct(String $token, HttpClient $httpClient = null)
    {
        $this->token = $token;

        if (!is_null($httpClient)) {
            $this->httpClient = $httpClient;
        } else {
            $this->httpClient = new Browser(new Curl());
        }
    }

    /**
     * Used to create a feed that will start being ingested
     * and will be available in the Spotify client as soon as we index it,
     * that can take at most 24 hours.
     *
     * @param String $name Is an internal name used for book-keeping and doesn’t represent the name that will be
    shown in the spotify client.
     * @param String $rssFeed Needs to use http(s) protocol and be publicly accessible. This is the identifier for this
    podcast.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function create($name, $rssFeed)
    {
        $body = json_encode([
            'name' => $name,
            'url' => $rssFeed,
        ]);

        $ret = $this->httpClient->post($this->getUrl(), $this->getHeaders(), $body);

        return $ret;
    }

    /**
     * Makes a podcast inaccessible on spotify and also stops any updates on it.
     *
     * @param String $spotifyUri Returned when podcast is created (added), e.g. "spotify:show:123"
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function remove($spotifyUri)
    {
        $ret = $this->httpClient->delete($this->getUrl($spotifyUri));

        return $ret;
    }

    private function getUrl($attach = null)
    {
        return self::API_URI
            . DIRECTORY_SEPARATOR . $this->getVersion()
            . DIRECTORY_SEPARATOR . self::API_ENDPOINT
            . DIRECTORY_SEPARATOR . $attach
            . '?token=' . $this->getToken();
    }

    /**
     * @return string
     */
    public function getVersion(): string
    {
        return $this->version;
    }

    /**
     * @param string $version
     */
    public function setVersion(string $version): void
    {
        $this->version = $version;
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