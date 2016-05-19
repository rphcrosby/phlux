<?php

namespace Phlux\Contracts;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\DispatcherInterface;

interface PhluxInterface
{
    /**
     * Create a new Phlux
     *
     * @param Phlux\Contracts\StateInterface $state
     * @param Phlux\Contracts\DispatcherInterface $dispatcher
     * @return void
     */
    public function __construct(StateInterface $state, DispatcherInterface $dispatcher);

    /**
     * Gets the current state
     *
     * @return Phlux\Contracts\StateInterface
     */
    public function getState();

    /**
     * Run Phlux and generate a new state
     *
     * @return void
     */
    public function run();
}
