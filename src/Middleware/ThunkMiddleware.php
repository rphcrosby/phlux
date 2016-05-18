<?php

namespace Phlux\Middleware;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\LoggerInterface;

/**
 * If the event being handled is a thunk then this middleware will resolve it
 *
 */
class ThunkMiddleware implements MiddlewareInterface
{
    /**
     * Run the middleware
     *
     * @param Phlux\Contracts\StateInterface $state
     * @param Phlux\Contracts\EventInterface $event
     * @param callable $next
     * @return Phlux\Contracts\StateInterface
     */
    public function __invoke(StateInterface $state, EventInterface $event, callable $next = null)
    {
        // Get the payload for the event
        $payload = $event->getPayload();

        // If the payload is callable then resolve it and set the new payload
        if (is_callable($payload)) {
            $event->setPayload(call_user_func(
                $payload,
                $state
            ));
        }

        return $next($state, $event);
    }
}
