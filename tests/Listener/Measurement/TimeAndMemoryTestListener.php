<?php

namespace PhpunitMemoryAndTimeUsageListener\Listener\Measurement;

use PHPUnit\Framework\TestCase;
use PhpunitMemoryAndTimeUsageListener\Domain\Measurement\MemoryMeasurement;
use PhpunitMemoryAndTimeUsageListener\Domain\Measurement\TestMeasurement;
use PhpunitMemoryAndTimeUsageListener\Domain\Measurement\TimeMeasurement;
use PHPUnit\Framework\TestListener;
use PHPUnit\Framework\TestSuite;
use PHPUnit\Framework\TestListenerDefaultImplementation;
use PHPUnit\Framework\Test;

class TimeAndMemoryTestListener implements TestListener
{

  use TestListenerDefaultImplementation;

  /** @var int  */
    protected $testSuitesRunning = 0;

    /** @var TestMeasurement[] */
    protected $testMeasurementCollection;

    /** @var bool  */
    protected $showOnlyIfEdgeIsExceeded = false;

    /**
     * Time in milliseconds we consider a test has a need to see if a refactor is needed
     * @var int
     */
    protected $executionTimeEdge = 100;

    /**
     * Memory bytes usage we consider a test has a need to see if a refactor is needed
     * @var int
     */
    protected $memoryUsageEdge = 1024;

    /**
     * Memory bytes usage we consider a test has a need to see if a refactor is needed
     * @var int
     */
    protected $memoryPeakDifferenceEdge = 1024;

    /**
     * @var TimeMeasurement
     */
    protected $executionTime;
    protected $memoryUsage;
    protected $memoryPeakIncrease;

    public function __construct($configurationOptions = array())
    {
        $this->setConfigurationOptions($configurationOptions);
    }

    /**
     * @param Test $test
     */
    public function startTest(Test $test): void
    {
        $this->memoryUsage = memory_get_usage();
        $this->memoryPeakIncrease = memory_get_peak_usage();
    }

    /**
     * @param Test $test
     * @param $time
     */
    public function endTest(Test $test, float $time): void
    {
        $this->executionTime = new TimeMeasurement($time);
        $this->memoryUsage = memory_get_usage() - $this->memoryUsage;
        $this->memoryPeakIncrease = memory_get_peak_usage() - $this->memoryPeakIncrease;

        if ($this->haveToSaveTestMeasurement()) {
            $name = ($test instanceof TestCase) ? $test->getName() : '';
            $this->testMeasurementCollection[] = new TestMeasurement(
                $name,
                get_class($test),
                $this->executionTime,
                new MemoryMeasurement($this->memoryUsage),
                new MemoryMeasurement($this->memoryPeakIncrease)
            );
        }
    }

    /**
     * @param TestSuite $suite
     */
    public function startTestSuite(TestSuite $suite): void
    {
        $this->testSuitesRunning++;
    }

    /**
     * @param TestSuite $suite
     */
    public function endTestSuite(TestSuite $suite): void
    {
        $this->testSuitesRunning--;

        if ((0 === $this->testSuitesRunning) && (0 < count($this->testMeasurementCollection))) {
            echo PHP_EOL . 'Time & Memory measurement results: ' . PHP_EOL;
            $i = 1;
            foreach ($this->testMeasurementCollection as $testMeasurement) {
                echo PHP_EOL . $i . ' - ' . $testMeasurement->measuredInformationMessage();
                $i++;
            }
        }
    }

    /**
     * @return bool
     */
    protected function haveToSaveTestMeasurement(): bool
    {
        return ((false === $this->showOnlyIfEdgeIsExceeded)
            || ((true === $this->showOnlyIfEdgeIsExceeded)
                && ($this->isAPotentialCriticalTimeUsage()
                || $this->isAPotentialCriticalMemoryUsage()
                || $this->isAPotentialCriticalMemoryPeakUsage()
                )
            )
        );
    }

    /**
     * Check if test execution time is critical so we need to check it out
     *
     * @return bool
     */
    protected function isAPotentialCriticalTimeUsage(): bool
    {
        return $this->checkEdgeIsOverTaken($this->executionTime->timeInMilliseconds(), $this->executionTimeEdge);
    }

    /**
     * Check if test execution memory usage is critical so we need to check it out
     *
     * @return bool
     */
    protected function isAPotentialCriticalMemoryUsage(): bool
    {
        return $this->checkEdgeIsOverTaken($this->memoryUsage, $this->memoryUsageEdge);
    }

    /**
     * Check if test execution memory peak usage is critical so we need to check it out
     *
     * @return bool
     */
    protected function isAPotentialCriticalMemoryPeakUsage(): bool
    {
        return $this->checkEdgeIsOverTaken($this->memoryPeakIncrease, $this->memoryPeakDifferenceEdge);
    }

    /**
     * @param $value
     * @param $edgeValue
     * @return bool
     */
    protected function checkEdgeIsOverTaken($value, $edgeValue): bool
    {
        return ($value >= $edgeValue);
    }

    /**
     * @param $configurationOptions
     */
    protected function setConfigurationOptions($configurationOptions): void
    {
        if (isset($configurationOptions['showOnlyIfEdgeIsExceeded'])) {
            $this->showOnlyIfEdgeIsExceeded = $configurationOptions['showOnlyIfEdgeIsExceeded'];
        }

        if (isset($configurationOptions['executionTimeEdge'])) {
            $this->executionTimeEdge = $configurationOptions['executionTimeEdge'];
        }

        if (isset($configurationOptions['memoryUsageEdge'])) {
            $this->memoryUsageEdge = $configurationOptions['memoryUsageEdge'];
        }

        if (isset($configurationOptions['memoryPeakDifferenceEdge'])) {
            $this->memoryPeakDifferenceEdge = $configurationOptions['memoryPeakDifferenceEdge'];
        }
    }
}
