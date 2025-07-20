<?php

namespace App\Http\Resources\Telemetry;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ItemResource extends JsonResource
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
            'stationId' => $this->station_id,
            'deviceId' => $this->device_id,
            'deviceName' => $this->device_name,
            'temperature' => $this->temperature,
            'temperatureTrend' => $this->temperature_trend,
            'humidity' => $this->humidity,
            'humidityTrend' => $this->humidity_trend,
            'createdAt' => $this->created_at?->toDateTimeString(),
            'updatedAt' => $this->updated_at?->toDateTimeString(),
        ];
    }
}
