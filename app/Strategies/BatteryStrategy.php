<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Models\MetricTypes;
use App\Models\Probes;

class BatteryStrategy implements CalculationStrategy
{
    public function calculate(Probes $probes, MetricTypes $metricTypes): void
    {
        // TODO: Implement calculate() method.
    }
}
