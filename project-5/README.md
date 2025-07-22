# NestJS MQTT Application (with Mosquitto Broker)

A modern NestJS application focused on MQTT messaging, integrated with a Mosquitto broker using Docker.

---

## 🚀 Features

- NestJS application written in TypeScript
- MQTT broker (Mosquitto) managed via Docker
- REST API endpoints for publishing and subscribing to MQTT messages
- Health check endpoint
- Docker Compose orchestration for easy setup
- Automatic MQTT client reconnection

---

## 📁 Project Structure

```
project-5/
├── src/
│   ├── mqtt/
│   │   ├── mqtt.controller.ts    # REST API endpoints
│   │   ├── mqtt.service.ts       # MQTT client logic
│   │   └── mqtt.module.ts        # MQTT module
│   ├── app.controller.ts         # Main app controller
│   ├── app.service.ts
│   ├── app.module.ts
│   └── main.ts
├── mosquitto/
│   └── config/
│       └── mosquitto.conf        # Mosquitto configuration
├── docker-compose.yml            # Docker orchestration
├── Dockerfile                    # NestJS app container
├── test-mqtt-pub.js              # MQTT publish test script
├── test-mqtt-sub.js              # MQTT subscribe test script
└── README.md
```

---

## 🧰 Prerequisites

- [Docker](https://www.docker.com/get-started) & [Docker Compose](https://docs.docker.com/compose/)
- [Node.js](https://nodejs.org/) (for local development)

---

## 🛠️ Getting Started (with Docker)

1. **Clone the repository**
2. **Build and start the application with Docker Compose:**

```bash
docker-compose up --build
```

3. **Ports Used:**
   - `1883`: MQTT Broker (Mosquitto)
   - `3001`: NestJS Application
   - `9001`: MQTT WebSocket

---

## 📡 Usage

### Health Check
```bash
curl http://localhost:3001/health
```

### Publish MQTT via REST API
```bash
curl -X POST http://localhost:3001/mqtt/publish \
  -H "Content-Type: application/json" \
  -d '{"topic": "test/topic", "message": "Hello from REST API!"}'
```

### Subscribe MQTT via REST API
```bash
curl -X POST "http://localhost:3001/mqtt/subscribe?topic=test/topic"
```

### Check MQTT Status
```bash
curl http://localhost:3001/mqtt/status
```

### Using Node.js Scripts

- **Publish:**
  ```bash
  node test-mqtt-pub.js
  ```
- **Subscribe:**
  ```bash
  node test-mqtt-sub.js
  ```

### Using Docker Mosquitto Client

- **Publish:**
  ```bash
  docker run -it --rm \
    --network project-5_mqtt-network \
    eclipse-mosquitto \
    mosquitto_pub -h mosquitto-broker -p 1883 -t "test/topic" -m "Hello from Docker!"
  ```
- **Subscribe:**
  ```bash
  docker run -it --rm \
    --network project-5_mqtt-network \
    eclipse-mosquitto \
    mosquitto_sub -h mosquitto-broker -p 1883 -t "test/topic"
  ```

### Using Local MQTT Client Tools

- **Publish:**
  ```bash
  mosquitto_pub -h localhost -p 1883 -t "test/topic" -m "Hello from command line!"
  ```
- **Subscribe:**
  ```bash
  mosquitto_sub -h localhost -p 1883 -t "test/topic"
  ```

---

## 📝 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET    | `/`      | Hello message |
| GET    | `/health`| Health check with MQTT status |
| POST   | `/mqtt/publish` | Publish MQTT message |
| POST   | `/mqtt/subscribe?topic=<topic>` | Subscribe to MQTT topic |
| GET    | `/mqtt/status` | MQTT connection status |

---

## 🧑‍💻 Development

1. **Install dependencies:**
   ```bash
   npm install
   ```
2. **Run in development mode:**
   ```bash
   npm run start:dev
   ```
3. **Build application:**
   ```bash
   npm run build
   ```
4. **Run tests:**
   ```bash
   npm run test
   ```

---

## 📊 Monitoring & Logs

- **View all logs:**
  ```bash
  docker-compose logs
  ```
- **View specific service logs:**
  ```bash
  docker-compose logs nestjs-app
  docker-compose logs mosquitto
  ```
- **Check container status:**
  ```bash
  docker-compose ps
  ```

---

## 🛑 Stopping the Application

- **Stop and remove containers:**
  ```bash
  docker-compose down
  ```
- **Stop and remove containers + volumes:**
  ```bash
  docker-compose down -v
  ```

---

## 🛠️ Troubleshooting

- **Port Already in Use:**
  - Change the port mapping in `docker-compose.yml` if needed.
- **MQTT Connection Issues:**
  - Ensure Mosquitto container is running: `docker-compose ps`
  - Check logs: `docker-compose logs mosquitto`
  - Test connection: `curl http://localhost:3001/health`
- **Application Not Starting:**
  - Check logs: `docker-compose logs nestjs-app`
  - Rebuild: `docker-compose up --build`
  - Restart: `docker-compose restart nestjs-app`

---

## 📚 References

- [NestJS Documentation](https://docs.nestjs.com/)
- [MQTT.js Documentation](https://github.com/mqttjs/MQTT.js)
- [Mosquitto Documentation](https://mosquitto.org/documentation/)
- [Docker Compose Documentation](https://docs.docker.com/compose/)
