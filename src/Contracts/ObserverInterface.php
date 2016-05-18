<?php

namespace Phlux\Contracts;

use Phlux\Contracts\StateInterface;

interface ObserverInterface
{
    /**
     * Get this observers unique
     *
     * @return string
     */
    public function getIdentifer();

    /**
     * Notify the observer that a state change has happened
     *
     * @param Phlux\Contracts\StateInterface $state
     * @return void
     */
    public function notify(StateInterface $state);
}
