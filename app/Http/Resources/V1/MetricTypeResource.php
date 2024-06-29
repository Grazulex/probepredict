<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use App\Models\MetricType;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin MetricType */
final class MetricTypeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'metric_type',
            'id' => $this->id,
            'attributes' => [
                'name' => $this->name,
                'description' => $this->description,
                'unit' => $this->unit,
            ],
            'links' => [
                'self' => route('api.metric-types.show', ['metric_type' => $this->id]),
            ],
        ];
    }
}
