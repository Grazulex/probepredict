<?php

declare(strict_types=1);

namespace App\Actions\ProbeTypes;

use App\Models\ProbeType;

class UpdateProbeTypeAction
{
    public function handle(array $input, ProbeType $probeType): ProbeType
    {
        $probeType->update($input);

        return $probeType;
    }
}
