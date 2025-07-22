-- Complete Database Setup for ESP32 Monitoring System
-- Run this script as root or database administrator

-- Create database
CREATE DATABASE IF NOT EXISTS r20001_esp32_monitoring;
USE r20001_esp32_monitoring;

-- Create telemetry table
CREATE TABLE IF NOT EXISTS telemetry (
    id INT AUTO_INCREMENT PRIMARY KEY,
    station_id VARCHAR(32) NOT NULL,
    device_id VARCHAR(50) NOT NULL,
    temperature DECIMAL(5,2) NULL,
    humidity DECIMAL(5,2) NULL,
    metadata JSON NULL,
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_station_id (station_id),
    INDEX idx_device_id (device_id),
    INDEX idx_timestamp (timestamp),
    INDEX idx_device_timestamp (device_id, timestamp)
);

-- Create sample data
INSERT INTO telemetry (station_id, device_id, temperature, humidity, metadata, timestamp) VALUES
('240401020001', 'ESP32_001', 25.4, 60.2, NULL, NOW() - INTERVAL 1 MINUTE),
('240401020001', 'ESP32_001', 25.6, 59.8, '{"foo":"bar"}', NOW() - INTERVAL 2 MINUTE),
('240401020001', 'ESP32_001', 25.2, 61.1, NULL, NOW() - INTERVAL 3 MINUTE),
('240401020001', 'ESP32_001', 25.8, 58.9, NULL, NOW() - INTERVAL 4 MINUTE),
('240401020001', 'ESP32_001', 25.1, 62.3, NULL, NOW() - INTERVAL 5 MINUTE),
('240401020001', 'ESP32_002', 24.9, 63.5, NULL, NOW() - INTERVAL 1 MINUTE),
('240401020001', 'ESP32_002', 25.3, 61.8, NULL, NOW() - INTERVAL 2 MINUTE),
('240401020001', 'ESP32_002', 24.7, 64.2, NULL, NOW() - INTERVAL 3 MINUTE),
('240401020001', 'ESP32_002', 25.5, 60.5, NULL, NOW() - INTERVAL 4 MINUTE),
('240401020001', 'ESP32_002', 24.8, 63.9, NULL, NOW() - INTERVAL 5 MINUTE);

-- Create database user (if not exists)
-- Note: This requires SUPER privilege or CREATE USER privilege
CREATE USER IF NOT EXISTS 'r20001_esp32'@'%' IDENTIFIED BY 'esp32_monitoring_2024';

-- Grant privileges to the user
GRANT SELECT, INSERT, UPDATE, DELETE ON r20001_esp32_monitoring.* TO 'r20001_esp32'@'%';

-- Flush privileges to apply changes
FLUSH PRIVILEGES;

-- Show created database and user
SELECT 'Database created successfully!' as message;
SHOW DATABASES LIKE 'r20001_esp32_monitoring';
SELECT User, Host FROM mysql.user WHERE User = 'r20001_esp32'; 