<?php

declare(strict_types=1);

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

final class DateTimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $date = Carbon::parse($this->resource);

        return [
            'timestamp' => $date->timestamp,
            'utc' => $date->toISOString(),
            'human' => $date->diffForHumans(),
            'timezone' => $date->timezoneName,
            'parts' => [
                'date' => $date->toDateString(),
                'time' => $date->toTimeString(),
                'day' => $date->day,
                'month' => $date->month,
                'year' => $date->year,
                'hour' => $date->hour,
                'minute' => $date->minute,
                'second' => $date->second,
            ],
        ];
    }
}
