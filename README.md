## Install

Via Composer

``` bash
$ composer require podcasthosting/podcast-client-spotify
```


## Intro

Create (add), get status, update url and remove (delete) podcasts on Spotify (DeliveryClient).

You can also fetch analytics data from a different Spotify API (AnalyticsClient).  

You´ll need to be a certified podcast provider (aggregator) to use these APIs.


## Documentation

DeliveryClient

``` php
$auth = new AuthClient($clientId, $clientSecret);
$token = $auth->getToken();

// Initiate client
$client = new Delivery\Client($token->access_token);

// Create podcast entry on Spotify
try {
    $res = $client->create('Example Feed', 'https://example.podcaster.de/feed.rss');
    if ($res instanceof Delivery\Result) {
        // Do something with SpotifyUri
        $spotifyUri = $res->getSpotifyUri(); // Result e.g. spotify:show:123
    }
} catch (AuthException $e) {
} catch (DomainException $e) {
} catch (DuplicateException $e) {
}

// Get status and info for podcast entry on Spotify
try {
    $res = $client->status($spotifyUri);
    if ($res instanceof Result) {
        // A description of the current status of the podcast.
        $statusDescription = $res->getStatusDescription();
        // A list of descriptive validation errors for the podcast
        $validationErrors = $res->getValidationErrors();
    }
} catch (AuthException $e) {
} catch (DomainException $e) {
} catch (NotFoundException $e) {
}

// Update uri for podcast entry on Spotify
try {
    $newUrl = 'https://example.podcaster.de/newfeed.rss';
    $res = $client->status($spotifyUri, $newUrl);
    if (true === $res) {
      // Is updated
    }
} catch (AuthException $e) {
} catch (DomainException $e) {
} catch (NotFoundException $e) {
}

// Remove podcast entry
try {
    $res = $client->remove($spotifyUri);
    if (true === $res) {
      // Is removed
    }    
} catch (AuthException $e) {
} catch (DomainException $e) {
} catch (NotFoundException $e) {
}
```


AnalyticsClient

``` php
$auth = new AuthClient($clientId, $clientSecret);
$token = $auth->getToken();
// Initiate client
$client = new Analytics\Client($token->access_token, $clientId);
// Fetch analytics data from Spotify
try {
    $res = $analyticsClient->get((new \DateTime())->setDate(\DateInterval::createfromdatestring('-1 day')));
    if ($res instanceof Analytics\Result) {
        // Iterate over JSON objects (from json_decode)
        foreach($res->getDecoded() as $jsonObject) {
        }
        // Iterate over raw JSON strings
        foreach($res->getRaw() as $sJson) {
        }
    }     
} catch (AuthException $e) {
}
```


## Testing

Not yet available. Sorry.

``` bash
$ composer test
```


## Contributing

You´ll find the [source on Github](https://github.com/podcasthosting/podcast-client-spotify). Feel free to submit patches. 

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
