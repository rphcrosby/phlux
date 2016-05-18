<?php

namespace Phlux;

use Phlux\Contracts\PipelineInterface;
use Phlux\Contracts\StoreInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\MiddlewareInterface;
use Closure;


class Pipeline implements PipelineInterface
{
    /**
     * The store that is passed through the pipeline
     *
     * @var Phlux\Contracts\StoreInterface
     */
    protected $store;

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
     * Pass a store and event through the pipeline
     *
     * @param Phlux\Contracts\StoreInterface $store
     * @param Phlux\Contracts\EventInterface $event
     * @return Phlux\Contracts\PipelineInterface
     */
    public function pass(StoreInterface $store, EventInterface $event)
    {
        $this->store = $store;
        $this->event = $event;

        return $this;
    }

    /**
     * Specify middleware that the pipeline is to be passed through
     *
     * @param Phlux\Contracts\MiddlewareInterface $middleware
     * @return Phlux\Contracts\PipelineInterface
     */
    public function through(MiddlewareInterface $middleware)
    {
        $this->middlewares[] = $middleware;

        return $this;
    }

    /**
     * Specify the closure that terminates the pipeline
     *
     * @param Closure $final
     * @return Phlux\Contracts\StoreInterface
     */
    public function then(Closure $final)
    {
        return call_user_func_array(
            array_reduce(
                array_reverse($this->middlewares),
                $this->prepareMiddleware(),
                $this->prepareFinal($final)
            ),
            [$this->store, $this->event]
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
            return function($store, $event) use ($next, $middleware)
            {
                return $middleware($store, $event, $next);
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
        return function($store, $event) use ($final)
        {
            return call_user_func_array($final, [$store, $event]);
        };
    }
}
