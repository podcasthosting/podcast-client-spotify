<?php
/**
 * User: Fabio Bacigalupo
 * Date: 11.07.18
 * Time: 16:44
 */

namespace podcasthosting\PodcastClientSpotify\Delivery;


class Result
{
    private $spotifyUri;

    /**
     * Result constructor.
     *
     * @param $spotifyUri|null
     */
    public function __construct($spotifyUri = null)
    {
        if (!is_null($spotifyUri)) {
            $this->spotifyUri = $spotifyUri;
        }
    }

    /**
     * @return String
     */
    public function getSpotifyUri(): String
    {
        return $this->spotifyUri;
    }

    /**
     * @param String $spotifyUri
     */
    public function setSpotifyUri($spotifyUri): void
    {
        $this->spotifyUri = $spotifyUri;
    }
}