<?php

namespace Phlux\Traits;

use Phlux\Contracts\ObserverInterface;

trait ObservableTrait
{
    /**
     * An array of observers
     *
     * @var array
     */
    protected $observers;

    /**
     * Register an observer
     *
     * @return void
     */
    public function observe(ObserverInterface $observer)
    {
        $this->observers[$observer->getIdentifer()] = $observer;
    }

    /**
     * Unregister an observer
     *
     * @return void
     */
    public function unregisterObserver(ObserverInterface $observer)
    {
        unset($this->observers[$observer->getIdentifier()]);
    }

    /**
     * Notify the registered observers that a state change has happened
     *
     * @return void
     */
    public function notifyObservers()
    {
        foreach ($this->observers as $observer) {
            $observer->notify($this->getState());
        }
    }
}
