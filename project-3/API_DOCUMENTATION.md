# Sensor Data API Documentation

## Base URL
```
http://localhost:8002/api/sensors
```

## Endpoints

### 1. POST /api/sensors
Menyimpan data sensor baru

**Request Body:**
```json
{
    "apikey": "your-api-key-here",
    "deviceId": "device001",
    "deviceName": "Temperature Sensor 1",
    "temperature": 25.5,
    "humidity": 65.2
}
```

**Response (200 OK):**
```json
{
    "id": 1,
    "apikey": "your-api-key-here",
    "deviceId": "device001",
    "deviceName": "Temperature Sensor 1",
    "temperature": 25.5,
    "humidity": 65.2,
    "timestamp": "2025-01-15T10:30:00"
}
```

### 2. GET /api/sensors/{deviceId}
Mengambil data sensor berdasarkan device ID (20 data terbaru)

**Request:**
```
GET http://localhost:8002/api/sensors/device001
```

**Response (200 OK):**
```json
[
    {
        "id": 3,
        "apikey": "your-api-key-here",
        "deviceId": "device001",
        "deviceName": "Temperature Sensor 1",
        "temperature": 26.1,
        "humidity": 64.8,
        "timestamp": "2025-01-15T10:35:00"
    },
    {
        "id": 2,
        "apikey": "your-api-key-here",
        "deviceId": "device001",
        "deviceName": "Temperature Sensor 1",
        "temperature": 25.8,
        "humidity": 65.0,
        "timestamp": "2025-01-15T10:32:00"
    }
]
```

### 3. GET /api/sensors
Mengambil semua data sensor

**Request:**
```
GET http://localhost:8002/api/sensors
```

**Response (200 OK):**
```json
[
    {
        "id": 3,
        "apikey": "your-api-key-here",
        "deviceId": "device001",
        "deviceName": "Temperature Sensor 1",
        "temperature": 26.1,
        "humidity": 64.8,
        "timestamp": "2025-01-15T10:35:00"
    }
]
```

### 4. GET /api/sensors/{deviceId}/latest
Mengambil data sensor terbaru berdasarkan device ID

**Request:**
```
GET http://localhost:8002/api/sensors/device001/latest
```

**Response (200 OK):**
```json
{
    "id": 3,
    "apikey": "your-api-key-here",
    "deviceId": "device001",
    "deviceName": "Temperature Sensor 1",
    "temperature": 26.1,
    "humidity": 64.8,
    "timestamp": "2025-01-15T10:35:00"
}
```

### 5. DELETE /api/sensors/{deviceId}
Menghapus semua data sensor berdasarkan device ID

**Request:**
```
DELETE http://localhost:8002/api/sensors/device001
```

**Response (200 OK):**
```json
"Data for device device001 deleted successfully"
```

## Contoh Penggunaan di Postman

### 1. Menyimpan Data Sensor
- **Method:** POST
- **URL:** `http://localhost:8002/api/sensors`
- **Headers:** 
  - Content-Type: application/json
- **Body (raw JSON):**
```json
{
    "apikey": "sensor-api-key-123",
    "deviceId": "temp-sensor-01",
    "deviceName": "Temperature & Humidity Sensor",
    "temperature": 24.5,
    "humidity": 68.3
}
```

### 2. Mengambil Data Sensor
- **Method:** GET
- **URL:** `http://localhost:8002/api/sensors/temp-sensor-01`

### 3. Mengambil Data Terbaru
- **Method:** GET
- **URL:** `http://localhost:8002/api/sensors/temp-sensor-01/latest`

## Contoh Data untuk Testing

### Data Sensor 1
```json
{
    "apikey": "sensor-api-key-123",
    "deviceId": "temp-sensor-01",
    "deviceName": "Temperature & Humidity Sensor",
    "temperature": 24.5,
    "humidity": 68.3
}
```

### Data Sensor 2
```json
{
    "apikey": "sensor-api-key-456",
    "deviceId": "temp-sensor-02",
    "deviceName": "Outdoor Weather Station",
    "temperature": 28.2,
    "humidity": 45.7
}
```

### Data Sensor 3
```json
{
    "apikey": "sensor-api-key-789",
    "deviceId": "temp-sensor-03",
    "deviceName": "Indoor Climate Monitor",
    "temperature": 22.1,
    "humidity": 72.8
}
```

## Catatan Penting

1. **Database:** Data akan disimpan di file SQLite `sensor_data.db` di root project
2. **Timestamp:** Jika tidak disediakan, akan menggunakan waktu saat ini
3. **ID:** Akan di-generate otomatis oleh database
4. **API Key:** Bisa digunakan untuk autentikasi (opsional)
5. **Device ID:** Wajib diisi untuk identifikasi sensor

## Error Handling

- **400 Bad Request:** Jika format JSON tidak valid
- **404 Not Found:** Jika device ID tidak ditemukan
- **500 Internal Server Error:** Jika ada masalah dengan database 