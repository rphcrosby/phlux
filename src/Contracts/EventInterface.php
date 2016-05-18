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
     * Returns a unique identifier for this event
     *
     * @return string
     */
    public function getType();
}
