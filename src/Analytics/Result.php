<?php
/**
 * User: Fabio Bacigalupo
 * Date: 11.07.18
 * Time: 16:44
 */

namespace podcasthosting\PodcastClientSpotify\Analytics;

class Result
{
    private $result;

    private $decoded = [];

    private $raw = [];

    /**
     * Result constructor.
     *
     */
    public function __construct(String $result)
    {
        $this->result = $result;
    }

    public function getDecoded(): array
    {
        if (count($this->decoded) > 0) {
            return $this->decoded;
        }

        $a = [];
        foreach (explode(PHP_EOL, $this->result) as $line) {
            $a[] = json_decode($line);
        }

        $this->decoded = $a;

        return $a;
    }

    public function getRaw(): array
    {
        if (count($this->raw) > 0) {
            return $this->raw;
        }

        $a = [];
        foreach (explode(PHP_EOL, $this->result) as $line) {
            $a[] = $line;
        }

        $this->raw = $a;

        return $a;
    }

    public function get(): String
    {
        return $this->result;
    }
}