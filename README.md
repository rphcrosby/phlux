## Phlux

Phlux is a library for managing stateful objects using events, listeners, observers and other nice
features.

### States

States in Phlux are immutable, meaning they cannot be modified. To perform a state change you must move
from one state to another. States can stored however you choose, Phlux comes with a simple array state
to get you started:

```php
use Phlux\State\ArrayState as State;

$state = new State([
    'subscribers' => []
]);
```

Performing updates on states will return a new state:

```php
$state->get('subscribers'); // Returns []

$new = $state->set('subscribers', [
    ['name' => 'Joe Blogs']
]);

$state->get('subscribers'); // Returns []
$new->get('subscribers'); // Returns [['name' => 'Joe Blogs']]
```

### Events

Events in Phlux are simple classes that specify when something happens in the application. Events
are sent to the dispatcher and should be the only way of sending information to the application state. Each
event has a unique identifer that defaults to the name of the class;

```php
use Phlux\Event\Event;

class UserSubscribed extends Event
{

}

$event = new UserSubscribed(['name' => 'Joe Blogs']);
```

### Listeners

Listeners are responsible for performing an update on the state of the application when events are fired.
They must return a new state when performing an update.

```php
use Phlux\Listener\Listener;
use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface;

class SubscribersListener extends Listener
{
    public function handle(StateInterface $state, EventInterface $event)
    {
        switch ($event->getIdentifier()) {
            case UserSubscribed::class:
                $subscribers = array_merge($state->get('subscribers'), $event->getPayload());
                return $state->set('subscribers', $subscribers);
        }
    }
}
```

### Dispatching Events

Phlux comes with a dispatcher that you can use to fire events as well as register listeners.
You may use any service with Phlux to queue events, there's an array queue to get you started:

```php
use Phlux\Phlux;
use Phlux\State\ArrayState as State;
use Phlux\Dispatcher\Dispatcher;
use Phlux\Pipeline\Pipeline;
use Phlux\Queue\ArrayQueue as Queue;

// Create a new initial state
$state = new State([
    'subscribers' => []
]);

$phlux = new Phlux($state, new Dispatcher(new Queue, new Pipeline));

$phlux->listen(new SubscribersListener);

$phlux->getState()->get('subscribers'); // Returns []

$phlux->fire(new UserSubscribed(['name' => 'Joe Blogs']));

$phlux->run();

$phlux->getState()->get('subscribers'); // Returns [['name' => 'Joe Blogs']]
```

### Middleware

Middleware in Phlux allows for third-party extension between dispatching an event and the listener.
State should not be modified by any middleware. One useful application for middleware is logging:

```php
use Phlux\Contracts\MiddlewareInterface;
use Phlux\Contracts\StateInterface;
use Phlux\Contracts\EventInterface

class LoggerMiddleware implements MiddlewareInterface
{
    public function __invoke(StateInterface $state, EventInterface $event, callable $next)
    {
        echo 'Event dispatched: ' . $event->getIdentifer();
    }
}

$phlux->middleware(new LoggerMiddleware);
```

### Observers

Observers differ from listeners in that they observe the state of the application directly and don't
respond specifically to events being fired. Observers are not able to modify the state of the application,
they must dispatch an event and let listeners it that instead.

```php
use Phlux\Observer\Observer;
use Phlux\Contracts\StateInterface;

class SubscribersChanged extends Observer
{
    public function notify(StateInterface $state)
    {
        echo 'Subscribers have changed!';
    }
}

$phlux->observe(new SubscribersChanged);

$phlux->fire(new UserSubscribed(['name' => 'Joe Blogs']));

$phlux->run(); // echos 'Subscribers have changed!'
```
