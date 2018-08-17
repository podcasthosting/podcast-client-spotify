## Install

Via Composer

``` bash
$ composer require podcasthosting/podcast-client-spotify
```


## Intro

Create (add) and remove (delete) podcasts on Spotify (DeliveryClient). 

You´ll need to be a certified podcast provider to use this API.


## Documentation

DeliveryClient

``` php
$token = "YourVeryPrivateAuthToken";
// Initiate client
$client = new DeliveryClient($token);
// Create podcast entry on Spotify
try {
    $res = $client->create('Example Feed', 'https://example.podcaster.de/feed.rss');
    if ($res instanceof Result) {
        // Do something with SpotifyUri
        $spotifyUri = $res->getSpotifyUri(); // Result e.g. spotify:show:123
    }
} catch (AuthException $e) {
} catch (DuplicateException $e) {
} catch (DomainException $e) {
}
// Remove podcast entry
$res = $client->remove($spotifyUri);
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
