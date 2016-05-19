<?php

namespace Phlux\State;

use Phlux\Contracts\StateInterface;

use Serializable;

/**
 * Array implementation of a Phlux state
 *
 */
class State implements StateInterface, Serializable
{
    /**
     * An array of state data
     *
     * @var array
     */
    private $data;

    /**
     * Create a new state
     *
     * @param array|null $data
     * @return void
     */
    public function __construct($data, array $initial = [])
    {
        if (is_null($data) || !$data) {
            $data = $initial;
        }

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
        $array = $this->data;

        if (empty($array) || is_null($key)) {
            return false;
        }

        if (array_key_exists($key, $array)) {
            return true;
        }

        foreach (explode('.', $key) as $segment) {
            if (! is_array($array) || ! array_key_exists($segment, $array)) {
                return false;
            }

            $array = $array[$segment];
        }

        return true;
    }

    /**
     * Gets a value from the state by key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get($key, $default)
    {
        $array = $this->data;

        if (is_null($key)) {
            return null;
        }

        if (isset($array[$key])) {
            return $array[$key];
        }

        foreach (explode('.', $key) as $segment) {
            if (!is_array($array) || !array_key_exists($segment, $array)) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
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
        $array = $this->data;

        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return new static($array);
    }

    /**
     * Remove a value and return a new state
     *
     * @param string $key
     * @return Phlux\Contracts\StateInterface
     */
    public function remove($key)
    {
        $array = $this->data;
        $original = &$array;

        $keys = (array) $keys;

        if (count($keys) === 0) {
            return;
        }

        foreach ($keys as $key) {
            $parts = explode('.', $key);

            while (count($parts) > 1) {
                $part = array_shift($parts);

                if (isset($array[$part]) && is_array($array[$part])) {
                    $array = &$array[$part];
                } else {
                    $parts = [];
                }
            }

            unset($array[array_shift($parts)]);

            // clean up after each pass
            $array = &$original;
        }

        return new static($array);
    }

    /**
     * Serializes this event
     *
     * @return string
     */
    public function serialize()
    {
        return json_encode($this->data);
    }

    /**
     * Unserializes an event
     *
     * @param string $serialized
     * @return void
     */
    public function unserialize($serialized)
    {
        $this->data = (array) json_decode($serialized);
    }

    /**
     * Casts the state to a string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->serialize();
    }
}
