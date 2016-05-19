<?php

namespace Phlux\Server;

use Phlux\Contracts\ParserInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Exceptions\InvalidMessageException;

class Parser implements ParserInterface
{
    /**
     * Parses a message to get the event to fire
     *
     * @param string $message
     * @return EventInterface
     */
    public function message($message)
    {
        // Decode the message from JSON
        $decoded = $this->decodeMessage($message);

        if ($this->isValidEvent($decoded)) {
            return [$decoded->id, $decoded->payload];
        }
    }

    /**
     * Decodes a message
     *
     * @param string $message
     * @return stdClass
     */
    protected function decodeMessage($message)
    {
        if (!$decoded = json_decode($message)) {
            throw new InvalidMessageException('Message cannot be parsed, please try again.');
        }

        return $decoded;
    }

    /**
     * Checks if the event class exists
     *
     * @param string $id
     * @return bool
     */
    protected function classExists($id)
    {
        return class_exists($id);
    }

    /**
     * Checks if a decoded event is in the right format
     *
     * @param stdClass $event
     * @return bool
     */
    protected function isValidEvent($event)
    {
        if (property_exists($event, 'id') && property_exists($event, 'payload')) {
            return true;
        }

        return false;
    }
}
