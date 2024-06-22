<?php

declare(strict_types=1);

namespace App\Actions\Probes;

use App\Models\Probe;

class DeleteProbeAction
{
    public function handle(Probe $probe): void
    {
        $probe->delete();
    }
}
