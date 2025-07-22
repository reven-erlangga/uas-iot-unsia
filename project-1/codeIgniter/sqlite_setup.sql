-- SQLite Database Setup for ESP32 Monitoring System
-- Run this script to create the database and tables

-- Create telemetry table
CREATE TABLE IF NOT EXISTS telemetry (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    station_id TEXT NOT NULL,
    device_id TEXT NOT NULL,
    temperature REAL,
    humidity REAL,
    metadata TEXT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_station_id ON telemetry(station_id);
CREATE INDEX IF NOT EXISTS idx_device_id ON telemetry(device_id);
CREATE INDEX IF NOT EXISTS idx_timestamp ON telemetry(timestamp);
CREATE INDEX IF NOT EXISTS idx_device_timestamp ON telemetry(device_id, timestamp);

-- Insert sample data
INSERT INTO telemetry (station_id, device_id, temperature, humidity, metadata, timestamp) VALUES
('240401020001', 'ESP32_001', 25.4, 60.2, NULL, datetime('now', '-1 minute')),
('240401020001', 'ESP32_001', 25.6, 59.8, '{"foo":"bar"}', datetime('now', '-2 minute')),
('240401020001', 'ESP32_001', 25.2, 61.1, NULL, datetime('now', '-3 minute')),
('240401020001', 'ESP32_001', 25.8, 58.9, NULL, datetime('now', '-4 minute')),
('240401020001', 'ESP32_001', 25.1, 62.3, NULL, datetime('now', '-5 minute')),
('240401020001', 'ESP32_002', 24.9, 63.5, NULL, datetime('now', '-1 minute')),
('240401020001', 'ESP32_002', 25.3, 61.8, NULL, datetime('now', '-2 minute')),
('240401020001', 'ESP32_002', 24.7, 64.2, NULL, datetime('now', '-3 minute')),
('240401020001', 'ESP32_002', 25.5, 60.5, NULL, datetime('now', '-4 minute')),
('240401020001', 'ESP32_002', 24.8, 63.9, NULL, datetime('now', '-5 minute'));

-- Show created tables
SELECT name FROM sqlite_master WHERE type='table';

-- Show sample data
SELECT * FROM telemetry LIMIT 5; 