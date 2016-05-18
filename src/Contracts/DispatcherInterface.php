<?php

namespace Phlux\Contracts;

use Phlux\Contracts\QueueInterface;
use Phlux\Contracts\PipelineInterface;
use Phlux\Contracts\ListenerInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\StateInterface;

interface DispatcherInterface
{
    /**
     * Create a new queue
     *
     * @param Phlux\Contracts\QueueInterface $queue
     * @param Phlux\Contracts\PipelineInterface $pipeline
     * @return void
     */
    public function __construct(QueueInterface $queue, PipelineInterface $pipeline);

    /**
     * Registers a listener
     *
     * @param Phlux\Contacts\ListenerInterface $listener
     * @return void
     */
    public function listen(ListenerInterface $listener);

    /**
     * Fires a new event
     *
     * @param Phlux\Contracts\QueueInterface $event
     * @return void
     */
    public function fire(EventInterface $event);

    /**
     * Registers a new middleware
     *
     * @param Phlux\Contacts\MiddlewareInterface $middleware
     * @return void
     */
    public function middleware(MiddlewareInterface $middleware);

    /**
     * Handles the next event on the current state
     *
     * @return Phlux\Contracts\StateInterface
     */
    public function handle(StateInterface $state);
}
