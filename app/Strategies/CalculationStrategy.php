<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Models\MetricType;
use App\Models\Probe;

interface CalculationStrategy
{
    public function calculate(Probe $probes, MetricType $metricTypes): void;
}
