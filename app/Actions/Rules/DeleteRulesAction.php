<?php

declare(strict_types=1);

namespace App\Actions\Rules;

use App\Models\ProbeRules;

class DeleteRulesAction
{
    public function handle(ProbeRules $probeRules): void
    {
        $probeRules->delete();
    }
}
