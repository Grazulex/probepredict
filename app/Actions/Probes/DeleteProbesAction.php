<?php

declare(strict_types=1);

namespace App\Actions\Probes;

use App\Models\Probes;

class DeleteProbesAction
{
    public function handle(Probes $probes): void
    {
        $probes->delete();
    }
}
