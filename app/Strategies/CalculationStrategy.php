<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Models\MetricType;
use App\Models\Probe;
use App\Models\ProbeMetric;
use Illuminate\Database\Eloquent\Collection;

interface CalculationStrategy
{
    public function calculate(Probe $probes, MetricType $metricTypes): void;

    public function calculateDiffPerSec(Collection $metrics, MetricType $metricTypes, Probe $probes): float;

    public function calculateTimeToCondition(ProbeMetric $probeMetric, float $condition, string $operator, float $diff_per_sec, int $quantity): float;

}
