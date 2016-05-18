<?php

namespace Phlux\Queue;

use Phlux\Contracts\EventInterface;
use Phlux\Contracts\QueueInterface;

/**
 * Array implementation of a Phlux queue
 *
 */
class ArrayQueue implements QueueInterface
{
    /**
     * An array of queued events
     *
     * @var array
     */
    protected $events = [];

    /**
     * Push a new event onto the end of the queue
     *
     * @param Phlux\Contracts\EventInterface $event
     * @return void
     */
    public function push(EventInterface $event)
    {
        array_push($this->events, $event);
    }

    /**
     * Return the next event from the beginning of the queue
     *
     * @return Phlux\Contracts\EventInterface
     */
    public function next()
    {
        return array_shift($this->events);
    }

    /**
     * Clear all events from the queue
     *
     * @return void
     */
    public function clear()
    {
        $this->events = [];
    }

    /**
     * Returns the size of the events queue
     *
     * @return int
     */
    public function size()
    {
        return count($this->events);
    }
}
