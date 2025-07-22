# Sensor Data API - UNSIA IoT Project

Backend application for storing and managing sensor data using Spring Boot, Kotlin, and SQLite.

## Features

- ✅ Store sensor data (temperature, humidity) to SQLite
- ✅ Retrieve sensor data by device ID
- ✅ Retrieve the latest sensor data
- ✅ Retrieve all sensor data
- ✅ Delete sensor data by device ID
- ✅ REST API with JSON response
- ✅ Web interface for data visualization

## Technologies Used

- **Backend:** Spring Boot 3.5.3
- **Language:** Kotlin 1.9.25
- **Database:** SQLite
- **Build Tool:** Gradle
- **Frontend:** Thymeleaf + Tailwind CSS + Chart.js

## Project Structure

```
project-3/
├── src/main/kotlin/com/unsia/project3/
│   ├── controllers/
│   │   ├── DeviceController.kt
│   │   ├── HomeController.kt
│   │   ├── SensorDataController.kt
│   │   └── SimpleViewController.kt
│   ├── model/
│   │   └── SensorData.kt
│   ├── repository/
│   │   └── SensorDataRepository.kt
│   ├── service/
│   │   ├── HomeService.kt
│   │   └── SensorDataService.kt
│   └── Project3Application.kt
├── src/main/resources/
│   ├── application.properties
│   └── templates/
│       ├── home.html
│       ├── sensorchart.html
│       └── sensordata.html
├── build.gradle.kts
├── API_DOCUMENTATION.md
├── Sensor_Data_API.postman_collection.json
└── README.md
```

## How to Run

### 1. Prerequisites

- Java 21 or newer
- Gradle (or use the provided gradlew)

### 2. Build and Run

```bash
# Clone or download the project
cd project-3

# Build the project
./gradlew build

# Run the application
./gradlew bootRun
```

Or if using gradlew.bat on Windows:
```bash
gradlew.bat build
gradlew.bat bootRun
```

### 3. Access the Application

- **API Base URL:** http://localhost:8002/api/sensors
- **Web Interface:** http://localhost:8002

## Database

- **Type:** SQLite
- **File:** `sensor_data.db` (will be created automatically in the project root)
- **Auto Migration:** Yes (tables will be created automatically)

## API Endpoints

### 1. POST /api/sensors
Store new sensor data

```bash
curl -X POST http://localhost:8002/api/sensors \
  -H "Content-Type: application/json" \
  -d '{
    "apikey": "sensor-api-key-123",
    "deviceId": "temp-sensor-01",
    "deviceName": "Temperature & Humidity Sensor",
    "temperature": 24.5,
    "humidity": 68.3
  }'
```

### 2. GET /api/sensors/{deviceId}
Retrieve sensor data by device ID

```bash
curl http://localhost:8002/api/sensors/temp-sensor-01
```

### 3. GET /api/sensors/{deviceId}/latest
Retrieve the latest sensor data

```bash
curl http://localhost:8002/api/sensors/temp-sensor-01/latest
```

### 4. GET /api/sensors
Retrieve all sensor data

```bash
curl http://localhost:8002/api/sensors
```

### 5. DELETE /api/sensors/{deviceId}
Delete sensor data by device ID

```bash
curl -X DELETE http://localhost:8002/api/sensors/temp-sensor-01
```

## Testing with Postman

1. Import the `Sensor_Data_API.postman_collection.json` file into Postman
2. The collection already contains all required endpoints
3. Run each request one by one for testing

## Example Data for Testing

### Sensor Data 1
```json
{
    "apikey": "sensor-api-key-123",
    "deviceId": "temp-sensor-01",
    "deviceName": "Temperature & Humidity Sensor",
    "temperature": 24.5,
    "humidity": 68.3
}
```

### Sensor Data 2
```json
{
    "apikey": "sensor-api-key-456",
    "deviceId": "temp-sensor-02",
    "deviceName": "Outdoor Weather Station",
    "temperature": 28.2,
    "humidity": 45.7
}
```

### Sensor Data 3
```json
{
    "apikey": "sensor-api-key-789",
    "deviceId": "temp-sensor-03",
    "deviceName": "Indoor Climate Monitor",
    "temperature": 22.1,
    "humidity": 72.8
}
```

## Web Interface

The application also provides a web interface for:

1. **Home Page:** Main dashboard
2. **Sensor Chart:** Data visualization in chart form
3. **Sensor Data:** Sensor data table

Access via: http://localhost:8002

## Troubleshooting

### 1. Port 8002 is already in use
Change the port in `application.properties`:
```properties
server.port=8003
```

### 2. Database error
- Delete the `sensor_data.db` file if it exists
- Restart the application (the database will be recreated)

### 3. Build error
```bash
./gradlew clean build
```

### 4. Permission denied on gradlew
```bash
chmod +x gradlew
```

## Logs

The application will display SQL query logs for debugging:
```
Hibernate: INSERT INTO sensor_data (apikey, device_id, device_name, humidity, temperature, timestamp) VALUES (?, ?, ?, ?, ?, ?)
```

## Development

### Adding a New Endpoint

1. Add a method in `SensorDataController.kt`
2. Add a method in `SensorDataService.kt`
3. Add a method in `SensorDataRepository.kt` (if needed)

### Adding a New Field

1. Update the `SensorData.kt` model
2. Restart the application (the database will be updated automatically)

## License

UNSIA IoT Project - All rights reserved. 