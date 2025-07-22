# Repository Pattern Implementation

This document explains the repository pattern implementation for the Telemetry module.

## Structure

### 1. Repository Interface
- **File**: `app/Repositories/Contracts/TelemetryRepositoryInterface.php`
- **Purpose**: Defines the contract for telemetry data operations

### 2. Repository Implementation
- **File**: `app/Repositories/TelemetryRepository.php`
- **Purpose**: Implements the repository interface with Eloquent operations

### 3. Service Provider
- **File**: `app/Providers/RepositoryServiceProvider.php`
- **Purpose**: Binds the repository interface to its implementation

### 4. Controller
- **File**: `app/Http/Controllers/Api/TelemetryController.php`
- **Purpose**: Handles HTTP requests using the repository pattern

## Available Methods

### Repository Methods
- `getAll(?int $perPage = null)` - Get all telemetries with optional pagination
- `findById(string $id)` - Find telemetry by ID
- `create(array $data)` - Create new telemetry record
- `update(string $id, array $data)` - Update telemetry record
- `delete(string $id)` - Delete telemetry record
- `getByStationId(string $stationId, ?int $perPage = null)` - Get telemetries by station ID
- `getByDeviceId(string $deviceId, ?int $perPage = null)` - Get telemetries by device ID
- `getLatest(int $limit = 10)` - Get latest telemetries

### API Endpoints
- `GET /api/telemetry` - List all telemetries (with optional filters)
- `POST /api/telemetry` - Create new telemetry
- `GET /api/telemetry/latest` - Get latest telemetries
- `GET /api/telemetry/{id}` - Get specific telemetry
- `PUT /api/telemetry/{id}` - Update telemetry
- `DELETE /api/telemetry/{id}` - Delete telemetry

## Query Parameters

### GET /api/telemetry
- `per_page` - Number of items per page (default: 15)
- `station_id` - Filter by station ID
- `device_id` - Filter by device ID

### GET /api/telemetry/latest
- `limit` - Number of latest records to retrieve (default: 10)

## Request Validation

### Store Request
- `station_id` - required|string
- `temperature` - required|numeric
- `humidity` - required|numeric
- `device_id` - required|string
- `metadata` - nullable|json

### Update Request
- `station_id` - sometimes|required|string
- `temperature` - sometimes|required|numeric
- `humidity` - sometimes|required|numeric
- `device_id` - sometimes|required|string
- `metadata` - nullable|json

## Benefits of Repository Pattern

1. **Separation of Concerns**: Business logic is separated from data access logic
2. **Testability**: Easy to mock repository for unit testing
3. **Flexibility**: Can easily switch between different data sources
4. **Maintainability**: Changes to data access logic don't affect business logic
5. **Code Reusability**: Repository methods can be reused across different controllers

## Usage Example

```php
// In a controller or service
public function __construct(TelemetryRepositoryInterface $telemetryRepository)
{
    $this->telemetryRepository = $telemetryRepository;
}

public function someMethod()
{
    // Get all telemetries
    $telemetries = $this->telemetryRepository->getAll();
    
    // Get paginated telemetries
    $telemetries = $this->telemetryRepository->getAll(10);
    
    // Get by station ID
    $telemetries = $this->telemetryRepository->getByStationId('station-123');
    
    // Create new telemetry
    $telemetry = $this->telemetryRepository->create([
        'station_id' => 'station-123',
        'temperature' => 25.5,
        'humidity' => 60.0,
        'device_id' => 'device-456',
        'metadata' => ['sensor_type' => 'DHT22']
    ]);
}
```