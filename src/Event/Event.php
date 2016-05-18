<?php

namespace Phlux\Event;

use Phlux\Contracts\EventInterface;

abstract class Event implements EventInterface
{
    /**
     * The payload of the event
     *
     * @var mixed
     */
    protected $payload;

    /**
     * The unique identifier of this event
     *
     * @var mixed
     */
    protected $id;

    /**
     * Create a new event
     *
     * @param mixed $payload
     * @return void
     */
    public function __construct($payload)
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
     * Returns a unique identifier for this event
     *
     * @return string
     */
    public function getIdentifier()
    {
        return $this->id ?: static::class;
    }

    /**
     * Set the unique identifier of this event
     *
     * @param mixed $id
     * @return void
     */
    public function setIdentifier($id)
    {
        $this->id = $id;
    }
}
