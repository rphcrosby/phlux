<?php

namespace Phlux\Contracts;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;
use Closure;

interface PipelineInterface
{
    /**
     * Pass a store and event through the pipeline
     *
     * @param Phlux\Contracts\StateInterface $state
     * @param Phlux\Contracts\EventInterface $event
     * @return Phlux\Contracts\PipelineInterface
     */
    public function pass(StateInterface $state, EventInterface $event);

    /**
     * Specify middleware that the pipeline is to be passed through
     *
     * @param array $middleware
     * @return Phlux\Contracts\PipelineInterface
     */
    public function through(array $middleware);

    /**
     * Specify the closure that terminates the pipeline
     *
     * @param Closure $final
     * @return Phlux\Contracts\StateInterface
     */
    public function to(Closure $final);
}
