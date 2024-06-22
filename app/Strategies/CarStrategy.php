<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Models\MetricType;
use App\Models\Probe;

final class CarStrategy implements CalculationStrategy
{
    public function calculate(Probe $probes, MetricType $metricTypes): void
    {
        // TODO: Implement calculate() method.
    }
}
