# [Guzzle](http://docs.guzzlephp.org/en/latest/) subscriber for [Clockwork](https://github.com/itsgoingd/clockwork) logging
[![Build Status](https://travis-ci.org/hannesvdvreken/guzzle-clockwork.png?branch=master)](https://travis-ci.org/hannesvdvreken/guzzle-clockwork) [![Latest Stable Version](https://poser.pugx.org/hannesvdvreken/guzzle-clockwork/v/stable.png)](https://packagist.org/packages/hannesvdvreken/guzzle-clockwork) [![Total Downloads](https://poser.pugx.org/hannesvdvreken/guzzle-clockwork/downloads.png)](https://packagist.org/packages/hannesvdvreken/guzzle-clockwork)

Guzzle Subscriber for logging to clockwork so you can view the requests timeline and logs in your browser's developer tools.

![Developer tools timeline](https://dl.dropboxusercontent.com/s/2okdxq30qr1n8os/timeline.png?dl=1&token_hash=AAH3BzQL-ks_lotJBZ-6iZ9i1OYaX8T9pEbA0vY_KWqp2g "Developer tools timeline")

![Developer tools logs](https://dl.dropboxusercontent.com/s/ca1gydqgar1twq6/log.png?dl=1&token_hash=AAEwY0bcesfhdG_da1_sTkyQ__GlZ9BQl6FRXZgzXJky_A "Developer tools logs")

## Usage

```php
// First you need a Guzzle HTTP Client
$client = new GuzzleHttp\Client;

// Then you need a Clockwork object
$clockwork = new Clockwork\Clockwork;

// Create the Guzzle subscriber
$subscriber = new GuzzleHttp\Subscriber\Log\ClockworkSubscriber($clockwork);

// Add it as a subscriber
$client->attach($subscriber);
```

And you are done!

### Laravel 4

If you are using Laravel 4, use the included service providers to add
the subscriber to every Guzzle Client.

```php
'providers' => [
    ...
    'Clockwork\Support\Laravel\ClockworkServiceProvider',
    'GuzzleHttp\Subscriber\Log\Support\Laravel\ServiceProvider',  
]
```

Be sure to create every client via the auto-resolving application container:

```php
$client = App::make('GuzzleHttp\Client');
```

## Guzzle version 3
The old code that works with Guzzle version 3 can be found under the
[guzzle3 branch](https://github.com/hannesvdvreken/guzzle-clockwork/tree/guzzle3).

You can choose to install a `0.1.*` version of this package and log every request
made with guzzle v3 clients, or install a `0.2` and up version for every request
made with guzzle v4 clients.

## Contributing
Feel free to make a pull request. Please try to be as
[PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
compliant as possible.

### Testing

To test your code before pushing, run the unit test suite.

```bash
phpunit
```

## License

MIT
