<?php

namespace Phlux\Store;

use Phlux\Contracts\StoreInterface;
use Phlux\Contracts\StateInterface;
use Phlux\Store\Store;

class ArrayStore extends Store
{
    /**
     * An array of states
     *
     * @var array
     */
    protected $states = [];

    /**
     * Store the state in the store
     *
     * @param Phlux\Contracts\StateInterface $state
     */
    public function set(StateInterface $state)
    {
        $this->states[$this->getId()] = $state;
    }

    /**
     * Retrieve the state from the store
     *
     * @return Phlux\Contracts\StateInterface
     */
    public function get()
    {
        $id = $this->getId();

        if (isset($this->states[$id])) {
            return $this->states[$id];
        }

        return null;
    }
}
