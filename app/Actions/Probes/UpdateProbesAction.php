<?php

declare(strict_types=1);

namespace App\Actions\Probes;

use App\Models\Probes;

class UpdateProbesAction
{
    public function handle(array $input, Probes $probes): Probes
    {
        $probes->update($input);

        return $probes;
    }
}
