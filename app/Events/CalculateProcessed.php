<?php

declare(strict_types=1);

namespace App\Events;

use App\Models\MetricTypes;
use App\Models\ProbeMetrics;
use App\Models\Probes;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CalculateProcessed
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(Probes $probes, MetricTypes $metricTypes)
    {
        //get rule for this metric
        $rule = $probes->rules->where('metric_type_id', $metricTypes->id)->first();
        if ($rule === null) {
            $condition = 0;
            $operator = '=';
        } else {
            $condition = $rule->condition;
            $operator = $rule->operator;
        }
        $metrics = $probes->metrics->where('metric_type_id', $metricTypes->id)->sortBy('created_at');
        $diff_per_sec = 0;
        $quantity = 0;
        foreach ($metrics as $metric) {
            $quantity = $metrics->count();
            $firstMetric = $metric;
            $nextMetric = ProbeMetrics::where('id', '>', $metric->id)
                ->where('metric_type_id', $metricTypes->id)
                ->where('probe_id', $probes->id)
                ->orderBy('id', 'asc')
                ->first();
            if ($nextMetric !== null) {
                $time_difference = ($firstMetric->created_at->diffInSeconds($nextMetric->created_at));
                $value_difference = abs($nextMetric->value - $firstMetric->value);
                $diff_per_sec = $diff_per_sec + ($value_difference / $time_difference);
            }
        }
        if ($quantity > 1) {
            $time_to_condition = 0;
            $probeMetric = $metrics->last();
            //echo "The average rate of change is: " . $diff_per_sec / $quantity;

            switch ($operator) {
                case '>':
                    $time_to_condition = ($condition - $probeMetric->value) / ($diff_per_sec / $quantity);
                    //echo "The metric will higher than $condition at: " . $probeMetric->created_at->addSeconds($time_to_condition);
                    break;
                case '<':
                    $time_to_condition = ($probeMetric->value - $condition) / ($diff_per_sec / $quantity);
                    //echo "The metric will lower than $condition at: " . $probeMetric->created_at->addSeconds($time_to_condition);
                    break;
                case '=':
                    $time_to_condition = abs($condition - $probeMetric->value) / ($diff_per_sec / $quantity);
                    //echo "The metric will reach $condition at: " . $probeMetric->created_at->addSeconds($time_to_condition);
                    break;
            }
            if ($rule !== null) {
                $rule->estimation = $probeMetric->created_at->addSeconds($time_to_condition);
                $rule->save();
            }
        }
    }
}
