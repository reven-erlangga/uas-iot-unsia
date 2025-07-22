<?php

namespace App\Http\Resources\Telemetry;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MonitoringResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'lastUpdate' => $this->resource['last_update'] 
                ? \Carbon\Carbon::parse($this->resource['last_update'])->format('d M Y H:i:s')
                : null,
            'avgTemperature' => $this->resource['avg_temperature'],
            'avgHumidity' => $this->resource['avg_humidity'],
            'deviceCount' => $this->resource['device_count'],
        ];
    }
}
