<?php

namespace App\Http\Resources\Telemetry;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChartDataResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'labels' => $this->resource['labels'],
            'temperatureData' => $this->resource['temperature_data'],
            'humidityData' => $this->resource['humidity_data'],
            'devices' => $this->resource['devices'],
        ];
    }
} 