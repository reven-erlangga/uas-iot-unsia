-- MySQL Database Setup for ESP32 Monitoring System
-- Run this script in your MySQL database

-- Create database
CREATE DATABASE IF NOT EXISTS r20001_esp32_monitoring;
USE r20001_esp32_monitoring;

-- Create telemetry table
CREATE TABLE IF NOT EXISTS telemetry (
    id INT AUTO_INCREMENT PRIMARY KEY,
    station_id VARCHAR(32) NOT NULL,
    device_id VARCHAR(50) NOT NULL,
    temperature DECIMAL(5,2),
    humidity DECIMAL(5,2),
    metadata JSON NULL,
    latitude DECIMAL(10,8),
    longitude DECIMAL(11,8),
    altitude DECIMAL(8,2),
    battery_level DECIMAL(5,2),
    signal_strength INT,
    timestamp DATETIME NOT NULL,
    created_at DATETIME NOT NULL
);

-- Create indexes for better performance
CREATE INDEX idx_station_id ON telemetry(station_id);
CREATE INDEX idx_device_id ON telemetry(device_id);
CREATE INDEX idx_timestamp ON telemetry(timestamp);
CREATE INDEX idx_device_timestamp ON telemetry(device_id, timestamp);

-- Insert sample data
INSERT INTO telemetry (station_id, device_id, temperature, humidity, metadata, timestamp, created_at) VALUES
('240401020001', 'ESP32_001', 25.5, 60.2, NULL, NOW() - INTERVAL 2 HOUR, NOW()),
('240401020001', 'ESP32_001', 26.1, 58.7, '{"foo":"bar"}', NOW() - INTERVAL 1 HOUR, NOW()),
('240401020001', 'ESP32_001', 24.8, 62.1, NULL, NOW() - INTERVAL 30 MINUTE, NOW()),
('240401020001', 'ESP32_001', 25.2, 61.5, NULL, NOW(), NOW());

-- Show the created data
SELECT COUNT(*) as total_records FROM telemetry; 