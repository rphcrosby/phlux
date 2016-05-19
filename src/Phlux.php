<?php

namespace Phlux;

use Phlux\Contracts\StateInterface;
use Phlux\Contracts\DispatcherInterface;
use Phlux\Contracts\ObserverInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\ObservableInterface;
use Phlux\Traits\ObservableTrait;

class Phlux implements ObservableInterface
{
    use ObservableTrait;

    /**
     * The current state
     *
     * @var Phlux\Contracts\StateInterface
     */
    protected $state;

    /**
     * The event dispatcher
     *
     * @var Phlux\Contracts\DispatcherInterface
     */
    protected $dispatcher;

    /**
     * Create a new Phlux
     *
     * @param Phlux\Contracts\StateInterface $state
     * @param Phlux\Contracts\DispatcherInterface $dispatcher
     * @return void
     */
    public function __construct(StateInterface $state, DispatcherInterface $dispatcher)
    {
        $this->state = $state;
        $this->dispatcher = $dispatcher;
    }

    /**
     * Gets the current state
     *
     * @return Phlux\Contracts\StateInterface
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Run Phlux and generate a new state
     *
     * @return void
     */
    public function run()
    {
        $this->state = $this->dispatcher->handle($this->state);

        $this->notifyObservers();
    }

    /**
     * Pass any nonexistent method calls through to the dispatcher
     *
     * @param string $method
     * @param array $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        return call_user_func_array([$this->dispatcher, $method], $parameters);
    }
}
