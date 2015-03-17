<?php

namespace PhpunitMemoryAndTimeUsageListener\Domain\Measurement;

class MemoryMeasurement extends Measurement
{
    public function __construct($quantity)
    {
        parent::__construct($quantity, $this->measurementUnit());
    }

    public function measurementUnit()
    {
        return 'bytes';
    }

    /**
     * @return int
     */
    public function memoryInKiloBytes()
    {
        return $this->toKiloBytes($this->quantity());
    }

    /**
     * @param $bytes
     * @return float
     */
    protected function toKiloBytes($bytes)
    {
        return (float) round(($bytes / 1024), 2);
    }
}