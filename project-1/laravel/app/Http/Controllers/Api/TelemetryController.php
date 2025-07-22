<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Telemetry\StoreRequest;
use App\Http\Requests\Telemetry\UpdateRequest;
use App\Repositories\Contracts\TelemetryRepositoryInterface;

class TelemetryController extends Controller
{
    protected $telemetryRepository;

    public function __construct(TelemetryRepositoryInterface $telemetryRepository)
    {
        $this->telemetryRepository = $telemetryRepository;
    }

    /**
     * Display a listing of the telemetries.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->get('per_page', 15);
            $stationId = $request->get('station_id');
            $deviceId = $request->get('device_id');

            if ($stationId) {
                $telemetries = $this->telemetryRepository->getByStationId($stationId, $perPage);
            } elseif ($deviceId) {
                $telemetries = $this->telemetryRepository->getByDeviceId($deviceId, $perPage);
            } else {
                $telemetries = $this->telemetryRepository->getAll($perPage);
            }

            return response()->json([
                'success' => true,
                'message' => 'Telemetries retrieved successfully',
                'data' => $telemetries
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve telemetries',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created telemetry in storage.
     *
     * @param StoreRequest $request
     * @return JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        try {
            $telemetry = $this->telemetryRepository->create($request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Telemetry created successfully',
                'data' => $telemetry
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create telemetry',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified telemetry.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        try {
            $telemetry = $this->telemetryRepository->findById($id);

            if (!$telemetry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Telemetry not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Telemetry retrieved successfully',
                'data' => $telemetry
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve telemetry',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified telemetry in storage.
     *
     * @param UpdateRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(UpdateRequest $request, string $id): JsonResponse
    {
        try {
            $telemetry = $this->telemetryRepository->update($id, $request->validated());

            if (!$telemetry) {
                return response()->json([
                    'success' => false,
                    'message' => 'Telemetry not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Telemetry updated successfully',
                'data' => $telemetry
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update telemetry',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified telemetry from storage.
     *
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $deleted = $this->telemetryRepository->delete($id);

            if (!$deleted) {
                return response()->json([
                    'success' => false,
                    'message' => 'Telemetry not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Telemetry deleted successfully'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete telemetry',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    /**
     * Get latest telemetries.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function latest(Request $request): JsonResponse
    {
        try {
            $limit = $request->get('limit', 10);
            $telemetries = $this->telemetryRepository->getLatest($limit);

            return response()->json([
                'success' => true,
                'message' => 'Latest telemetries retrieved successfully',
                'data' => $telemetries
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve latest telemetries',
                'error' => $th->getMessage()
            ], 500);
        }
    }
}
