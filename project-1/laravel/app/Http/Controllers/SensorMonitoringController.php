<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Repositories\Contracts\TelemetryRepositoryInterface;
use App\Http\Resources\Telemetry\CollectionResource;
use App\Http\Resources\Telemetry\MonitoringResource;
use App\Http\Resources\Telemetry\ChartDataResource;

class SensorMonitoringController extends Controller
{
    protected $telemetryRepository;

    public function __construct(TelemetryRepositoryInterface $telemetryRepository)
    {
        $this->telemetryRepository = $telemetryRepository;
    }

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $page = $request->get('page', 1);
        $date = $request->get('chart_date', date('Y-m-d'));
        $deviceName = $request->get('chart_device_name');
        $interval = $request->get('chart_interval', 30);
        
        $telemetries = $this->telemetryRepository->paginate($perPage, ['*'], 'page', $page);
        $monitoringTelemetry = $this->telemetryRepository->getMonitoringData();
        $chartData = $this->telemetryRepository->getChartData($date, $deviceName, $interval);
        $availableDates = $this->telemetryRepository->getAvailableDates();

        return Inertia::render('SensorMonitoring', [
            'appName' => 'IoT Sensor Monitoring',
            'telemetries' => new CollectionResource($telemetries),
            'monitoringTelemetry' => new MonitoringResource($monitoringTelemetry)->resolve(),
            'chartData' => new ChartDataResource($chartData)->resolve(),
            'availableDates' => $availableDates,
        ]);
    }
}