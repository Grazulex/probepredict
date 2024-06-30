<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\MetricType;
use App\Models\Probe;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class CalculateJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Probe $probe, protected MetricType $metric_type) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $rules = $this->probe->rules;
        if ($rules->isEmpty()) {
            $this->resetOnGoingStats();
        }
        foreach ($rules as $rule) {
            $NameStrategy =  Str::camel($rule->metric_type()->name) . 'Strategy';
            if (class_exists('App\Strategies\\' . $NameStrategy)) {
                $strategy = new $NameStrategy();
                $strategy->calculate($this->probe, $this->metric_type);
            } else {
                $this->resetOnGoingStats();
            }
        }
    }

    private function resetOnGoingStats(): void
    {
        $this->probe->stats_ongoing = $this->probe->stats_ongoing - 1;
        $this->probe->save();
    }
}
