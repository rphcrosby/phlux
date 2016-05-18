<?php

namespace Phlux\Contracts;

use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\StoreInterface;
use Phlux\Contracts\EventInterface;
use Closure;

interface PipelineInterface
{
    /**
     * Pass a store and event through the pipeline
     *
     * @param Phlux\Contracts\StoreInterface $store
     * @param Phlux\Contracts\EventInterface $event
     * @return Phlux\Contracts\PipelineInterface
     */
    public function pass(StoreInterface $store, EventInterface $event);

    /**
     * Specify middleware that the pipeline is to be passed through
     *
     * @param Phlux\Contracts\MiddlewareInterface $middleware
     * @return Phlux\Contracts\PipelineInterface
     */
    public function through(MiddlewareInterface $middleware);

    /**
     * Specify the closure that terminates the pipeline
     *
     * @param Closure $final
     * @return Phlux\Contracts\StoreInterface
     */
    public function then(Closure $final);
}
