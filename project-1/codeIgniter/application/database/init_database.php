<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Database Initialization Script
 * Run this script to create the telemetry table in SQLite
 */

// Load CodeIgniter
require_once FCPATH . 'index.php';

// Create database connection
$db_path = APPPATH . 'database/telemetry.db';

// Create SQLite database if it doesn't exist
if (!file_exists($db_path)) {
    $db = new SQLite3($db_path);
    
    // Create telemetry table
    $sql = "
    CREATE TABLE IF NOT EXISTS telemetry (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        station_id VARCHAR(32) NOT NULL,
        device_id VARCHAR(50) NOT NULL,
        temperature DECIMAL(5,2),
        humidity DECIMAL(5,2),
        metadata TEXT,
        latitude DECIMAL(10,8),
        longitude DECIMAL(11,8),
        altitude DECIMAL(8,2),
        battery_level DECIMAL(5,2),
        signal_strength INTEGER,
        timestamp DATETIME NOT NULL,
        created_at DATETIME NOT NULL
    );
    
    CREATE INDEX IF NOT EXISTS idx_station_id ON telemetry(station_id);
    CREATE INDEX IF NOT EXISTS idx_device_id ON telemetry(device_id);
    CREATE INDEX IF NOT EXISTS idx_timestamp ON telemetry(timestamp);
    CREATE INDEX IF NOT EXISTS idx_device_timestamp ON telemetry(device_id, timestamp);
    ";
    
    $db->exec($sql);
    
    echo "Database created successfully at: " . $db_path . "\n";
    echo "Telemetry table created with indexes.\n";
    
    // Insert sample data for testing
    $sample_data = [
        [
            'station_id' => '240401020001',
            'device_id' => 'ESP32_001',
            'temperature' => 25.5,
            'humidity' => 60.2,
            'metadata' => null,
            'timestamp' => date('Y-m-d H:i:s', strtotime('-1 hour')),
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'station_id' => '240401020001',
            'device_id' => 'ESP32_001',
            'temperature' => 26.1,
            'humidity' => 58.7,
            'metadata' => '{"custom":"value"}',
            'timestamp' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'station_id' => '240401020001',
            'device_id' => 'ESP32_001',
            'temperature' => 24.8,
            'humidity' => 62.1,
            'metadata' => null,
            'timestamp' => date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        ]
    ];
    
    $stmt = $db->prepare("
        INSERT INTO telemetry (station_id, device_id, temperature, humidity, metadata, timestamp, created_at)
        VALUES (:station_id, :device_id, :temperature, :humidity, :metadata, :timestamp, :created_at)
    ");
    
    foreach ($sample_data as $data) {
        $stmt->bindValue(':station_id', $data['station_id'], SQLITE3_TEXT);
        $stmt->bindValue(':device_id', $data['device_id'], SQLITE3_TEXT);
        $stmt->bindValue(':temperature', $data['temperature'], SQLITE3_FLOAT);
        $stmt->bindValue(':humidity', $data['humidity'], SQLITE3_FLOAT);
        $stmt->bindValue(':metadata', $data['metadata'], SQLITE3_TEXT);
        $stmt->bindValue(':timestamp', $data['timestamp'], SQLITE3_TEXT);
        $stmt->bindValue(':created_at', $data['created_at'], SQLITE3_TEXT);
        $stmt->execute();
    }
    
    echo "Sample data inserted successfully.\n";
    
} else {
    echo "Database already exists at: " . $db_path . "\n";
}

echo "Database initialization completed.\n";
?> 