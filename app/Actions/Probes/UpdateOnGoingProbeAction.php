<?php

declare(strict_types=1);

namespace App\Actions\Probes;

use App\Models\Probe;

class UpdateOnGoingProbeAction
{
    public function handle(bool $isAdding, Probe $probe): void
    {
        if ($isAdding) {
            $probe->stats_ongoing = $probe->stats_ongoing + 1;
        } else {
            $probe->stats_ongoing = $probe->stats_ongoing - 1;
        }
        $probe->save();
    }
}
