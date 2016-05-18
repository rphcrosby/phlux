<?php

namespace Phlux\Contracts;

interface EventInterface
{
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
     * Returns a unique identifier for this event
     *
     * @return string
     */
    public function getIdentifier();
}
