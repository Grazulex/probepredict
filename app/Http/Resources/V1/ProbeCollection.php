<?php

declare(strict_types=1);

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

final class ProbeCollection extends ResourceCollection
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray(Request $request): array
    {
        return [
            'items' => $this->collection,
            'links' => [
                'prev' => $this->resource->previousPageUrl(),
                'self' => route('api.probes.list'),
                'next' => $this->resource->nextPageUrl(),
            ],
        ];
    }
}
