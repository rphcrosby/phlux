<?php

namespace Phlux\Middleware;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\MiddlewareInterface;
use Psr\Log\LoggerInterface;

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
        $this->logger->info('-----------Previous State-----------');
        $this->logger->info($state);

        $this->logger->info('----------Dispatched Event----------');
        $this->logger->info($event);

        $result = $next($state, $event);

        $this->logger->info('-------------Next State-------------');
        $this->logger->info($result);

        return $result;
    }
}
