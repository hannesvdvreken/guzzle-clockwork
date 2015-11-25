<?php
namespace GuzzleHttp\Profiling\Clockwork;

use Clockwork\Request\Timeline;
use GuzzleHttp\Profiling\DescriptionMaker;
use GuzzleHttp\Profiling\Profiler as ProfilerContract;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Profiler implements ProfilerContract
{
    use DescriptionMaker;

    /**
     * @var \Clockwork\Request\Timeline
     */
    private $timeline;

    /**
     * Public constructor.
     *
     * @param \Clockwork\Request\Timeline $timeline
     */
    public function __construct(Timeline $timeline)
    {
        $this->timeline = $timeline;
    }

    /**
     * @param float $start
     * @param float $end
     * @param \Psr\Http\Message\RequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function add($start, $end, RequestInterface $request, ResponseInterface $response = null)
    {
        $description = $this->describe($request, $response);
        $name = spl_object_hash($request);

        $this->timeline->addEvent($name, $description, $start, $end);
    }
}
