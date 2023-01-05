<?php

namespace App\Service\Solution;

class SolutionConsumerCollection
{
    /**
     * @var iterable
     */
    private iterable $consumers;

    /**
     * SolutionConsumerCollection constructor.
     * @param iterable $consumers
     */
    public function __construct(iterable $consumers)
    {
        $this->consumers = $consumers;
    }

    /**
     * @return iterable
     */
    public function getConsumers(): iterable
    {
        return $this->consumers;
    }
}
