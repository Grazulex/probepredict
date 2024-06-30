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
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use ReflectionException;

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
     * @throws Exception
     */
    public function handle(): void
    {
        $rules = $this->probe->rules;
        if ($rules->isEmpty()) {
            $this->resetOnGoingStats();
        } else {
            foreach ($rules as $rule) {
                $NameStrategy = Str::ucfirst(Str::camel($rule->metric_type()->first()->name) . 'Strategy');
                try {
                    $strategy = new $NameStrategy();
                    $strategy->calculate($this->probe, $this->metric_type);
                } catch (ReflectionException $e) {
                    Log::error("Class {$NameStrategy} not found: " . $e->getMessage());
                } catch (Exception $e) {
                    Log::error("An error occurred: " . $e->getMessage());
                }
            }
        }
    }

    private function resetOnGoingStats(): void
    {
        $ongoing = new UpdateOnGoingProbeAction();
        $ongoing->handle(
            isAdding: false,
            probe: $this->probe,
        );
    }
}
