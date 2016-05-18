<?php

namespace Phlux\Contracts;

use Phlux\Contracts\StoreInterface;
use Phlux\Contracts\EventInterface;

interface MiddlewareInterface
{
    /**
     * Run the middleware
     *
     * @param Phlux\Contracts\StoreInterface $store
     * @param Phlux\Contracts\EventInterface $event
     * @param callable $next
     * @return Phlux\Contracts\StoreInterface
     */
    public function __invoke(StoreInterface $store, EventInterface $event, callable $next = null);
}
