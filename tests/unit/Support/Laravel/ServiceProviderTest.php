<?php

use GuzzleHttp\Subscriber\Log\Support\Laravel\ServiceProvider;

class ServiceProviderTest extends PHPUnit_Framework_TestCase
{
    /**
     * Close mockery.
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * @test
     * Test the register method
     */
    public function register()
    {
        // Arrange
        $app = Mockery::mock('Illuminate\Foundation\Application');
        $clockwork = Mockery::mock('Clockwork\Clockwork');

        // Test bind closure
        $closure = Mockery::on(function ($closure) use ($app, $clockwork) {
            // Arrange
            $app->shouldReceive('make')->once()
              ->with('clockwork')->andReturn($clockwork);

            // Act
            $client = $closure($app);
            $listeners = $client->getEmitter()->listeners();

            // Assert
            $this->assertInstanceOf('GuzzleHttp\Client', $client);
            $this->assertNotEmpty($listeners);

            // Mandatory
            return true;
        });

        $app->shouldReceive('bind')->once()
          ->with('GuzzleHttp\Client', $closure);

        // Act
        $provider = new ServiceProvider($app);
        $provider->register();

        // Assert
    }
}
