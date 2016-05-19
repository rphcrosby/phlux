<?php

namespace Phlux\Store;

use Phlux\Contracts\StoreInterface;

abstract class Store implements StoreInterface
{
    /**
     * The unique ID for this store
     *
     * @var string
     */
    protected $id;

    /**
     * Create a new state store
     *
     * @return void
     */
    public function __construct()
    {
        $this->setId();
    }

    /**
     * Generates a unique ID for this store
     *
     * @return string
     */
    public function generateId()
    {
        return sha1(uniqid('', true).microtime(true));
    }

    /**
     * Returns the ID for this store
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set a new ID for this store
     *
     * @param string $id
     * @return void
     */
    public function setId($id = null)
    {
        if (is_null($id)) {
            $id = $this->generateId();
        }

        $this->id = $id;
    }
}
