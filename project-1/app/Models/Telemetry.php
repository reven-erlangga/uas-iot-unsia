<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Crypt;

class Telemetry extends Model
{
    use HasUuids;

    protected $fillable = [
        'station_id',
        'temperature',
        'humidity',
        'device_id',
        'device_name',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
        'temperature' => 'double',
        'humidity' => 'double',
    ];

    public function stationId(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_null($value)) {
                    return null;
                }
                
                try {
                    return Crypt::decryptString($value);
                } catch (\Exception $e) {
                    // If decryption fails, return the original value
                    return $value;
                }
            },
            set: fn ($value) => is_null($value) ? null : Crypt::encryptString($value),
        );
    }

    public function deviceId(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if (is_null($value)) {
                    return null;
                }
                
                try {
                    return Crypt::decryptString($value);
                } catch (\Exception $e) {
                    // If decryption fails, return the original value
                    return $value;
                }
            },
            set: fn ($value) => is_null($value) ? null : Crypt::encryptString($value),
        );
    }

    public function metadata(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => is_null($value) ? null : json_decode($value, true),
            set: fn ($value) => json_encode($value),
        );
    }

    public function temperatureTrend(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Ambil data sebelumnya berdasarkan device_id dan station_id yang sama
                $previousData = static::where('device_name', $this->device_name)
                    ->latest()
                    ->first();

                if (!$previousData || is_null($this->temperature) || is_null($previousData->temperature)) {
                    return 'stable';
                }

                if ($this->temperature > $previousData->temperature) {
                    return 'up';
                } elseif ($this->temperature < $previousData->temperature) {
                    return 'down';
                } else {
                    return 'stable';
                }
            }
        );
    }

    public function humidityTrend(): Attribute
    {
        return Attribute::make(
            get: function () {
                // Ambil data sebelumnya berdasarkan device_id dan station_id yang sama
                $previousData = static::where('device_name', $this->device_name)
                    ->latest()
                    ->first();

                if (!$previousData || is_null($this->humidity) || is_null($previousData->humidity)) {
                    return 'stable';
                }

                if ($this->humidity > $previousData->humidity) {
                    return 'up';
                } elseif ($this->humidity < $previousData->humidity) {
                    return 'down';
                } else {
                    return 'stable';
                }
            }
        );
    }
}
