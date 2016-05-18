<?php

namespace Phlux\Listener;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\ListenerInterface;

abstract class Listener implements ListenerInterface
{
    /**
     * Handles an event and returns a state
     *
     * @param Phlux\Contracts\StateInterface $state
     * @param Phlux\Contracts\EventInterface $event
     * @return Phlux\Contracts\StateInterface
     */
    abstract public function handle(StateInterface $state, EventInterface $event);
}
