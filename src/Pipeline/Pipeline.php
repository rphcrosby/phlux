<?php

namespace Phlux\Pipeline;

use Phlux\Contracts\PipelineInterface;
use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;
use Closure;

class Pipeline implements PipelineInterface
{
    /**
     * The state that is passed through the pipeline
     *
     * @var Phlux\Contracts\StateInterface
     */
    protected $state;

    /**
     * The event that is passed through the pipeline
     *
     * @var Phlux\Contracts\EventInterface
     */
    protected $event;

    /**
     * The array of middleware that the pipeline passes through
     *
     * @var array
     */
    protected $middleware = [];

    /**
     * Pass a state and event through the pipeline
     *
     * @param Phlux\Contracts\StateInterface $state
     * @param Phlux\Contracts\EventInterface $event
     * @return Phlux\Contracts\PipelineInterface
     */
    public function pass(StateInterface $state, EventInterface $event)
    {
        $this->state = $state;
        $this->event = $event;

        return $this;
    }

    /**
     * Specify middleware that the pipeline is to be passed through
     *
     * @param array $middleware
     * @return Phlux\Contracts\PipelineInterface
     */
    public function through(array $middleware)
    {
        $this->middleware = $middleware;

        return $this;
    }

    /**
     * Specify the closure that terminates the pipeline
     *
     * @param Closure $final
     * @return Phlux\Contracts\StateInterface
     */
    public function to(Closure $final)
    {
        return call_user_func_array(
            array_reduce(
                array_reverse($this->middleware),
                $this->prepareMiddleware(),
                $this->prepareFinal($final)
            ),
            [$this->state, $this->event]
        );
    }

    /**
     * Prepares the middleware for the pipeline
     *
     * @return Closure
     */
    protected function prepareMiddleware()
    {
        return function($next, $middleware)
        {
            return function($state, $event) use ($next, $middleware)
            {
                return $middleware($state, $event, $next);
            };
        };
    }

    /**
     * Prepares the final closure for use in the pipeline
     *
     * @return Closure
     */
    protected function prepareFinal($final)
    {
        return function($state, $event) use ($final)
        {
            return call_user_func_array($final, [$state, $event]);
        };
    }
}
