## Install

Via Composer

``` bash
$ composer require podcasterhosting/podcast-client-spotify
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
$res = $client->create('Example Feed', 'https://example.podcaster.de/feed.rss');
// Remove podcast entry
$res = $client->remove('spotify:show:123');
```


## Testing

Not yet available. Sorry.

``` bash
$ composer test
```


## Contributing

You find the [source on Github](https://github.com/podcasthosting/podcast-client-spotify). Feel free to submit patches. 

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.
