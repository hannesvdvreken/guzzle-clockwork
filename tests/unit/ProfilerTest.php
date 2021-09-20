<?php

namespace GuzzleHttp\Profiling\Clockwork\Unit;

use Clockwork\Request\Timeline\Timeline;
use GuzzleHttp\Profiling\Clockwork\Profiler;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class ProfilerTest extends TestCase
{
    public function testTimelineIsCalled()
    {
        // Arrange
        $timeline = $this->createMock(Timeline::class);
        $profiler = new Profiler($timeline);
        $request = new Request('GET', 'http://httpbin.org/status/418');
        $response = new Response(418);

        // Set expectations
        $timeline
            ->expects($this->once())
            ->method('event')
            ->with(
                'GET http://httpbin.org/status/418 returned 418 I\'m a teapot', // This is not under test.
                [
                    'start' => $start = microtime(true),
                    'end' => $end = microtime(true),
                ]
            );

        // Act
        $profiler->add($start, $end, $request, $response);

        // Assert
    }
}
