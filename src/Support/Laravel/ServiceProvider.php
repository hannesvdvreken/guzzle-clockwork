<?php

namespace GuzzleHttp\Profiling\Clockwork\Support\Laravel;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware as GuzzleMiddleware;
use GuzzleHttp\Profiling\Clockwork\Profiler;
use GuzzleHttp\Profiling\Middleware;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use Psr\Http\Client\ClientInterface as PsrClientInterface;
use Psr\Log\LoggerInterface;

class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * @return array
     */
    public function provides(): array
    {
        return [
            Client::class,
            ClientInterface::class,
            HandlerStack::class,
        ];
    }

    /**
     * Register method.
     */
    public function register(): void
    {
        // Configuring all guzzle clients.
        $this->app->bind(ClientInterface::class, function(): PsrClientInterface {
            // Guzzle client
            return new Client(['handler' => $this->app->make(HandlerStack::class)]);
        });

        $this->app->alias(ClientInterface::class, Client::class);
        $this->app->alias(ClientInterface::class, PsrClientInterface::class);

        // Bind if needed.
        $this->app->bindIf(HandlerStack::class, function(): HandlerStack {
            return HandlerStack::create();
        });

        // If resolved, by this SP or another, add some layers.
        $this->app->resolving(HandlerStack::class, function(HandlerStack $stack): void {
            /** @var \Clockwork\Clockwork $clockwork */
            $clockwork = $this->app->make('clockwork');

            $stack->push(new Middleware(new Profiler($clockwork->getTimeline())));

            /** @var \GuzzleHttp\MessageFormatter $formatter */
            $formatter = $this->app->make(MessageFormatter::class);
            $stack->unshift(GuzzleMiddleware::log($clockwork->getLog(), $formatter));

            // Also log to the default PSR logger.
            if ($this->app->bound(LoggerInterface::class)) {
                $logger = $this->app->make(LoggerInterface::class);

                // Don't log to the same logger twice.
                if ($logger === $clockwork->getLog()) {
                    return;
                }

                // Push the middleware on the stack.
                $stack->unshift(GuzzleMiddleware::log($logger, $formatter));
            }
        });
    }
}
