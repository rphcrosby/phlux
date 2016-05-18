<?php

require 'vendor/autoload.php';

use Phlux\Phlux;

use Phlux\Event\Event;
use Phlux\Pipeline\Pipeline;
use Phlux\Dispatcher\Dispatcher;
use Phlux\Observer\Observer;
use Phlux\Queue\ArrayQueue;
use Phlux\State\ArrayState;
use Phlux\Logger\DumpLogger;
use Phlux\Listener\ListenerCollection;
use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;
use Phlux\Contracts\ListenerInterface;
use Phlux\Contracts\ObserverInterface;
use Phlux\Contracts\ObservableInterface;
use Phlux\Middleware\LoggerMiddleware;
use Phlux\Middleware\ListenersMiddleware;
use Phlux\Middleware\ThunkMiddleware;

class UserSubscribed extends Event implements EventInterface {}

class SubscribersChanged extends Observer implements ObserverInterface
{
    public function notify(StateInterface $state)
    {
        echo 'lol';
    }
}

class SubscribersListener implements ListenerInterface
{
    public function handle(StateInterface $state, EventInterface $event)
    {
        switch ($event->getIdentifier()) {
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

// Setup a new phlux
$phlux = new Phlux(
    new ArrayState(['subscribers' => []]),
    new Dispatcher(new ArrayQueue, new Pipeline)
);

// Register event listeners
$phlux->listen(new SubscribersListener);

// Attach new middleware
$phlux->middleware(new LoggerMiddleware(new DumpLogger));
$phlux->middleware(new ThunkMiddleware);

// Register an observer
$phlux->observe(new SubscribersChanged);

// Fire an event on the dispatcher
$phlux->fire(new UserSubscribed([
    'name' => 'Richard Crosby'
]));

// Fire another event on the dispatcher
$phlux->fire(new UserSubscribed([
    'name' => 'Test User'
]));

// Run phlux
$phlux->run();
$phlux->run();
