<?php

namespace Phlux\Contracts;

interface LoggerInterface
{
    /**
     * Log a message
     *
     * @param string|array $messages
     * @return void
     */
    public function log($message);
}
