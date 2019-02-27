<?php
/**
 * User: Fabio Bacigalupo
 * Date: 11.07.18
 * Time: 16:36
 */

namespace podcasthosting\PodcastClientSpotify\Exceptions;


use Throwable;

class NotFoundException extends \Exception
{
    public function __construct(string $message = "Podcast not found.", int $code = 404, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}