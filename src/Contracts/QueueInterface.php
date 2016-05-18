<?php

namespace Phlux\Contracts;

use Phlux\Contracts\EventInterface;

interface QueueInterface
{
    /**
     * Push a new event onto the end of the queue
     *
     * @param Phlux\Contracts\EventInterface $event
     * @return void
     */
    public function push(EventInterface $event);

    /**
     * Return the next event from the beginning of the queue
     *
     * @return Phlux\Contracts\EventInterface
     */
    public function next();

    /**
     * Clear all events from the queue
     *
     * @return void
     */
    public function clear();

    /**
     * Returns the size of the events queue
     *
     * @return int
     */
    public function size();
}
