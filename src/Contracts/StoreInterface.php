<?php

namespace Phlux\Contracts;

use Phlux\Contracts\StateInterface;

interface StoreInterface
{
    /**
     * Store the state in the store
     *
     * @param Phlux\Contracts\StateInterface $state
     */
    public function set(StateInterface $state);

    /**
     * Retrieve the state from the store
     *
     * @return Phlux\Contracts\StateInterface
     */
    public function get();

    /**
     * Generates a unique ID for this store
     *
     * @return string
     */
    public function generateId();

    /**
     * Returns the ID for this store
     *
     * @return string
     */
    public function getId();

    /**
     * Set a new ID for this store
     *
     * @param string $id
     * @return void
     */
    public function setId($id);
}
