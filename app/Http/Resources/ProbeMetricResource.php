<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class ProbeMetricResource extends JsonResource
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
            'metric_type' => new MetricTypeResource($this->metric_type),
            'value' => $this->value,
            'created_at' => new DateTimeResource($this->created_at),
        ];
    }
}
