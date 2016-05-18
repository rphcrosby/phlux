<?php

namespace Phlux;

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
     * Returns a unique identifier for this event
     *
     * @return string
     */
    public function getType()
    {
        return static::class;
    }
}
