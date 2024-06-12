<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Models\MetricTypes;
use App\Models\Probes;

interface CalculationStrategy
{
    public function calculate(Probes $probes, MetricTypes $metricTypes): void;
}
