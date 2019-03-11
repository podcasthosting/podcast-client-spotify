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
    private $statusCode;
    private $statusDescription;
    private $validationErrors = [];

    /**
     * Result constructor.
     *
     * @param null $spotifyUri
     * @param null $statusCode
     * @param null $statusDescription
     * @param null $validationErrors
     */
    public function __construct($spotifyUri = null, $statusCode = null, $statusDescription = null,
                                $validationErrors = null)
    {
        if (!is_null($spotifyUri)) {
            $this->spotifyUri = $spotifyUri;
        }

        if (!is_null($statusCode)) {
            $this->statusCode = $statusCode;
        }

        if (!is_null($statusDescription)) {
            $this->statusDescription = $statusDescription;
        }

        if (!is_null($validationErrors)) {
            if (is_array($validationErrors)) {
                $errors = [];
                foreach($validationErrors as $validationError) {
                    $errors[] = $this->decodeError($validationError);
                }
            } else {
                $errors[] = $this->decodeError($validationErrors);
            }
            $this->validationErrors = $errors;
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

    /**
     * @return null
     */
    public function getStatusCode()
    {
        return $this->statusCode;
    }

    /**
     * @return null
     */
    public function getStatusDescription()
    {
        return $this->statusDescription;
    }

    /**
     * @return array|mixed
     */
    public function getValidationErrors()
    {
        return $this->validationErrors;
    }

    private function decodeError($validationError)
    {
        $error = null;

        if ($validationError instanceof \stdClass) {
            $error = $validationError->message ?? $validationError;
        } else {
            $error = json_decode($validationError);
        }

        return $error;
    }
}