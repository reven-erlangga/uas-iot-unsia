# uas-iot-unsia

UAS IoT Unsia Project

This repository contains several IoT-related projects developed for Universitas Siber Asia (UNSIA). Each subproject demonstrates different technologies and approaches for IoT data collection, storage, and visualization.

## Project Structure

- **project-1**
  - `codeIgniter/`: ESP32 Telemetry Monitoring System using CodeIgniter 3 and SQLite. Features real-time and historical sensor data monitoring, device setup, and a RESTful API for ESP32 devices.
  - `laravel/`: Laravel-based web application framework, set up for modern PHP web development and API building.

- **project-2**
  - `iot/`: IoT Sensor API built with Django (Python). Provides RESTful endpoints for storing and retrieving sensor data, with SQLite as the backend database.

- **project-3**
  - Spring Boot & Kotlin backend for sensor data management. Supports REST API, SQLite storage, and a web interface for data visualization.

- **project-5**
  - NestJS (Node.js/TypeScript) application for MQTT messaging, integrated with a Mosquitto broker via Docker. Includes REST endpoints for MQTT publish/subscribe and health checks.

## Getting Started

Each subproject contains its own README with detailed setup and usage instructions. Please refer to the respective directories for more information.

### Prerequisites

- Docker & Docker Compose (for containerized services)
- Node.js (for NestJS project)
- PHP & Composer (for CodeIgniter and Laravel projects)
- Python 3 (for Django project)
- Java 21+ and Gradle (for Spring Boot project)

### Quick Start

1. Clone this repository:
   ```bash
   git clone <repo-url>
   cd uas-iot-unsia
   ```
2. Navigate to the desired project directory and follow its README instructions.

## License

This project is for educational purposes at Universitas Siber Asia (UNSIA).
