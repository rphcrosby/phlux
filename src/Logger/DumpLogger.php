<?php

namespace Phlux\Logger;

use Phlux\Contracts\LoggerInterface;

/**
 * Simple var dump logger, useful for testing
 *
 */
class DumpLogger implements LoggerInterface
{
    /**
     * Log a message
     *
     * @param string|array $messages
     * @return void
     */
    public function log($messages)
    {
        if (is_array($messages)) {
            array_walk($message, 'var_dump');
        } else {
            var_dump($messages);
        }
    }
}
