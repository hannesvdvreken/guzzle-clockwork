<?php
namespace GuzzleHttp\Subscriber\Log;

use Clockwork\Clockwork;
use GuzzleHttp\Event\AbstractTransferEvent;
use GuzzleHttp\Event\BeforeEvent;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use GuzzleHttp\Event\HeadersEvent;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Message\Request;

class ClockworkSubscriber implements SubscriberInterface
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
     * @return  array
     */
    public function getEvents()
    {
        return [
            'before' => ['onBefore'],
            'complete' => ['onComplete'],
            'headers' => ['onHeaders'],
            'error' => ['onError'],
        ];
    }

    /**
     * About to send request.
     *
     * @param  ErrorEvent  $event
     */
    public function onError(ErrorEvent $event)
    {
        $this->clockwork->error($event->getException()->getMessage());
    }

    /**
     * About to send request.
     *
     * @param  Event  $event
     */
    public function onBefore(BeforeEvent $event)
    {
        // Start an event for this request.
        $this->startEvent($event->getRequest());
    }

    /**
     * Completed a full HTTP transaction.
     *
     * @param  CompleteEvent  $event
     */
    public function onComplete(CompleteEvent $event)
    {
        // Grab the request object.
        $request = $event->getRequest();
        $id = $this->createRequestID($request);

        $name = "guzzle.request.$id";
        $timeline = $this->clockwork->getTimeline()->toArray();

        // Add it if it didn't exist already.
        if (! array_key_exists($name, $timeline)) {
            $this->startEvent($request);
        }

        // Stop the timer.
        $this->clockwork->endEvent($name);
    }

    /**
     * Received reponse headers.
     *
     * @param  HeadersEvent $event
     */
    public function onHeaders(HeadersEvent $event)
    {
        $response = $event->getResponse();
        $request = $event->getRequest();
        
        $this->clockwork->info(sprintf(
            '%s %s returned %s',
            $request->getMethod(),
            $request->getUrl(),
            $response->getStatusCode()
        ));
    }

    /**
     * Log a request start
     *
     * @param  GuzzleHttp\Message\Request $request
     */
    protected function startEvent($request)
    {
        // Get unique identifier.
        $id = $this->createRequestID($request);

        // Start the event.
        $this->clockwork->startEvent(
            "guzzle.request.$id",
            sprintf(
                'Performing a %s request to %s.',
                $request->getMethod(),
                $request->getHeader('Host')
            )
        );
    }

    /**
     * Create unique id
     *
     * @param  GuzzleHttp\Message\Request $request
     * @return string
     */
    protected function createRequestID(Request $request)
    {
        return spl_object_hash($request);
    }
}
