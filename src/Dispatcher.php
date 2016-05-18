<?php

namespace Phlux;

use Phlux\Contracts\QueueInterface;
use Phlux\Contracts\StoreInterface;
use Phlux\Contracts\PipelineInterface;
use Phlux\Contracts\EventInterface;

class Dispatcher
{
    /**
     * The queue where all events are managed
     *
     * @var Phlux\Contracts\QueueInterface
     */
    protected $queue;

    /**
     * The pipeline through which all events are handled
     *
     * @var Phlux\Contracts\PipelineInterface
     */
    protected $pipeline;

    /**
     * The application store
     *
     * @var Phlux\Contracts\StoreInterface
     */
    protected $store;

    /**
     * Create a new queue
     *
     * @param Phlux\Contracts\QueueInterface $queue
     * @param Phlux\Contracts\StoreInterface $store
     * @param Phlux\Contracts\PipelineInterface $pipeline
     * @return void
     */
    public function __construct(QueueInterface $queue, StoreInterface $store, PipelineInterface $pipeline)
    {
        $this->queue = $queue;
        $this->store = $store;
        $this->pipeline = $pipeline;
    }

    /**
     * Fires a new event on the store
     *
     * @param Phlux\Contracts\QueueInterface $event
     * @return Phlux\Contracts\StoreInterface
     */
    public function fire(EventInterface $event)
    {
        $this->queue->push($event);
    }

    /**
     * Process the queue and set the state to whatever it returns
     *
     * @return void
     */
    public function run()
    {
        $event = $this->queue->next();

        $this->pipeline
            ->pass($this->store, $event)
            ->then(function($state, $event)
            {
                return $state;
            });
    }
}
