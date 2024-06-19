<?php

declare(strict_types=1);

namespace App\DataObjects;

use App\Models\ProbeTypes;

readonly class ProbesDataObject
{
    public function __construct(
        public string $name,
        public string $description,
        public ProbeTypes $probe_type_id,
    ) {}
}
