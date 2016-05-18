<?php

namespace Phlux;

use Phlux\Contracts\StoreInterface;
use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\QueueInterface;
use Phlux\Contracts\ListenerInterface;
use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\PipelineInterface;

class Store implements StoreInterface
{
    /**
     * The current state of the application
     *
     * @var Phlux\Contracts\StateInterface
     */
    protected $state;

    /**
     * An array of store listeners
     *
     * @var Phlux\Contracts\QueueInterface
     */
    protected $listeners = [];

    /**
     * Create a new store
     *
     * @param Phlux\Contracts\StateInterface $state
     * @return void
     */
    public function __construct(StateInterface $state)
    {
        $this->state = $state;
    }

    /**
     * Get the current state
     *
     * @return Phlux\Contracts\StateInterface
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Sets the current state
     *
     * @param Phlux\Contracts\StateInterface $state
     * @return void
     */
    public function setState(StateInterface $state)
    {
        $this->state = $state;
    }

    /**
     * Get the listeners for this store
     *
     * @return array
     */
    public function getListeners()
    {
        return $this->listeners;
    }

    /**
     * Add a listener to this store
     *
     * @param Phlux\Contracts\ListenerInterface
     * @return void
     */
    public function listen(ListenerInterface $listener)
    {
        $this->listeners[] = $listener;
    }
}
