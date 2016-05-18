<?php

require 'vendor/autoload.php';

use Phlux\Event;
use Phlux\Store;
use Phlux\Pipeline;
use Phlux\Dispatcher;
use Phlux\Queues\ArrayQueue;
use Phlux\States\ArrayState;
use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\ListenerInterface;
use Phlux\Middleware\LoggerMiddleware;
use Phlux\Middleware\ProcessListenersMiddleware;
use Phlux\Loggers\DumpLogger;

class UserSubscribed extends Event implements EventInterface
{

}

class SubscribersListener implements ListenerInterface
{
    public function handle(StateInterface $state, EventInterface $event)
    {
        switch ($event->getType()) {
            case UserSubscribed::class:
                $state = $this->handleSubscribed($state, $event);
                return $state;
            default:
                return $state;
        }
    }

    public function handleSubscribed($state, $event)
    {
        $subscribers = $state->get('subscribers');
        $subscribers[] = $event->getPayload();
        return $state->set('subscribers', $subscribers);
    }
}

// Setup a new store with initial state
$store = new Store(new ArrayState([
    'subscribers' => []
]));

// Add a store listener
$store->listen(new SubscribersListener);

// Setup the queue
$queue = new ArrayQueue;

// Setup a new pipeline with middleware
$pipeline = new Pipeline;
$pipeline->through(new LoggerMiddleware(new DumpLogger));
$pipeline->through(new ListenersMiddleware);

// Setup a new dispatcher
$dispatcher = new Dispatcher($queue, $store, $pipeline);

// Fire an event on the dispatcher
$dispatcher->fire(new UserSubscribed([
    'name' => 'Richard Crosby'
]));

// Fire another event on the dispatcher
$dispatcher->fire(new UserSubscribed([
    'name' => 'Test User'
]));

// Run the dispatcher
$dispatcher->run();
$dispatcher->run();
