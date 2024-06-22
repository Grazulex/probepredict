<?php

declare(strict_types=1);

namespace App\Actions\Probes;

use App\Models\Probe;

class UpdateProbeAction
{
    public function handle(array $input, Probe $probe): Probe
    {
        $probe->update($input);

        return $probe;
    }
}
