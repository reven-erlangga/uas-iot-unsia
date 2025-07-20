<?php

namespace App\Repositories\Contracts;

use App\Models\Telemetry;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TelemetryRepositoryInterface
{
    /**
     * Get all telemetries with optional pagination
     *
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getAll(?int $perPage = null);

    /**
     * Find telemetry by ID
     *
     * @param string $id
     * @return Telemetry|null
     */
    public function findById(string $id): ?Telemetry;

    /**
     * Create new telemetry record
     *
     * @param array $data
     * @return Telemetry
     */
    public function create(array $data): Telemetry;

    /**
     * Update telemetry record
     *
     * @param string $id
     * @param array $data
     * @return Telemetry|null
     */
    public function update(string $id, array $data): ?Telemetry;

    /**
     * Delete telemetry record
     *
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;

    /**
     * Get telemetries by station ID
     *
     * @param string $stationId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByStationId(string $stationId, ?int $perPage = null);

    /**
     * Get telemetries by device ID
     *
     * @param string $deviceId
     * @param int|null $perPage
     * @return LengthAwarePaginator|Collection
     */
    public function getByDeviceId(string $deviceId, ?int $perPage = null);

    /**
     * Get latest telemetries
     *
     * @param int $limit
     * @return Collection
     */
    public function getLatest(int $limit = 10): Collection;

    /**
     * Get paginated telemetries
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function paginate($perPage = 15, $columns = ['*'], $pageName = 'page', $page = null): LengthAwarePaginator;

    /**
     * Get monitoring data including last update, average temperature, average humidity, and device count
     *
     * @return array
     */
    public function getMonitoringData(): array;

    /**
     * Get chart data for temperature and humidity over time
     *
     * @param string|null $date
     * @param string|null $deviceName
     * @param int $interval
     * @return array
     */
    public function getChartData(?string $date = null, ?string $deviceName = null, int $interval = 30): array;

    /**
     * Get available dates for chart data
     *
     * @return array
     */
    public function getAvailableDates(): array;
}