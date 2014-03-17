<?php
namespace Guzzle\Plugin\Log\Support\Laravel;

use Guzzle\Plugin\Log\ClockworkPlugin;
use Guzzle\Http\Client;

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
        $this->app->bind('Guzzle\Http\Client', function($app)
        {
            // Guzzle client
            $client = new Client;

            // The Clockwork object from the application container.
            $clockwork = $this->app->make('clockwork');

            // Create the Guzzle plugin.
            $plugin = new ClockworkPlugin($clockwork);

            // Add it as a subscriber.
            $client->addSubscriber($plugin);

            // Return the client.
            return $client;
        });
    }
}