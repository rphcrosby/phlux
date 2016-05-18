<?php

namespace Phlux\Contracts;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;

interface ListenerInterface
{
    /**
     * Handles an event and returns a state
     *
     * @param Phlux\Contracts\StateInterface $state
     * @param Phlux\Contracts\EventInterface $event
     * @return Phlux\Contracts\StateInterface
     */
    public function handle(StateInterface $state, EventInterface $event);
}
