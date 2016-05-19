<?php

require 'vendor/autoload.php';

use Phlux\Phlux;
use Phlux\Event\Event;
use Phlux\Pipeline\Pipeline;
use Phlux\Dispatcher\Dispatcher;
use Phlux\Queue\ArrayQueue;
use Phlux\State\State;

// Middleware
use Phlux\Logger\DumpLogger;
use Phlux\Middleware\LoggerMiddleware;
use Phlux\Middleware\ThunkMiddleware;

// Persistence
use Phlux\Store\ArrayStore;
use Phlux\Observer\PersistenceObserver;

// Setup a persistence layer
$store = new ArrayStore;

// Setup a new phlux
$phlux = new Phlux(
    new State($store->get(), ['subscribers' => []]),
    new Dispatcher(new ArrayQueue, new Pipeline)
);

// Attach new middleware
$phlux->middleware(new LoggerMiddleware(new DumpLogger));
$phlux->middleware(new ThunkMiddleware);

// Register an observer
$phlux->observe(new PersistenceObserver($store));

// Run phlux
$phlux->run();
