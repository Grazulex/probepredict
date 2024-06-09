<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProbeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'probe_type' => new ProbeTypeResource($this->probeType),
            'created_at' => new DateTimeResource($this->created_at),
            'updated_at' => new DateTimeResource($this->updated_at),
        ];
    }
}
