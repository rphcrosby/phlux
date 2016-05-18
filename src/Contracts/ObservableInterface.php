<?php

namespace Phlux\Contracts;

use Phlux\Contracts\ObserverInterface;

interface ObservableInterface
{
    /**
     * Register an observer
     *
     * @return void
     */
    public function observe(ObserverInterface $observer);

    /**
     * Unregister an observer
     *
     * @return void
     */
    public function unregisterObserver(ObserverInterface $observer);

    /**
     * Notify the registered observers that a state change has happened
     *
     * @return void
     */
    public function notifyObservers();

    /**
     * Gets the state that triggered the change
     *
     * @return mixed
     */
    public function getState();
}
