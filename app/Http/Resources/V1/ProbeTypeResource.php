<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\ProbeType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin ProbeType */
final class ProbeTypeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'type' => 'probe_type',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'description' => $this->description,
            ],
            'links' => [
                'self' => route('api.probe-types.show', ['probe_type' => $this->id]),
            ],
        ];
    }
}
