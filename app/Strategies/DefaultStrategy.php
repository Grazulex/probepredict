<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Models\MetricType;
use App\Models\Probe;
use App\Models\ProbeMetric;
use Illuminate\Database\Eloquent\Collection;

class DefaultStrategy implements CalculationStrategy
{
    public function calculate(Probe $probes, MetricType $metricTypes): void
    {
        $rule = $probes->rules->firstWhere('metric_type_id', $metricTypes->id);
        $metrics = $probes->metrics->where('metric_type_id', $metricTypes->id)->sortBy('created_at');
        $quantity = $metrics->count();
        $diff_per_sec = $this->calculateDiffPerSec(
            metrics: $metrics,
            metricTypes: $metricTypes,
            probes: $probes,
        );
        if ($quantity > 1 && $diff_per_sec > 0) {
            $time_to_condition = $this->calculateTimeToCondition(
                probeMetric: $metrics->last(),
                condition: $rule->condition ?? 0,
                operator: $rule->operator ?? '=',
                diff_per_sec: $diff_per_sec,
                quantity: $quantity,
            );
            if (null !== $rule) {
                $rule->estimation = $metrics->last()->created_at->addSeconds($time_to_condition);
                $rule->save();
            }
        }
    }

    public function calculateDiffPerSec(Collection $metrics, MetricType $metricTypes, Probe $probes): float
    {
        $diff_per_sec = 0;
        $quantity = $metrics->count();
        foreach ($metrics as $metric) {
            $nextMetric = ProbeMetric::where('id', '>', $metric->id)
                ->where('metric_type_id', $metricTypes->id)
                ->where('probe_id', $probes->id)
                ->orderBy('id', 'asc')
                ->first();
            if (null !== $nextMetric) {
                $time_difference = ($metric->created_at->diffInSeconds($nextMetric->created_at));
                $value_difference = abs($nextMetric->value - $metric->value);
                $diff_per_sec += $value_difference / $time_difference;
            }
        }
        return $diff_per_sec / $quantity;
    }

    public function calculateTimeToCondition(ProbeMetric $probeMetric, float $condition, string $operator, float $diff_per_sec, int $quantity): float
    {
        $time_to_condition = 0;
        switch ($operator) {
            case '>':
                $time_to_condition = ($condition - $probeMetric->value) / ($diff_per_sec / $quantity);
                break;
            case '<':
                $time_to_condition = ($probeMetric->value - $condition) / ($diff_per_sec / $quantity);
                break;
            case '=':
                $time_to_condition = abs($condition - $probeMetric->value) / ($diff_per_sec / $quantity);
                break;
        }
        return $time_to_condition;
    }
}
