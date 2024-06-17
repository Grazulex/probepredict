<?php

declare(strict_types=1);

namespace App\Actions\Probes;

use App\Models\Probes;

class UpdateOnGoingProbesAction
{
    public function handle(bool $isAdding, Probes $probes): void
    {
        if ($isAdding) {
            $probes->stats_ongoing = $probes->stats_ongoing + 1;
        } else {
            $probes->stats_ongoing = $probes->stats_ongoing - 1;
        }
        $probes->save();
    }
}
