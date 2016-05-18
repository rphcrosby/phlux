<?php

namespace Phlux\Contracts;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;

interface MiddlewareInterface
{
    /**
     * Run the middleware
     *
     * @param Phlux\Contracts\StateInterface $state
     * @param Phlux\Contracts\EventInterface $event
     * @param callable $next
     * @return Phlux\Contracts\StateInterface
     */
    public function __invoke(StateInterface $state, EventInterface $event, callable $next = null);
}
