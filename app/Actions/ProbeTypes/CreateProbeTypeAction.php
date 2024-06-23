<?php

declare(strict_types=1);

namespace App\Actions\ProbeTypes;

use App\Models\ProbeType;

class CreateProbeTypeAction
{
    public function handle(array $input): ProbeType
    {
        return ProbeType::create($input);
    }
}
