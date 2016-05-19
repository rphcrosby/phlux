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
     * An array of registered events
     *
     * @var array
     */
    protected $evemts = [];

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
     * @param string $event
     * @param Phlux\Contacts\ListenerInterface $listener
     * @return void
     */
    public function listen($event, ListenerInterface $listener)
    {
        // If listeners already exist for this event
        if ($this->hasListeners($event)) {

            // Get the current listeners
            $listeners = $this->listeners[$event];

            // If more than one listener exists already then add this listener to them
            // otherwise case it to an array
            if (is_array($listeners)) {
                $listeners = array_push($listeners, $event);
            } else {
                $listeners = array_push([$listeners], $event);
            }

            $this->listeners[$event] = $listeners;
        } else {

            // Otherwise this listener is the first listener for this event
            $this->listeners[$event] = $listener;
        }
    }

    /**
     * Fires a new event
     *
     * @param string $event
     * @param array $payload
     * @return void
     */
    public function fire($event, array $payload = [])
    {
        $event = $this->resolveEvent($event);
        $event->setPayload($payload);

        if ($event) {
            $this->queue->push($event);
        }
    }

    /**
     * Binds a new event to Phlux
     *
     * @param Phlux\Contracts\EventInterface $event
     * @return void
     */
    public function bind(EventInterface $event)
    {
        $this->events[$event->getIdentifier()] = $event;
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
     * Gets the list of listeners that should handle the incoming event
     *
     * @param Phlux\Contracts\EventInterface $event
     * @return array
     */
    protected function resolveListeners(EventInterface $event)
    {
        if ($this->hasListeners($event->getIdentifier())) {
            $listeners = $this->listeners[$event->getIdentifier()];
            return is_array($listeners) ? $listeners : [$listeners];
        }

        return [];
    }

    /**
     * Finds an event either by it's class name or by alias
     *
     * @param string $event
     * @return Phlux\Contracts\EventInterface
     */
    protected function resolveEvent($event)
    {
        if ($event instanceof EventInterface) {
            return $event;
        }

        if (class_exists($event)) {
            $instance = new $event;
            return $instance instanceof EventInterface ? $instance : null;
        }

        if (array_key_exists($event, $this->events)) {
            return $this->events[$event];
        }
    }

    /**
     * Checks if an event identifier has any listeners
     *
     * @param string $event
     * @return array
     */
    public function hasListeners($event)
    {
        return array_key_exists($event, $this->listeners);
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
                return array_reduce($this->resolveListeners($event), function($state, $listener) use ($event)
                {
                    return $listener->handle($state, $event);
                }, $state);
            });
    }
}
