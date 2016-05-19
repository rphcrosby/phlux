<?php

namespace Phlux\Contracts;

interface EventInterface
{
    /**
     * Create a new event
     *
     * @param mixed $payload
     * @return void
     */
    public function __construct($payload = null);

    /**
     * Get the payload for this event
     *
     * @return mixed
     */
    public function getPayload();

    /**
     * Set the payload for this event
     *
     * @param mixed $payload
     * @return void
     */
    public function setPayload($payload);

    /**
     * Returns the unique identifier for this event
     *
     * @return string
     */
    public function getIdentifier();

    /**
     * Serializes this event
     *
     * @return string
     */
    public function serialize();

    /**
     * Unserializes an event
     *
     * @param string $serialized
     * @return void
     */
    public function unserialize($serialized);

    /**
     * Casts the state to a string
     *
     * @return string
     */
    public function __toString();
}
