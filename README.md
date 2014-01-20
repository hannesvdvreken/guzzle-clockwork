# [Guzzle](http://docs.guzzlephp.org/en/latest/) plugin with [Clockwork](https://github.com/itsgoingd/clockwork)
[![Build Status](https://travis-ci.org/hannesvdvreken/guzzle-clockwork.png?branch=master)](https://travis-ci.org/hannesvdvreken/guzzle-clockwork)

Guzzle Plugin for logging to clockwork so you can view the requests timeline...

[!Developer tools timeline](https://dl.dropboxusercontent.com/s/2okdxq30qr1n8os/timeline.png?dl=1&token_hash=AAH3BzQL-ks_lotJBZ-6iZ9i1OYaX8T9pEbA0vY_KWqp2g "Developer tools timeline")

and logs in your browser's developer tools.

[!Developer tools logs](https://www.dropbox.com/s/ca1gydqgar1twq6/log.png "Developer tools logs")

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
