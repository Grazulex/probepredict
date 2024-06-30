<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\Probes\UpdateOnGoingProbeAction;
use App\Models\MetricType;
use App\Models\Probe;
use Exception;
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
    public function __construct(protected Probe $probe, protected MetricType $metric_type, protected UpdateOnGoingProbeAction $updateOnGoingProbesAction) {}

    /**
     * Execute the job.
     * @throws Exception
     */
    public function handle(): void
    {
        $rules = $this->probe->rules;
        if ($rules->isEmpty()) {
            $this->resetOnGoingStats();
        } else {
            foreach ($rules as $rule) {
                $NameStrategy = Str::camel($rule->metric_type()->first()->name) . 'Strategy';
                if (class_exists('App\\Strategies\\' . $NameStrategy)) {
                    $strategy = new $NameStrategy();
                    $strategy->calculate($this->probe, $this->metric_type);
                } else {
                    // If the strategy does not exist, return error of job
                    throw new Exception('Strategy ' . $NameStrategy . ' not found');
                }
            }
        }
    }

    private function resetOnGoingStats(): void
    {
        $this->updateOnGoingProbesAction->handle(
            isAdding: false,
            probe: $this->probe,
        );
    }
}
