<?php
namespace GuzzleHttp\Subscriber\Log\Support\Laravel;

use GuzzleHttp\Subscriber\Log\ClockworkSubscriber;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

class ServiceProvider extends BaseServiceProvider
{
    /**
     *  Register method
     *
     * @return  void
     */
    public function register()
    {
        // Configuring all guzzle clients.
        $this->app->bind('GuzzleHttp\Client', function ($app) {
            // Guzzle client
            $client = new Client;

            // The Clockwork object from the application container.
            $clockwork = $app->make('clockwork');

            // Create the Guzzle plugin.
            $plugin = new ClockworkSubscriber($clockwork);

            // Add it as a subscriber.
            $client->getEmitter()->attach($plugin);

            // Return the client.
            return $client;
        });
    }
}
