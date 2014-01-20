# OAuth service classes with Guzzle
[![Build Status](https://travis-ci.org/hannesvdvreken/guzzle-clockwork.png?branch=master)](https://travis-ci.org/hannesvdvreken/guzzle-clockwork)

## Usage

```php
// First you need a Guzzle HTTP Client
$client = new Guzzle\Http\Client;

// Then you need a Clockwork object
$clockwork = new Clockwork\Clockwork;

// Create the Guzzle plugin
$plugin = new Guzzle\Plugin\Log\Clockwork($clockwork);

// Add it as a subscriber
$client->addSubscriber($plugin);
```

And you are done!

### Laravel 4

If you are using Laravel 4, create your own service provider to add the plugin to each Guzzle Client 
and be sure to grab the configured clockwork object from the IoC container:

```php
$clockwork = $this->app->make('clockwork');
```

## Contributing
Feel free to make a pull request. Please try to be as 
[PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) 
compliant as possible.

## License

MIT