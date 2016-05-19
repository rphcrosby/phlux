<?php

namespace Phlux\Contracts;

interface ParserInterface
{
    /**
     * Parses a message to get the event to fire
     *
     * @param string $message
     * @return EventInterface
     */
    public function message($message);
}
