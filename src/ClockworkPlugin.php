<?php
namespace Guzzle\Plugin\Log;

use Clockwork\Clockwork;
use Guzzle\Common\Event;
use Guzzle\Http\Exception\CurlException;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ClockworkPlugin implements EventSubscriberInterface
{
    /**
     * @var  Clockwork
     */
    protected $clockwork;
    
    /**
     * Public constructor
     *
     * @param Clockwork $clockwork
     */
    public function __construct(Clockwork $clockwork)
    {
        $this->clockwork = $clockwork;
    }

    /**
     * Returns array of events to which the plugin subscribes.
     * 
     * @static
     * @return  array
     */
    public static function getSubscribedEvents()
    {
        return array(
            'request.before_send' => 'onRequestBeforeSend',
            'request.complete'    => 'onRequestComplete',
            'request.success'     => 'onRequestSuccess',
            'request.error'       => 'onRequestError',
            'request.exception'   => 'onRequestException',
        );
    }

    /**
     * About to send request.
     *
     * @param  Event  $event
     */
    public function onRequestSuccess(Event $event)
    {
       $this->log('info', $event);
    }

    /**
     * About to send request.
     *
     * @param  Event  $event
     */
    public function onRequestError(Event $event)
    {
        $this->log('error', $event);
    }

    /**
     * About to send request.
     *
     * @param  Event  $event
     */
    public function onRequestException(Event $event)
    {
        $exception = $event['exception'];

        if ($exception instanceof CurlException)
        {
            $this->clockwork->critical($exception->getMessage());
        }
    }

    /**
     * About to send request.
     *
     * @param  Event  $event
     */
    public function onRequestBeforeSend(Event $event)
    {
        // Start an event for this request.
        $this->startEvent($event['request']);
    }

    /**
     * Completed a full HTTP transaction.
     * 
     * @param  Event  $event
     */
    public function onRequestComplete(Event $event)
    {
        // Grab the request object.
        $request = $event['request'];
        $id = md5($request);

        // Add it if it didn't exist already.
        if ( ! array_key_exists('guzzle.request.'. $id, $this->clockwork->getTimeline()->toArray())) {
            $this->startEvent($request);
        }
        
        // Stop the timer.
        $this->clockwork->endEvent('guzzle.request.'. $id);
    }

    /**
     * About to send request.
     *
     * @param  string $level
     * @param  Event  $event
     */
    protected function log($level, $event)
    {
        $request  = $event['request'];
        $response = $event['response'];

        $this->clockwork->$level(sprintf(
            '%s %s returned %s',
            $request->getMethod(), $request->getUrl(), $response->getStatusCode()
        ));
    }

    /**
     * Log a request start
     *
     * @param  Guzzle\Http\Message\Request $request
     */
    protected function startEvent($request)
    {
        // Get unique identifier.
        $id = md5($request);

        // Start the event.
        $this->clockwork->startEvent('guzzle.request.'. $id,
            sprintf('Performing a %s request to %s.', $request->getMethod(), $request->getHeader('Host'))
        );
    }
}
