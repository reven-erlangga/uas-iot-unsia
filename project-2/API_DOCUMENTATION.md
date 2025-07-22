# IoT Sensor API Documentation

## Base URL
```
http://localhost:8000
```

## API Endpoints

### 1. Store Sensor Data (POST)
**URL:** `/api/store-sensor-data/` (⚠️ **PENTING**: Harus ada trailing slash `/` di akhir)

**Description:** Menyimpan data sensor dari perangkat IoT

**Headers:**
```
Content-Type: application/json
```

**Request Body (JSON):**
```json
{
    "apikey": "your_api_key_here",
    "deviceId": "device-001",
    "deviceName": "Sensor Device 1",
    "temperature": 25.5,
    "humidity": 65.2
}
```

**Response Success (201):**
```json
{
    "success": true,
    "message": "Data saved successfully",
    "data": {
        "id": 1,
        "deviceId": "device-001",
        "deviceName": "Sensor Device 1",
        "temperature": 25.5,
        "humidity": 65.2,
        "timestamp": "2024-01-15T10:30:00.000000Z"
    }
}
```

**Response Error (400):**
```json
{
    "success": false,
    "error": "Missing required field: temperature"
}
```

### 2. Get Sensor Data (GET)
**URL:** `/api/get-sensor-data/` (⚠️ **PENTING**: Harus ada trailing slash `/` di akhir)

**Description:** Mengambil data sensor dari database

**Query Parameters:**
- `deviceId` (optional): Filter berdasarkan device ID
- `limit` (optional): Jumlah data yang diambil (default: 100)

**Examples:**
```
GET /api/get-sensor-data/
GET /api/get-sensor-data/?deviceId=device-001
GET /api/get-sensor-data/?limit=50
GET /api/get-sensor-data/?deviceId=device-001&limit=10
```

**Response Success (200):**
```json
{
    "success": true,
    "count": 2,
    "data": [
        {
            "id": 2,
            "apikey": "your_api_key_here",
            "deviceId": "device-001",
            "deviceName": "Sensor Device 1",
            "temperature": 26.1,
            "humidity": 64.8,
            "timestamp": "2024-01-15T10:35:00.000000Z"
        },
        {
            "id": 1,
            "apikey": "your_api_key_here",
            "deviceId": "device-001",
            "deviceName": "Sensor Device 1",
            "temperature": 25.5,
            "humidity": 65.2,
            "timestamp": "2024-01-15T10:30:00.000000Z"
        }
    ]
}
```

## Postman Setup

### 1. Store Sensor Data Collection
1. Create new collection: "IoT Sensor API"
2. Add new request: "Store Sensor Data"
3. Set method to **POST**
4. Set URL to: `http://localhost:8000/api/store-sensor-data/` (⚠️ **PENTING**: Pastikan ada `/` di akhir)
5. Set Headers:
   - Key: `Content-Type`
   - Value: `application/json`
6. Set Body to **raw** and **JSON**:
```json
{
    "apikey": "test_api_key_123",
    "deviceId": "device-001",
    "deviceName": "Sensor Device 1",
    "temperature": 25.5,
    "humidity": 65.2
}
```

### 2. Get Sensor Data Collection
1. Add new request: "Get Sensor Data"
2. Set method to **GET**
3. Set URL to: `http://localhost:8000/api/get-sensor-data/` (⚠️ **PENTING**: Pastikan ada `/` di akhir)
4. Add query parameters (optional):
   - Key: `deviceId`, Value: `device-001`
   - Key: `limit`, Value: `10`

## Database Schema

### SensorData Table
| Field | Type | Description |
|-------|------|-------------|
| id | AutoField | Primary key |
| apikey | CharField(100) | API key untuk autentikasi |
| deviceId | CharField(50) | ID perangkat sensor |
| deviceName | CharField(100) | Nama perangkat sensor |
| temperature | FloatField | Nilai suhu dalam Celsius |
| humidity | FloatField | Nilai kelembaban dalam persen |
| timestamp | DateTimeField | Waktu data diterima (auto) |

## Admin Panel
- URL: `http://localhost:8000/admin/`
- Username: `admin`
- Password: `admin123`

## Running the Server
```bash
cd /path/to/iot/project
python3 manage.py runserver
```

## Testing with cURL

### Store Data:
```bash
curl -X POST http://localhost:8000/api/store-sensor-data/ \
  -H "Content-Type: application/json" \
  -d '{
    "apikey": "test_api_key_123",
    "deviceId": "device-001",
    "deviceName": "Sensor Device 1",
    "temperature": 25.5,
    "humidity": 65.2
  }'
```

### Get Data:
```bash
curl http://localhost:8000/api/get-sensor-data/
``` 