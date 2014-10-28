# [Guzzle](http://docs.guzzlephp.org/en/latest/) subscriber for [Clockwork](https://github.com/itsgoingd/clockwork) logging
[![Build Status](http://img.shields.io/travis/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](https://travis-ci.org/hannesvdvreken/guzzle-clockwork)
[![Latest Stable Version](http://img.shields.io/packagist/v/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](https://packagist.org/packages/hannesvdvreken/guzzle-clockwork)
[![Total Downloads](http://img.shields.io/packagist/dt/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](https://packagist.org/packages/hannesvdvreken/guzzle-clockwork)
[![Coverage Status](https://img.shields.io/coveralls/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](https://coveralls.io/r/hannesvdvreken/guzzle-clockwork?branch=master)
[![License](http://img.shields.io/packagist/l/hannesvdvreken/guzzle-clockworkh.svg?style=flat-square)](#license)

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
$client->getEmitter()->attach($subscriber);
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

## Guzzle v3

If you want to continue to work with the old Guzzle v3 (`Guzzle\Http\Client` instead of `GuzzleHttp\Client`) ClockworkPlugin
then you might want to install the `0.1.*` releases. Pull request with Guzzle v3 compatibility should be made against the `guzzle3` [branch](https://github.com/hannesvdvreken/guzzle-clockwork/tree/guzzle3). Install the latest guzzle v3 compatible version with `0.1.*` or `dev-guzzle3`.

Versions `0.2.0` and up are all compatible with Guzzle v4 and v5.

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
