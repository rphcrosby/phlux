<?php

namespace Phlux\Observer;

use Phlux\Observer\Observer;
use Phlux\Contracts\StateInterface;
use Phlux\Contracts\StoreInterface;

/**
 * Observer the state for any changes and then persist them
 *
 */
final class PersistenceObserver extends Observer
{
    /**
     * This observers unique identifer
     *
     * @var string
     */
    protected $id = 'phlux.persistence';

    /**
     * The store to persist the state to
     *
     * @var Phlux\Contracts\StoreInterface
     */
    protected $store;

    /**
     * Create a new persistence observer
     *
     * @param
     */
    public function __construct(StoreInterface $store)
    {
        $this->store = $store;
    }

    /**
     * Notify the observer that a state change has happened
     *
     * @param Phlux\Contracts\StateInterface $state
     * @return void
     */
    public function notify(StateInterface $state)
    {
        $this->store->set($state);
    }
}
