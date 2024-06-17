<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Models\MetricTypes;
use App\Models\Probes;
use App\Models\ProbeTypes;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Probes $probe, protected ProbeTypes $probe_type, protected MetricTypes $metric_type) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->probe_type->getCalculationStrategy()->calculate($this->probe, $this->metric_type);
    }
}
