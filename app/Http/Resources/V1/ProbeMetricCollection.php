<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class ProbeMetricCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => $this->collection,
            'links' => [
                'prev' => $this->resource->previousPageUrl(),
                'self' => route('api.probes.metrics', ['probe' => $this->resource->first()->probe_id]),
                'next' => $this->resource->nextPageUrl(),
            ],
        ];
    }
}
