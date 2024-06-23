<?php

declare(strict_types=1);

namespace App\Actions\ProbeTypes;

use App\Models\ProbeType;

class DeleteProbeTypeAction
{
    public function handle(ProbeType $probeType): void
    {
        $probeType->delete();
    }
}
