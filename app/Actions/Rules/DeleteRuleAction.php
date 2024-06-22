<?php

declare(strict_types=1);

namespace App\Actions\Rules;

use App\Models\ProbeRule;

class DeleteRuleAction
{
    public function handle(ProbeRule $probeRule): void
    {
        $probeRule->delete();
    }
}
