# [Guzzle](http://docs.guzzlephp.org/en/latest/) middleware for [Clockwork](https://github.com/itsgoingd/clockwork) logging

[![Build Status](http://img.shields.io/travis/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](https://travis-ci.org/hannesvdvreken/guzzle-clockwork)
[![Latest Stable Version](http://img.shields.io/packagist/v/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](https://packagist.org/packages/hannesvdvreken/guzzle-clockwork)
[![Code Quality](https://img.shields.io/scrutinizer/g/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](https://scrutinizer-ci.com/g/hannesvdvreken/guzzle-clockwork/)
[![Code Coverage](https://img.shields.io/scrutinizer/coverage/g/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](https://scrutinizer-ci.com/g/hannesvdvreken/guzzle-clockwork/)
[![Total Downloads](http://img.shields.io/packagist/dt/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](https://packagist.org/packages/hannesvdvreken/guzzle-clockwork)
[![License](http://img.shields.io/packagist/l/hannesvdvreken/guzzle-clockwork.svg?style=flat-square)](#license)

Guzzle middleware for logging requests clockwork's timeline so you can view the timeline in your browser's developer tools.

![Developer tools timeline](https://dl.dropboxusercontent.com/s/2okdxq30qr1n8os/timeline.png?dl=1&token_hash=AAH3BzQL-ks_lotJBZ-6iZ9i1OYaX8T9pEbA0vY_KWqp2g "Developer tools timeline")

![Developer tools logs](https://dl.dropboxusercontent.com/s/ca1gydqgar1twq6/log.png?dl=1&token_hash=AAEwY0bcesfhdG_da1_sTkyQ__GlZ9BQl6FRXZgzXJky_A "Developer tools logs")

## Usage

```php
// First you need a Clockwork object
$clockwork = new Clockwork\Clockwork();

// Create the Guzzle middleware
$middleware = new GuzzleHttp\Middleware\Log\Clockwork($clockwork);

// Then you need to add it to the Guzzle HandlerStack
$stack = GuzzleHttp\HandlerStack::create();

$stack->unshift($middleware);
```

And you are done!

### Laravel

If you are using Laravel, use the included service providers to add
the subscriber to every Guzzle Client.

```php
'providers' => [
    ...
    'Clockwork\Support\Laravel\ClockworkServiceProvider',
    'GuzzleHttp\Profiling\Clockwork\Support\Laravel\ServiceProvider',
]
```

Be sure to create every client (type hint with `GuzzleHttp\ClientInterface` or `GuzzleHttp\Client`) via the IoC container.

## Guzzle v4 and v5

Versions `0.2.0` and up until `1.0.0` (exclusively) are all compatible with Guzzle v4 and v5. To develop for these versions of Guzzle, use the `guzzle4-5` [branch](https://github.com/hannesvdvreken/guzzle-clockwork/tree/guzzle4-5).

Use

## Guzzle v3

If you want to continue to work with the old Guzzle v3 (`Guzzle\Http\Client` instead of `GuzzleHttp\Client`) ClockworkPlugin
then you might want to install the `0.1.*` releases. Pull request with Guzzle v3 compatibility should be made against the `guzzle3` [branch](https://github.com/hannesvdvreken/guzzle-clockwork/tree/guzzle3). Install the latest guzzle v3 compatible version with `0.1.*` or `dev-guzzle3`.

## Contributing

Feel free to make a pull request. Please try to be as
[PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
compliant as possible. Fix Code Style quickly by running `vendor/bin/php-cs-fixer fix`. Give a good description of what is supposed to be added/changed/removed/fixed.

### Testing

To test your code before pushing, run the unit test suite.

```bash
vendor/bin/phpunit
```

## License

[MIT](LICENSE)
