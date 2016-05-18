<?php

namespace Phlux\States;

use Phlux\Contracts\StateInterface;

/**
 * Array implementation of a Phlux state
 *
 */
class ArrayState implements StateInterface
{
    /**
     * An array of data
     *
     * @var array
     */
    private $data;

    /**
     * Create a new state
     *
     * @param array $data
     * @return void
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * Checks if a key exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->data);
    }

    /**
     * Gets a value from the state by key
     *
     * @param string $key
     * @return mixed
     */
    public function get($key)
    {
        if ($this->has($key)) {
            return $this->data[$key];
        }
    }

    /**
     * Set a value and return a new state
     *
     * @param string $key
     * @param mixed $value
     * @return Phlux\Contracts\StateInterface
     */
    public function set($key, $value)
    {
        return new static(array_merge($this->data, [$key => $value]));
    }

    /**
     * Remove a value and return a new state
     *
     * @param string $key
     * @return Phlux\Contracts\StateInterface
     */
    public function remove($key)
    {
        return new static(array_diff($this->data, [$key => $value]));
    }
}
