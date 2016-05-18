<?php

namespace Phlux\Contracts;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\ListenerInterface;
use Phlux\Contracts\EventInterface;

interface StoreInterface
{
    /**
     * Create a new store
     *
     * @param Phlux\Contracts\StateInterface $state
     * @return void
     */
    public function __construct(StateInterface $state);

    /**
     * Get the current state
     *
     * @return Phlux\Contracts\StateInterface
     */
    public function getState();

    /**
     * Sets the current state
     *
     * @param Phlux\Contracts\StateInterface $state
     * @return void
     */
    public function setState(StateInterface $state);

    /**
     * Get the listeners for this store
     *
     * @return array
     */
    public function getListeners();

    /**
     * Add a listener to this store
     *
     * @param Phlux\Contracts\ListenerInterface
     * @return void
     */
    public function listen(ListenerInterface $listener);
}
