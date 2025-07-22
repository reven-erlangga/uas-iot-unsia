<?php
/**
 * Database Initialization Script for ESP32 Telemetry System
 * Run this script to create the SQLite database and tables
 */

// Define paths
$app_path = __DIR__ . '/application/';
$db_path = $app_path . 'database/telemetry.db';

// Create database directory if it doesn't exist
if (!is_dir($app_path . 'database')) {
    mkdir($app_path . 'database', 0755, true);
    echo "Created database directory.\n";
}

// Create SQLite database if it doesn't exist
if (!file_exists($db_path)) {
    try {
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
        
        echo "✓ Database created successfully at: " . $db_path . "\n";
        echo "✓ Telemetry table created with indexes.\n";
        
        // Insert sample data for testing
        $sample_data = [
            [
                'station_id' => '240401020001',
                'device_id' => 'ESP32_001',
                'temperature' => 25.5,
                'humidity' => 60.2,
                'metadata' => null,
                'timestamp' => date('Y-m-d H:i:s', strtotime('-2 hours')),
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'station_id' => '240401020001',
                'device_id' => 'ESP32_001',
                'temperature' => 26.1,
                'humidity' => 58.7,
                'metadata' => '{"foo":"bar"}',
                'timestamp' => date('Y-m-d H:i:s', strtotime('-1 hour')),
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'station_id' => '240401020001',
                'device_id' => 'ESP32_001',
                'temperature' => 24.8,
                'humidity' => 62.1,
                'metadata' => null,
                'timestamp' => date('Y-m-d H:i:s', strtotime('-30 minutes')),
                'created_at' => date('Y-m-d H:i:s')
            ],
            [
                'station_id' => '240401020001',
                'device_id' => 'ESP32_001',
                'temperature' => 25.2,
                'humidity' => 61.5,
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
        
        echo "✓ Sample data inserted successfully.\n";
        
        // Verify the data
        $result = $db->query("SELECT COUNT(*) as count FROM telemetry");
        $count = $result->fetchArray(SQLITE3_ASSOC)['count'];
        echo "✓ Database contains {$count} records.\n";
        
    } catch (Exception $e) {
        echo "✗ Error creating database: " . $e->getMessage() . "\n";
        exit(1);
    }
    
} else {
    echo "✓ Database already exists at: " . $db_path . "\n";
    
    // Check if table exists and has data
    try {
        $db = new SQLite3($db_path);
        $result = $db->query("SELECT COUNT(*) as count FROM telemetry");
        $count = $result->fetchArray(SQLITE3_ASSOC)['count'];
        echo "✓ Database contains {$count} records.\n";
    } catch (Exception $e) {
        echo "✗ Error checking database: " . $e->getMessage() . "\n";
    }
}

echo "\nDatabase initialization completed successfully!\n";
echo "You can now run your CodeIgniter application.\n";
?> 