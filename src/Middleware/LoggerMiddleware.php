<?php

namespace Phlux\Middleware;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\LoggerInterface;

/**
 * Logs any events that are passed through the pipeline
 *
 */
class LoggerMiddleware implements MiddlewareInterface
{
    /**
     * The logger to be used for output
     *
     * @var Phlux\Contracts\LoggerInterface
     */
    protected $logger;

    /**
     * Create a new logger middleware
     *
     * @param Phlux\Contracts\LoggerInterface $logger
     * @return void
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

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
        $this->logger->log('-----------Previous State-----------');
        $this->logger->log($state);

        $this->logger->log('----------Dispatched Event----------');
        $this->logger->log($event);
        $result = $next($state, $event);

        $this->logger->log('-------------Next State-------------');
        $this->logger->log($result);

        return $result;
    }
}
