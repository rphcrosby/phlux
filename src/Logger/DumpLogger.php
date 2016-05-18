<?php

namespace Phlux\Logger;

use Psr\Log\AbstractLogger;

/**
 * Simple var dump logger, useful for testing
 *
 */
class DumpLogger extends AbstractLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        var_dump($message);
    }
}
