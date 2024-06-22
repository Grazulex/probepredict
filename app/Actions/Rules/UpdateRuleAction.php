<?php

declare(strict_types=1);

namespace App\Actions\Rules;

use App\Models\ProbeRule;

class UpdateRuleAction
{
    public function handle(array $input, ProbeRule $probeRule): ProbeRule
    {
        $probeRule->update($input);

        return $probeRule;
    }
}
