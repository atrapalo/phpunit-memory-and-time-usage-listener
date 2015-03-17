<?php

namespace PhpunitMemoryAndTimeUsageListener\Domain\Measurement;

class Measurement
{
    /** @var  float */
    private $quantity;
    /** @var  string */
    private $measurementUnit;

    public function __construct($quantity, $unit)
    {
        $this->quantity = $quantity;
        $this->measurementUnit = $unit;
    }

    public function quantity()
    {
        return $this->quantity;
    }
}