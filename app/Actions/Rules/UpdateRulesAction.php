<?php

declare(strict_types=1);

namespace App\Actions\Rules;

use App\Models\ProbeRules;

class UpdateRulesAction
{
    public function handle(array $input, ProbeRules $probeRules): ProbeRules
    {
        $probeRules->update($input);

        return $probeRules;
    }
}
