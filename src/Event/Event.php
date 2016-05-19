<?php

namespace Phlux\Event;

use Phlux\Contracts\EventInterface;
use Serializable;

abstract class Event implements EventInterface, Serializable
{
    /**
     * The payload of the event
     *
     * @var mixed
     */
    protected $payload;

    /**
     * The unique identifier for this event
     *
     * @var string
     */
    protected $id;

    /**
     * Create a new event
     *
     * @param mixed $payload
     * @return void
     */
    public function __construct($payload = null)
    {
        $this->payload = $payload;
    }

    /**
     * Get the payload for this event
     *
     * @return mixed
     */
    public function getPayload()
    {
        return $this->payload;
    }

    /**
     * Returns the unique identifier for this event
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->id ?: static::class;
    }

    /**
     * Set the payload for this event
     *
     * @param mixed $payload
     * @return void
     */
    public function setPayload($payload)
    {
        $this->payload = $payload;
    }

    /**
     * Serializes this event
     *
     * @return string
     */
    public function serialize()
    {
        return json_encode([
            'id' => $this->getIdentifier(),
            'payload' => $this->getPayload()
        ]);
    }

    /**
     * Unserializes an event
     *
     * @param string $serialized
     * @return void
     */
    public function unserialize($serialized)
    {
        $decoded = json_decode($serialized);
        $class = $decoded['id'];
        return new $class($decoded['payload']);
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
