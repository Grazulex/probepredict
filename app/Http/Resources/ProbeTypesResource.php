<?php

declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\ProbeTypes;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProbeTypes */
final class ProbeTypesResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
        ];
    }
}
