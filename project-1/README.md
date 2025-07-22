# IoT Telemetry Monitoring Project

This project is a web-based sensor data (telemetry) monitoring system built using **two popular PHP frameworks**: [CodeIgniter 3](https://codeigniter.com/) and [Laravel](https://laravel.com/). The system is designed to receive, store, and display sensor data (such as temperature, humidity, pressure, etc.) sent from IoT devices (e.g., ESP32) in both real-time and historical views.

---

## Table of Contents
- [Overview](#overview)
- [Project Structure](#project-structure)
- [Main Features](#main-features)
- [System Architecture](#system-architecture)
- [CodeIgniter vs Laravel Comparison](#codeigniter-vs-laravel-comparison)
- [Getting Started](#getting-started)
- [API & ESP32 Integration](#api--esp32-integration)
- [License](#license)

---

## Overview

- **CodeIgniter**: Monitoring implementation with a simple MVC architecture, SQLite database, and API endpoints to receive data from IoT devices.
- **Laravel**: Monitoring implementation with more modern features, Eloquent ORM, request validation, and more structured API endpoints.

Both frameworks can be used independently for sensor data monitoring needs.

---

## Project Structure

```
project-1/
├── codeIgniter/   # CodeIgniter application source code
├── laravel/       # Laravel application source code
└── README.md      # Main documentation
```

---

## Main Features

### CodeIgniter
- Sensor data monitoring dashboard
- API to receive telemetry data from ESP32
- Data storage using SQLite
- Real-time & historical data visualization
- Data export (CSV/JSON)
- Device management (setup, status, reset)

### Laravel
- Sensor data monitoring dashboard
- RESTful API for telemetry data CRUD
- Data storage using relational databases (default: SQLite/MySQL)
- Data validation & encryption (device_id, station_id)
- Temperature & humidity trend visualization
- More modern code structure (Repository, Request Validation, Eloquent ORM)

---

## System Architecture

```
[ESP32/IoT Device]
      |
      |  (HTTP POST JSON)
      v
[API Endpoint - CodeIgniter/Laravel]
      |
      v
[Database]
      |
      v
[Web Dashboard]
```

- IoT devices send data to the API endpoint (choose either framework)
- Data is stored in the database
- The web dashboard displays data in real-time and historical views

---

## CodeIgniter vs Laravel Comparison

| Feature              | CodeIgniter 3                | Laravel                      |
|----------------------|------------------------------|------------------------------|
| Architecture         | Classic MVC, procedural      | Modern MVC, OOP, modular     |
| Database             | SQLite                       | SQLite/MySQL/PostgreSQL      |
| ORM                  | Query Builder                | Eloquent ORM                 |
| Validation           | Manual in controller/model   | Request Validation           |
| API                  | Simple REST                  | RESTful, resourceful         |
| Security             | Basic                        | Encryption, CSRF, Middleware |
| Extensions           | Easy, lightweight            | Very broad, large ecosystem  |
| Documentation        | Simple                       | Very comprehensive           |

---

## Getting Started

### CodeIgniter
1. Go to the `codeIgniter/` folder
2. Make sure PHP & SQLite are installed
3. Run the database initialization script:
   ```bash
   php application/database/init_database.php
   ```
4. Set your web server to the `codeIgniter/` folder
5. Access the application via browser

### Laravel
1. Go to the `laravel/` folder
2. Install dependencies:
   ```bash
   composer install
   ```
3. Copy `.env.example` to `.env` and configure your database settings
4. Run the database migrations:
   ```bash
   php artisan migrate
   ```
5. Start the local server:
   ```bash
   php artisan serve
   ```
6. Access the application via browser

---

## API & ESP32 Integration

### Example Telemetry Data Payload
```json
{
  "device_id": "ESP32_001",
  "temperature": 25.5,
  "humidity": 60.2,
  "timestamp": "2024-01-01 12:00:00",
  "metadata": { "signal": -45, "battery": 85.2 }
}
```

### API Endpoints
- **CodeIgniter**: `POST /api/telemetry/receive`
- **Laravel**: `POST /api/telemetry`

### Example ESP32 Data Sending Code (Arduino)
```cpp
#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";
const char* serverUrl = "http://your-server.com/api/telemetry";

void sendTelemetryData(float temperature, float humidity) {
    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        http.begin(serverUrl);
        http.addHeader("Content-Type", "application/json");
        
        DynamicJsonDocument doc(512);
        doc["device_id"] = "ESP32_001";
        doc["temperature"] = temperature;
        doc["humidity"] = humidity;
        doc["timestamp"] = "2024-01-01 12:00:00";
        
        String jsonString;
        serializeJson(doc, jsonString);
        http.POST(jsonString);
        http.end();
    }
}
```

---

## License

This project is open source and uses the MIT license.
