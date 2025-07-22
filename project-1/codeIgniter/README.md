# ESP32 Telemetry Monitoring System

A CodeIgniter 3 based web application for monitoring ESP32 sensor data with SQLite database.

## Features

- **Home Controller**: Main dashboard and navigation
- **ESP Setup Controller**: Device configuration and setup management
- **Sensor Monitoring Controller**: Real-time and historical sensor data monitoring
- **Telemetry API Controller**: RESTful API for receiving sensor data from ESP32 devices
- **Telemetry Model**: Database operations for sensor data management
- **SQLite Database**: Lightweight database for storing telemetry data

## System Requirements

- PHP 7.0 or higher
- SQLite3 extension for PHP
- CodeIgniter 3.1.13
- Web server (Apache/Nginx)

## Installation

1. **Clone or download** the project to your web server directory
2. **Initialize the database** by running:
   ```bash
   php init_db.php
   ```
3. **Configure your web server** to point to the project directory
4. **Set proper permissions** for the database directory:
   ```bash
   chmod 755 application/database/
   chmod 644 application/database/telemetry.db
   ```

## Database Schema

The system uses SQLite with the following table structure:

```sql
CREATE TABLE telemetry (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    device_id VARCHAR(50) NOT NULL,
    temperature DECIMAL(5,2),
    humidity DECIMAL(5,2),
    pressure DECIMAL(8,2),
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    altitude DECIMAL(8,2),
    battery_level DECIMAL(5,2),
    signal_strength INTEGER,
    timestamp DATETIME NOT NULL,
    created_at DATETIME NOT NULL
);
```

## Controllers

### HomeController
- **URL**: `/home`
- **Methods**:
  - `index()` - Main dashboard
  - `dashboard()` - Detailed dashboard view
  - `about()` - About page

### EspSetupController
- **URL**: `/espsetup`
- **Methods**:
  - `index()` - Setup overview
  - `config()` - Device configuration form
  - `save_config()` - Save device configuration
  - `status()` - Device status monitoring
  - `reset()` - Reset device configuration

### SensorMonitoringController
- **URL**: `/sensormonitoring`
- **Methods**:
  - `index()` - Sensor monitoring dashboard
  - `realtime()` - Real-time monitoring
  - `history()` - Historical data view
  - `details()` - Sensor details
  - `export()` - Data export (CSV/JSON)
  - `get_data()` - AJAX data endpoint

### TelemetryController (API)
- **URL**: `/telemetry`
- **Methods**:
  - `receive()` - POST endpoint for receiving sensor data
  - `latest()` - GET latest telemetry data
  - `range()` - GET data by date range
  - `stats()` - GET statistics
  - `devices()` - GET device list
  - `delete()` - DELETE telemetry data
  - `health()` - Health check endpoint

## API Endpoints

### Receive Telemetry Data
```
POST /telemetry/receive
Content-Type: application/json

{
    "device_id": "ESP32_001",
    "temperature": 25.5,
    "humidity": 60.2,
    "pressure": 1013.25,
    "timestamp": "2024-01-01 12:00:00",
    "latitude": -6.2088,
    "longitude": 106.8456,
    "altitude": 10.5,
    "battery_level": 85.2,
    "signal_strength": -45
}
```

### Get Latest Data
```
GET /telemetry/latest?device_id=ESP32_001&limit=10
```

### Get Data by Date Range
```
GET /telemetry/range?start_date=2024-01-01&end_date=2024-01-31&device_id=ESP32_001
```

### Get Statistics
```
GET /telemetry/stats?period=24h&device_id=ESP32_001
```

### Get Device List
```
GET /telemetry/devices
```

## Model Methods

### Telemetry Model
- `insert($data)` - Insert new telemetry data
- `get_latest_data($limit, $device_id)` - Get latest sensor readings
- `get_data_by_date_range($start_date, $end_date, $device_id)` - Get historical data
- `get_sensor_statistics($period, $device_id)` - Get statistical data
- `get_device_list()` - Get list of all devices
- `get_chart_data($start_date, $end_date, $device_id)` - Get data for charts
- `get_alerts($temp_min, $temp_max, $humidity_min, $humidity_max, $device_id)` - Get alert data

## ESP32 Integration

To send data from your ESP32 device, use the following Arduino code structure:

```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";
const char* serverUrl = "http://your-server.com/telemetry/receive";

void sendTelemetryData(float temperature, float humidity, float pressure) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        http.begin(serverUrl);
        http.addHeader("Content-Type", "application/json");
        
        DynamicJsonDocument doc(1024);
        doc["device_id"] = "ESP32_001";
        doc["temperature"] = temperature;
        doc["humidity"] = humidity;
        doc["pressure"] = pressure;
        doc["timestamp"] = getCurrentTimestamp();
        
        String jsonString;
        serializeJson(doc, jsonString);
        
        int httpResponseCode = http.POST(jsonString);
        
        if (httpResponseCode > 0) {
            String response = http.getString();
            Serial.println("HTTP Response code: " + String(httpResponseCode));
            Serial.println("Response: " + response);
        } else {
            Serial.println("Error on sending POST: " + String(httpResponseCode));
        }
        
        http.end();
    }
}
```

## Usage

1. **Access the web interface** at your server URL
2. **Configure ESP32 devices** using the ESP Setup section
3. **Monitor sensor data** in real-time or view historical data
4. **Export data** in CSV or JSON format
5. **Use the API** to integrate with other systems

## File Structure

```
application/
├── controllers/
│   ├── HomeController.php
│   ├── EspSetupController.php
│   ├── SensorMonitoringController.php
│   └── TelemetryController.php
├── models/
│   └── Telemetry.php
├── database/
│   └── telemetry.db
└── views/
    └── (view files to be created)
```

## Security Notes

- The current implementation uses session-based storage for device configuration
- For production use, consider implementing proper authentication
- Validate and sanitize all input data
- Use HTTPS for API communications
- Implement rate limiting for API endpoints

## Troubleshooting

1. **Database errors**: Ensure SQLite3 extension is installed
2. **Permission errors**: Check file permissions for database directory
3. **API errors**: Verify JSON format and required fields
4. **Connection issues**: Check network connectivity and server configuration

## License

This project is open source and available under the MIT License. 