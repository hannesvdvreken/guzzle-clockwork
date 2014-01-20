<?php

class ClockworkPluginTest extends PHPUnit_Framework_TestCase
{
    /**
     * Close mockery.
     */
    public function tearDown()
    {
        Mockery::close();
    }

    /**
     * Testing if all events are subscribed upon.
     */
    public function test_get_subscribed_events()
    {
        // Arrange
        $expected = array(
            'request.before_send' => 'onRequestBeforeSend',
            'request.complete'    => 'onRequestComplete',
            'request.success'     => 'onRequestSuccess',
            'request.error'       => 'onRequestError',
            'request.exception'   => 'onRequestException',
        );

        // Act
        $result = Guzzle\Plugin\Log\ClockworkPlugin::getSubscribedEvents();

        // Assert
        $this->assertEquals($expected, $result);
    }

    /**
     * Test the onRequestException handler but with a basic BadResponseException
     */
    public function test_on_request_exception_not_curl_exception()
    {
        // Arrange
        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('Guzzle\Common\Event');
        $exception = Mockery::mock('alias:Guzzle\Http\Exception\BadResponseException');
        $event->shouldReceive('offsetGet')->once()
            ->with('exception')->andReturn($exception);

        // Act
        $plugin = new Guzzle\Plugin\Log\ClockworkPlugin($clockwork);
        $plugin->onRequestException($event);

        // Assert
    }

    /**
     * Same test function but now it is a CurlException
     */
    public function test_on_request_exception_on_curl_exception()
    {
        // Arrange
        $message = '[curl] 7: couldn\'t connect to host [url] https://example.com/';
        
        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('Guzzle\Common\Event');
        $exception = Mockery::mock('alias:Guzzle\Http\Exception\CurlException');

        $event->shouldReceive('offsetGet')->once()
            ->with('exception')->andReturn($exception);
        $exception->shouldReceive('getMessage')->once()
            ->andReturn($message);
        $clockwork->shouldReceive('critical')->once()
            ->with($message);

        // Act
        $plugin = new Guzzle\Plugin\Log\ClockworkPlugin($clockwork);
        $plugin->onRequestException($event);

        // Assert
    }

    /**
     * Test on request success method
     */
    public function test_on_request_success()
    {
        // Arrange
        $message = ($method = 'GET') .' '. ($url = 'http://example.com') .' returned '. ($status = 200);
        
        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('Guzzle\Common\Event');
        $request = Mockery::mock('Guzzle\Http\Message\Request');
        $response = Mockery::mock('Guzzle\Http\Message\Response');

        $event->shouldReceive('offsetGet')->once()
            ->with('request')->andReturn($request);
        $event->shouldReceive('offsetGet')->once()
            ->with('response')->andReturn($response);
        $request->shouldReceive('getMethod')->once()
            ->andReturn($method);
        $request->shouldReceive('getUrl')->once()
            ->andReturn($url);
        $response->shouldReceive('getStatusCode')->once()
            ->andReturn($status);
        $clockwork->shouldReceive('info')->once()
            ->with($message);

        // Act
        $plugin = new Guzzle\Plugin\Log\ClockworkPlugin($clockwork);
        $plugin->onRequestSuccess($event);

        // Assert
    }

    /**
     * Test on request error method
     */
    public function test_on_request_error()
    {
        // Arrange
        $message = ($method = 'POST') .' '. ($url = 'http://example.com/404') .' returned '. ($status = 404);
        
        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('Guzzle\Common\Event');
        $request = Mockery::mock('Guzzle\Http\Message\Request');
        $response = Mockery::mock('Guzzle\Http\Message\Response');

        $event->shouldReceive('offsetGet')->once()
            ->with('request')->andReturn($request);
        $event->shouldReceive('offsetGet')->once()
            ->with('response')->andReturn($response);
        $request->shouldReceive('getMethod')->once()
            ->andReturn($method);
        $request->shouldReceive('getUrl')->once()
            ->andReturn($url);
        $response->shouldReceive('getStatusCode')->once()
            ->andReturn($status);
        $clockwork->shouldReceive('error')->once()
            ->with($message);

        // Act
        $plugin = new Guzzle\Plugin\Log\ClockworkPlugin($clockwork);
        $plugin->onRequestError($event);

        // Assert
    }

    /**
     * Test onRequestBeforeSend event handler
     */
    public function test_on_request_before_send()
    {
        // Arrange
        $method = 'POST';
        $host = 'example.com';

        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('Guzzle\Common\Event');
        $request = Mockery::mock('Guzzle\Http\Message\Request');
        $event->shouldReceive('offsetGet')->once()
            ->with('request')->andReturn($request);
        $request->shouldReceive('getMethod')->once()
            ->andReturn($method);
        $request->shouldReceive('getHeader')->once()
            ->with('Host')->andReturn($host);
        $clockwork->shouldReceive('startEvent')->once()
            ->with('guzzle.request', 'Performing a '. $method .' request to '. $host .'.');

        // Act
        $plugin = new Guzzle\Plugin\Log\ClockworkPlugin($clockwork);
        $plugin->onRequestBeforeSend($event);

        // Assert
    }

    /**
     * Test event handler for onRequestComplete
     */
    public function test_on_request_complete()
    {
        // Arrange
        $clockwork = Mockery::mock('Clockwork\Clockwork');
        $event = Mockery::mock('Guzzle\Common\Event');
        $clockwork->shouldReceive('endEvent')->once()
            ->with('guzzle.request');

        // Act
        $plugin = new Guzzle\Plugin\Log\ClockworkPlugin($clockwork);
        $plugin->onRequestComplete($event);

        // Assert
    }
}
