<?php

namespace Phlux\Contracts;

interface StateInterface
{
    /**
     * Create a new state
     *
     * @param array|null $data
     * @return void
     */
    public function __construct($data, array $initial = []);

    /**
     * Checks if a key exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key);

    /**
     * Gets a value from the state by key
     *
     * @param string $key
     * @return mixed
     */
    public function get($key);

    /**
     * Set a value and return a new state
     *
     * @param string $key
     * @param mixed $value
     * @return Phlux\Contracts\StateInterface
     */
    public function set($key, $value);

    /**
     * Remove a value and return a new state
     *
     * @param string $key
     * @return Phlux\Contracts\StateInterface
     */
    public function remove($key);
}
