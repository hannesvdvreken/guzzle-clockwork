<?php

use GuzzleHttp\Subscriber\Log\ClockworkSubscriber;

class ClockworkSubscriberTest extends PHPUnit_Framework_TestCase
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
     * Testing if all events are subscribed upon.
     */
    public function get_events()
    {
        // Arrange
        $clockwork = Mockery::mock('Clockwork\Clockwork');

        $expected = [
            'before' => ['onBefore'],
            'complete' => ['onComplete'],
            'error' => ['onError'],
            'headers' => ['onHeaders'],
        ];

        $subscriber = new ClockworkSubscriber($clockwork);

        // Act
        $result = $subscriber->getEvents();

        // Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * @test
     * Test on error method
     */
    public function on_error()
    {
        // Arrange
        $message = '[curl] 7: couldn\'t connect to host [url] https://example.com/';

        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('GuzzleHttp\Event\ErrorEvent');

        $event->shouldReceive('getResponse')->once()
            ->andReturn(null);
        $event->shouldReceive('getException->getMessage')->once()
            ->andReturn($message);
        $clockwork->shouldReceive('error')->once()
            ->with($message);

        // Act
        $subscriber = new ClockworkSubscriber($clockwork);
        $subscriber->onError($event);

        // Assert
    }

    /**
     * @test
     * Test on error method with event having response
     */
    public function on_error_with_response()
    {
        // Arrange
        $method = 'PATCH';
        $url = 'api.justyo.co';
        $status = '403';
        $message = sprintf('%s %s returned %s', $method, $url, $status);

        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('GuzzleHttp\Event\ErrorEvent');
        $response = Mockery::mock('GuzzleHttp\Message\Response');
        $request = Mockery::mock('GuzzleHttp\Message\Request');

        $event->shouldReceive('getResponse')->once()
            ->andReturn($response);
        $event->shouldReceive('getRequest')->once()
            ->andReturn($request);
        $request->shouldReceive('getUrl')->once()
            ->andReturn($url);
        $request->shouldReceive('getMethod')->once()
            ->andReturn($method);
        $response->shouldReceive('getStatusCode')->once()
            ->andReturn($status);
        $clockwork->shouldReceive('error')->once()
            ->with($message);

        // Act
        $subscriber = new ClockworkSubscriber($clockwork);
        $subscriber->onError($event);

        // Assert
    }

    /**
     * @test
     * Test on complete method
     */
    public function on_complete()
    {
        // Arrange
        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('GuzzleHttp\Event\CompleteEvent');
        $request = Mockery::mock('GuzzleHttp\Message\Request');

        $id = spl_object_hash($request);

        $event->shouldReceive('getRequest')->once()
            ->andReturn($request);
        $clockwork->shouldReceive('getTimeline->toArray')->once()
            ->andReturn(["guzzle.request.$id" => true]);
        $clockwork->shouldReceive('endEvent')->once()
            ->with("guzzle.request.$id");

        // Act
        $subscriber = new ClockworkSubscriber($clockwork);
        $subscriber->onComplete($event);

        // Assert
    }

    /**
     * @test
     * Test on complete method when the before method isn't called
     */
    public function on_complete_before_before_event()
    {
        // Arrange
        $method = 'PUT';
        $host = 'example.com';
        $description = "Performing a $method request to $host.";

        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('GuzzleHttp\Event\CompleteEvent');
        $request = Mockery::mock('GuzzleHttp\Message\Request');

        $id = spl_object_hash($request);

        $event->shouldReceive('getRequest')->once()
            ->andReturn($request);
        $request->shouldReceive('getMethod')->once()
            ->andReturn($method);
        $request->shouldReceive('getHeader')->once()
            ->with('Host')->andReturn($host);
        $clockwork->shouldReceive('getTimeline->toArray')->once()
            ->andReturn([]);
        $clockwork->shouldReceive('startEvent')->once()
            ->with("guzzle.request.$id", $description);
        $clockwork->shouldReceive('endEvent')->once()
            ->with("guzzle.request.$id");

        // Act
        $subscriber = new ClockworkSubscriber($clockwork);
        $subscriber->onComplete($event);

        // Assert
    }

    /**
     * @test
     * Test on headers method
     */
    public function on_headers()
    {
        // Arrange
        $method = 'GET';
        $url = 'http://example.com';
        $status = 200;

        $message = sprintf('%s %s returned %s', $method, $url, $status);

        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('GuzzleHttp\Event\HeadersEvent');
        $request = Mockery::mock('GuzzleHttp\Message\Request');
        $response = Mockery::mock('GuzzleHttp\Message\Response');

        $event->shouldReceive('getRequest')->once()
            ->andReturn($request);
        $event->shouldReceive('getResponse')->once()
            ->andReturn($response);
        $request->shouldReceive('getMethod')->once()
            ->andReturn($method);
        $request->shouldReceive('getUrl')->once()
            ->andReturn($url);
        $response->shouldReceive('getStatusCode')->once()
            ->andReturn($status);
        $clockwork->shouldReceive('info')->once()
            ->with($message);

        // Act
        $subscriber = new ClockworkSubscriber($clockwork);
        $subscriber->onHeaders($event);

        // Assert
    }

    /**
     * @test
     * Test on before method
     */
    public function on_before()
    {
        // Arrange
        $method = 'POST';
        $host = 'example.com';
        $description = "Performing a $method request to $host.";

        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('GuzzleHttp\Event\BeforeEvent');
        $request = Mockery::mock('GuzzleHttp\Message\Request');

        $id = spl_object_hash($request);

        $event->shouldReceive('getRequest')->once()
            ->andReturn($request);
        $request->shouldReceive('getMethod')->once()
            ->andReturn($method);
        $request->shouldReceive('getHeader')->once()
            ->with('Host')->andReturn($host);
        $clockwork->shouldReceive('startEvent')->once()
            ->with('guzzle.request.'. $id, $description);

        // Act
        $subscriber = new ClockworkSubscriber($clockwork);
        $subscriber->onBefore($event);

        // Assert
    }
}
