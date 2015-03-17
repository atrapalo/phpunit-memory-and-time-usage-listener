<?php

namespace PhpunitMemoryAndTimeUsageListener\Domain\Measurement;

class TimeMeasurement extends Measurement
{
    public function __construct($quantity)
    {
        parent::__construct($quantity, $this->measurementUnit());
    }

    public function measurementUnit()
    {
        return 'microsecond';
    }

    /**
     * @return int
     */
    public function timeInMilliseconds()
    {
        return $this->toMilliseconds($this->quantity());
    }

    /**
     * Convert \PHPUnit's reported test time (microseconds) to milliseconds.
     *
     * @param  float $time
     * @return int
     */
    protected function toMilliseconds($time)
    {
        return (int) round($time * 1000);
    }
}