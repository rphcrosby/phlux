<?php

namespace Phlux\Middleware;

use Phlux\Contracts\StoreInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\LoggerInterface;

/**
 * Processes any listeners that are attached to the store
 *
 */
class ListenersMiddleware implements MiddlewareInterface
{
    /**
     * Run the middleware
     *
     * @param Phlux\Contracts\StoreInterface $store
     * @param Phlux\Contracts\EventInterface $event
     * @param callable $next
     * @return Phlux\Contracts\StoreInterface
     */
    public function __invoke(StoreInterface $store, EventInterface $event, callable $next = null)
    {
        foreach ($store->getListeners() as $listener) {
            $store->setState($listener->handle($store->getState(), $event));
        }

        return $next($store, $event);
    }
}
