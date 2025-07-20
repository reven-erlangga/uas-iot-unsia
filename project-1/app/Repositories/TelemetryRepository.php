<?php

namespace App\Repositories;

use App\Models\Telemetry;
use App\Repositories\Contracts\TelemetryRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class TelemetryRepository implements TelemetryRepositoryInterface
{
    protected $model;

    public function __construct(Telemetry $model)
    {
        $this->model = $model;
    }

    /**
     * Get all telemetries with optional pagination
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getAll(?int $perPage = null)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        
        if ($perPage) {
            return $query->paginate($perPage);
        }
        
        return $query->get();
    }

    /**
     * Find telemetry by ID
     *
     * @param string $id
     * @return Telemetry|null
     */
    public function findById(string $id): ?Telemetry
    {
        return $this->model->find($id);
    }

    /**
     * Create new telemetry record
     *
     * @param array $data
     * @return Telemetry
     */
    public function create(array $data): Telemetry
    {
        return $this->model->create($data);
    }

    /**
     * Update telemetry record
     *
     * @param string $id
     * @param array $data
     * @return Telemetry|null
     */
    public function update(string $id, array $data): ?Telemetry
    {
        $telemetry = $this->findById($id);
        
        if (!$telemetry) {
            return null;
        }
        
        $telemetry->update($data);
        return $telemetry->fresh();
    }

    /**
     * Delete telemetry record
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool
    {
        $telemetry = $this->findById($id);
        
        if (!$telemetry) {
            return false;
        }
        
        return $telemetry->delete();
    }

    /**
     * Get telemetries by station ID
     *
     * @param string $stationId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByStationId(string $stationId, ?int $perPage = null)
    {
        $query = $this->model->where('station_id', $stationId)
                            ->orderBy('created_at', 'desc');
        
        if ($perPage) {
            return $query->paginate($perPage);
        }
        
        return $query->get();
    }

    /**
     * Get telemetries by device ID
     *
     * @param string $deviceId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByDeviceId(string $deviceId, ?int $perPage = null)
    {
        $query = $this->model->where('device_id', $deviceId)
                            ->orderBy('created_at', 'desc');
        
        if ($perPage) {
            return $query->paginate($perPage);
        }
        
        return $query->get();
    }

    /**
     * Get latest telemetries
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection
    {
        return $this->model->orderBy('created_at', 'desc')
                          ->limit($limit)
                          ->get();
    }

    /**
     * Get paginated telemetries with custom page name
     *
     * @param int $perPage
     * @param string $pageName
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator
    {
        return $this->model->latest()->paginate($perPage, $columns, $pageName, $page);
    }

    /**
     * Get monitoring data including last update, average temperature, average humidity, and device count
     *
     * @return array
     */
    public function getMonitoringData(): array
    {
        // Get last update (most recent telemetry record)
        $lastUpdate = $this->model->latest()->first();

        // Get average temperature
        $avgTemperature = $this->model->avg('temperature');

        // Get average humidity
        $avgHumidity = $this->model->avg('humidity');

        // Get unique device count (group by device_name)
        $deviceCount = $this->model->distinct('device_name')->count('device_name');

        return [
            'last_update' => $lastUpdate ? $lastUpdate->created_at : null,
            'avg_temperature' => round($avgTemperature, 2),
            'avg_humidity' => round($avgHumidity, 2),
            'device_count' => $deviceCount,
        ];
    }

    /**
     * Get chart data for temperature and humidity over time
     *
     * @param string|null $date
     * @param string|null $deviceName
     * @param int $interval
     * @return array
     */
    public function getChartData(?string $date = null, ?string $deviceName = null, int $interval = 30): array
    {
        // Get all telemetries for the date and device
        $query = $this->model->select('temperature', 'humidity', 'created_at', 'device_id', 'device_name');

        // Filter by date if provided
        if ($date) {
            $query->whereDate('created_at', $date);
        }

        // Filter by device name if provided (and not empty)
        if ($deviceName && $deviceName !== '') {
            $query->where('device_name', $deviceName);
        }

        $telemetries = $query->orderBy('created_at', 'asc')->get();

        // Group data by interval using Carbon
        $groupedData = [];
        
        foreach ($telemetries as $telemetry) {
            $createdAt = \Carbon\Carbon::parse($telemetry->created_at);
            
            // Calculate interval group
            $intervalGroup = match($interval) {
                15 => floor($createdAt->minute / 15),
                30 => floor($createdAt->minute / 30),
                60 => 0, // Group by hour only
                default => floor($createdAt->minute / 30)
            };
            
            // Create group key based on device name and time
            $groupKey = $telemetry->device_name . '-' . $createdAt->format('Y-m-d-H') . '-' . $intervalGroup;
            
            if (!isset($groupedData[$groupKey])) {
                $groupedData[$groupKey] = [
                    'hour' => $createdAt->hour,
                    'minute' => $intervalGroup,
                    'temperatures' => [],
                    'humidities' => [],
                    'device_id' => $telemetry->device_id,
                    'device_name' => $telemetry->device_name
                ];
            }
            
            $groupedData[$groupKey]['temperatures'][] = $telemetry->temperature ?? 0;
            $groupedData[$groupKey]['humidities'][] = $telemetry->humidity ?? 0;
        }

        // Sort by group key to maintain chronological order
        ksort($groupedData);

        $labels = [];
        $temperatureData = [];
        $humidityData = [];

        foreach ($groupedData as $groupKey => $group) {
            // Format time label based on interval
            $hour = str_pad($group['hour'], 2, '0', STR_PAD_LEFT);
            
            $minute = match($interval) {
                15 => str_pad($group['minute'] * 15, 2, '0', STR_PAD_LEFT),
                30 => $group['minute'] == 0 ? '00' : '30',
                60 => '00',
                default => $group['minute'] == 0 ? '00' : '30'
            };
            
            $labels[] = $hour . ':' . $minute;
            
            // Calculate averages
            $avgTemperature = count($group['temperatures']) > 0 ? array_sum($group['temperatures']) / count($group['temperatures']) : 0;
            $avgHumidity = count($group['humidities']) > 0 ? array_sum($group['humidities']) / count($group['humidities']) : 0;
            
            $temperatureData[] = round($avgTemperature, 1);
            $humidityData[] = round($avgHumidity, 1);
        }

        return [
            'labels' => $labels,
            'temperature_data' => $temperatureData,
            'humidity_data' => $humidityData,
            'devices' => $this->model->select('device_name')
                                   ->distinct()
                                   ->pluck('device_name')
                                   ->toArray(),
        ];
    }

    /**
     * Get available dates for chart data
     *
     * @return array
     */
    public function getAvailableDates(): array
    {
        return $this->model->selectRaw('DATE(created_at) as date')
                          ->distinct()
                          ->orderBy('date', 'desc')
                          ->pluck('date')
                          ->toArray();
    }
}