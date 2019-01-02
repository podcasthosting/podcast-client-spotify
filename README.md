## Install

Via Composer

``` bash
$ composer require podcasthosting/podcast-client-spotify
```


## Intro

Create (add) and remove (delete) podcasts on Spotify (DeliveryClient).

You can also fetch analytics data from another API (AnalyticsClient).  

You´ll need to be a certified podcast provider to use these APIs.


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
}
// Remove podcast entry
$res = $client->remove($spotifyUri);
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
