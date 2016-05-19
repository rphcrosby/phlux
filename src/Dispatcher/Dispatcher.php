<?php

namespace Phlux\Dispatcher;

use Phlux\Contracts\DispatcherInterface;
use Phlux\Contracts\QueueInterface;
use Phlux\Contracts\PipelineInterface;
use Phlux\Contracts\ListenerInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\StateInterface;

class Dispatcher implements DispatcherInterface
{
    /**
     * The queue where all events are managed
     *
     * @var Phlux\Contracts\QueueInterface
     */
    protected $queue;

    /**
     * The pipeline through which all events are handled
     *
     * @var Phlux\Contracts\PipelineInterface
     */
    protected $pipeline;

    /**
     * An array of registered listeners
     *
     * @var array
     */
    protected $listeners = [];

    /**
     * An array of registered middleware
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Create a new queue
     *
     * @param Phlux\Contracts\QueueInterface $queue
     * @param Phlux\Contracts\PipelineInterface $pipeline
     * @return void
     */
    public function __construct(QueueInterface $queue, PipelineInterface $pipeline)
    {
        $this->queue = $queue;
        $this->pipeline = $pipeline;
    }

    /**
     * Registers a new listener
     *
     * @param Phlux\Contacts\ListenerInterface $listener
     * @return void
     */
    public function listen(ListenerInterface $listener)
    {
        $this->listeners[] = $listener;
    }

    /**
     * Fires a new event
     *
     * @param Phlux\Contracts\QueueInterface $event
     * @return void
     */
    public function fire(EventInterface $event)
    {
        $this->queue->push($event);
    }

    /**
     * Registers a new middleware
     *
     * @param Phlux\Contacts\MiddlewareInterface $middleware
     * @return void
     */
    public function middleware(MiddlewareInterface $middleware)
    {
        $this->middleware[] = $middleware;
    }

    /**
     * Handles the next event on the current state
     *
     * @return Phlux\Contracts\StateInterface
     */
    public function handle(StateInterface $state)
    {
        // Get the next event
        $event = $this->queue->next();

        // If there is no event in the queue then just return the current state
        if (!$event) {
            return $state;
        }

        return $this->pipeline
            ->pass($state, $event)
            ->through($this->middleware)
            ->to(function($state, $event)
            {
                return array_reduce($this->listeners, function($state, $listener) use ($event)
                {
                    return $listener->handle($state, $event);
                }, $state);
            });
    }
}
