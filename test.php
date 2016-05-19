<?php

require 'vendor/autoload.php';

use Phlux\Phlux;
use Phlux\Event\Event;
use Phlux\Listener\Listener;
use Phlux\Pipeline\Pipeline;
use Phlux\Dispatcher\Dispatcher;
use Phlux\Queue\ArrayQueue;
use Phlux\State\State;
use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;

// Middleware
use Phlux\Logger\DumpLogger;
use Phlux\Middleware\LoggerMiddleware;
use Phlux\Middleware\ThunkMiddleware;

// Persistence
use Phlux\Store\ArrayStore;
use Phlux\Observer\PersistenceObserver;

// Server
use Phlux\Server\Parser;
use Phlux\Server\Server;

// Setup a new event
class TestEvent extends Event
{
    protected $id = 'test';
}

// Setup a new listener
class TestListener extends Listener
{
    public function handle(StateInterface $state, EventInterface $event)
    {
        $subscribers = array_merge($state->get('subscribers'), [$event->getPayload()]);
        return $state->set('subscribers', $subscribers);
    }
}

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

// Register an event
$phlux->bind(new TestEvent);

// Register a listener
$phlux->listen('test', new TestListener);

// Run Phlux locally
// $phlux->fire(new TestEvent(['name' => 'Richard Crosby']));
// $phlux->run();

// Start a new server
$server = new Server($phlux, new Parser);
$server->start();

