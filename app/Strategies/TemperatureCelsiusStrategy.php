<?php

declare(strict_types=1);

namespace App\Strategies;

use App\Models\MetricType;
use App\Models\Probe;
use App\Models\ProbeMetric;

final class TemperatureCelsiusStrategy implements CalculationStrategy
{
    public function calculate(Probe $probes, MetricType $metricTypes): void
    {
        $rule = $probes->rules->where('metric_type_id', $metricTypes->id)->first();
        if (null === $rule) {
            $condition = 0;
            $operator = '=';
        } else {
            $condition = $rule->condition;
            $operator = $rule->operator;
        }
        $metrics = $probes->metrics->where('metric_type_id', $metricTypes->id)->sortBy('created_at');
        $diff_per_sec = 0;
        $quantity = $metrics->count();
        foreach ($metrics as $metric) {
            $firstMetric = $metric;
            $nextMetric = ProbeMetric::where('id', '>', $metric->id)
                ->where('metric_type_id', $metricTypes->id)
                ->where('probe_id', $probes->id)
                ->orderBy('id', 'asc')
                ->first();
            if (null !== $nextMetric) {
                $time_difference = ($firstMetric->created_at->diffInSeconds($nextMetric->created_at));
                if ($nextMetric->value < $firstMetric->value) {
                    $value_difference = $firstMetric->value - $nextMetric->value;
                    $diff_per_sec = $diff_per_sec + ($value_difference / $time_difference);
                } else {
                    $value_difference = $nextMetric->value - $firstMetric->value;
                    $diff_per_sec = $diff_per_sec - ($value_difference / $time_difference);
                }
            }
        }
        $diff_per_sec = $diff_per_sec / $quantity;
        if ($quantity > 1 && $diff_per_sec > 0) {
            $time_to_condition = 0;
            $probeMetric = $metrics->last();
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
            if (null !== $rule) {
                $rule->estimation = $probeMetric->created_at->addSeconds($time_to_condition);
                $rule->save();
            }
        }
    }
}
