<?php

namespace Phlux\Observer;

use Phlux\Contracts\ObserverInterface;

abstract class Observer implements ObserverInterface
{
    /**
     * This observers unique identifer
     *
     * @var string
     */
    protected $id;

    /**
     * Get this observers unique
     *
     * @return string
     */
    public function getIdentifer()
    {
        return $this->id ?: static::class;
    }
}
