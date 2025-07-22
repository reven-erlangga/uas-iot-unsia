<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Telemetry extends CI_Model {

    private $data_file;
    private $data = array();

    public function __construct() {
        parent::__construct();
        $this->data_file = APPPATH . 'database/telemetry_data.json';
        $this->load_data();
    }

    /**
     * Load data from JSON file
     */
    private function load_data() {
        if (file_exists($this->data_file)) {
            $json_data = file_get_contents($this->data_file);
            $this->data = json_decode($json_data, true) ?: array();
        } else {
            // Initialize with sample data if file doesn't exist
            $this->data = $this->generate_sample_data();
            $this->save_data();
        }
    }

    /**
     * Save data to JSON file
     */
    private function save_data() {
        $json_data = json_encode($this->data, JSON_PRETTY_PRINT);
        file_put_contents($this->data_file, $json_data);
    }

    /**
     * Generate sample data for testing
     */
    private function generate_sample_data() {
        $data = array();
        $devices = array('ESP32_001', 'ESP32_002');
        $current_time = time();
        $id = 1;

        // Generate data for the last 24 hours
        for ($i = 23; $i >= 0; $i--) {
            foreach ($devices as $device_id) {
                $timestamp = date('Y-m-d H:i:s', $current_time - ($i * 3600));
                
                // Generate realistic sensor data
                $hour = date('H', $current_time - ($i * 3600));
                $base_temp = 25 + (sin(($hour - 6) * 0.26) * 3);
                $base_humidity = 70 - (sin(($hour - 6) * 0.26) * 15);
                
                $data[] = array(
                    'id' => $id++,
                    'device_id' => $device_id,
                    'temperature' => round($base_temp + (rand(-10, 10) / 10), 1),
                    'humidity' => round($base_humidity + (rand(-5, 5)), 1),
                    'timestamp' => $timestamp,
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
        }

        // Add some recent data points
        for ($i = 1; $i <= 10; $i++) {
            foreach ($devices as $device_id) {
                $timestamp = date('Y-m-d H:i:s', time() - ($i * 300)); // Every 5 minutes
                
                $data[] = array(
                    'id' => $id++,
                    'device_id' => $device_id,
                    'temperature' => round(25 + (rand(-20, 20) / 10), 1),
                    'humidity' => round(60 + (rand(-10, 10)), 1),
                    'timestamp' => $timestamp,
                    'created_at' => date('Y-m-d H:i:s')
                );
            }
        }

        return $data;
    }

    /**
     * Insert new telemetry data
     * @param array $data
     * @return int|bool Insert ID on success, FALSE on failure
     */
    public function insert($data) {
        $max_id = 0;
        foreach ($this->data as $record) {
            if ($record['id'] > $max_id) {
                $max_id = $record['id'];
            }
        }
        
        $data['id'] = $max_id + 1;
        $data['created_at'] = date('Y-m-d H:i:s');
        
        $this->data[] = $data;
        $this->save_data();
        
        return $data['id'];
    }

    /**
     * Get latest telemetry data
     * @param int $limit Number of records to return
     * @param string $device_id Optional device ID filter
     * @return array
     */
    public function get_latest_data($limit = 10, $device_id = null) {
        $filtered_data = $this->data;
        
        if ($device_id) {
            $filtered_data = array_filter($filtered_data, function($record) use ($device_id) {
                return $record['device_id'] === $device_id;
            });
        }
        
        // Sort by timestamp descending
        usort($filtered_data, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });
        
        return array_values(array_slice($filtered_data, 0, $limit));
    }

    /**
     * Get telemetry data by date range
     * @param string $start_date Start date (Y-m-d format)
     * @param string $end_date End date (Y-m-d format)
     * @param string $device_id Optional device ID filter
     * @return array
     */
    public function get_data_by_date_range($start_date, $end_date, $device_id = null) {
        $filtered_data = array_filter($this->data, function($record) use ($start_date, $end_date, $device_id) {
            $record_date = date('Y-m-d', strtotime($record['timestamp']));
            $date_match = $record_date >= $start_date && $record_date <= $end_date;
            
            if ($device_id) {
                return $date_match && $record['device_id'] === $device_id;
            }
            
            return $date_match;
        });
        
        // Sort by timestamp ascending
        usort($filtered_data, function($a, $b) {
            return strtotime($a['timestamp']) - strtotime($b['timestamp']);
        });
        
        return array_values($filtered_data);
    }

    /**
     * Get sensor statistics
     * @param string $period Period for statistics (24h, 7d, 30d)
     * @param string $device_id Optional device ID filter
     * @return object
     */
    public function get_sensor_statistics($period = '24h', $device_id = null) {
        $cutoff_time = time();
        
        switch ($period) {
            case '24h':
                $cutoff_time -= 24 * 3600;
                break;
            case '7d':
                $cutoff_time -= 7 * 24 * 3600;
                break;
            case '30d':
                $cutoff_time -= 30 * 24 * 3600;
                break;
        }
        
        $filtered_data = array_filter($this->data, function($record) use ($cutoff_time, $device_id) {
            $time_match = strtotime($record['timestamp']) >= $cutoff_time;
            
            if ($device_id) {
                return $time_match && $record['device_id'] === $device_id;
            }
            
            return $time_match;
        });
        
        if (empty($filtered_data)) {
            return (object) array(
                'avg_temperature' => 0,
                'min_temperature' => 0,
                'max_temperature' => 0,
                'avg_humidity' => 0,
                'min_humidity' => 0,
                'max_humidity' => 0,
                'total_readings' => 0
            );
        }
        
        $temperatures = array_column($filtered_data, 'temperature');
        $humidities = array_column($filtered_data, 'humidity');
        
        return (object) array(
            'avg_temperature' => round(array_sum($temperatures) / count($temperatures), 1),
            'min_temperature' => min($temperatures),
            'max_temperature' => max($temperatures),
            'avg_humidity' => round(array_sum($humidities) / count($humidities), 1),
            'min_humidity' => min($humidities),
            'max_humidity' => max($humidities),
            'total_readings' => count($filtered_data)
        );
    }

    /**
     * Get sensor details for a specific sensor
     * @param string $sensor_id
     * @return object|null
     */
    public function get_sensor_details($sensor_id) {
        $this->db->select('*');
        $this->db->from('telemetry');
        $this->db->where('device_id', $sensor_id);
        $this->db->order_by('timestamp', 'DESC');
        $this->db->limit(1);
        
        $query = $this->db->get();
        return $query->row();
    }

    /**
     * Get sensor readings for a specific sensor
     * @param string $sensor_id
     * @param int $limit Number of readings to return
     * @return array
     */
    public function get_sensor_readings($sensor_id, $limit = 100) {
        $this->db->select('*');
        $this->db->from('telemetry');
        $this->db->where('device_id', $sensor_id);
        $this->db->order_by('timestamp', 'DESC');
        $this->db->limit($limit);
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get list of all devices
     * @return array
     */
    public function get_device_list() {
        $devices = array();
        $device_stats = array();
        
        foreach ($this->data as $record) {
            $device_id = $record['device_id'];
            
            if (!isset($device_stats[$device_id])) {
                $device_stats[$device_id] = array(
                    'device_id' => $device_id,
                    'last_seen' => $record['timestamp'],
                    'total_readings' => 0
                );
            }
            
            $device_stats[$device_id]['total_readings']++;
            
            if (strtotime($record['timestamp']) > strtotime($device_stats[$device_id]['last_seen'])) {
                $device_stats[$device_id]['last_seen'] = $record['timestamp'];
            }
        }
        
        // Sort by last_seen descending
        usort($device_stats, function($a, $b) {
            return strtotime($b['last_seen']) - strtotime($a['last_seen']);
        });
        
        return array_map(function($device) {
            return (object) $device;
        }, $device_stats);
    }

    /**
     * Delete telemetry data by ID
     * @param int $id
     * @return bool
     */
    public function delete_by_id($id) {
        return $this->db->delete('telemetry', array('id' => $id));
    }

    /**
     * Delete telemetry data by device ID
     * @param string $device_id
     * @return bool
     */
    public function delete_by_device_id($device_id) {
        return $this->db->delete('telemetry', array('device_id' => $device_id));
    }

    /**
     * Get total count of telemetry records
     * @param string $device_id Optional device ID filter
     * @param string $search Search term
     * @return int
     */
    public function get_total_count($device_id = null, $search = '') {
        $filtered_data = $this->data;
        
        if ($device_id) {
            $filtered_data = array_filter($filtered_data, function($record) use ($device_id) {
                return $record['device_id'] === $device_id;
            });
        }
        
        if (!empty($search)) {
            $filtered_data = array_filter($filtered_data, function($record) use ($search) {
                return stripos($record['device_id'], $search) !== false;
            });
        }
        
        return count($filtered_data);
    }

    /**
     * Get data for charts (hourly averages)
     * @param string $start_date Start date (Y-m-d format)
     * @param string $end_date End date (Y-m-d format)
     * @param string $device_id Optional device ID filter
     * @return array
     */
    public function get_chart_data($start_date, $end_date, $device_id = null) {
        $filtered_data = $this->get_data_by_date_range($start_date, $end_date, $device_id);
        
        $hourly_data = array();
        
        foreach ($filtered_data as $record) {
            $hour = date('Y-m-d H:00:00', strtotime($record['timestamp']));
            
            if (!isset($hourly_data[$hour])) {
                $hourly_data[$hour] = array(
                    'hour' => $hour,
                    'temperatures' => array(),
                    'humidities' => array(),
                    'readings_count' => 0
                );
            }
            
            $hourly_data[$hour]['temperatures'][] = $record['temperature'];
            $hourly_data[$hour]['humidities'][] = $record['humidity'];
            $hourly_data[$hour]['readings_count']++;
        }
        
        $result = array();
        foreach ($hourly_data as $hour => $data) {
            $result[] = (object) array(
                'hour' => $hour,
                'avg_temperature' => round(array_sum($data['temperatures']) / count($data['temperatures']), 1),
                'avg_humidity' => round(array_sum($data['humidities']) / count($data['humidities']), 1),
                'readings_count' => $data['readings_count']
            );
        }
        
        return $result;
    }

    /**
     * Get data for charts with flexible intervals
     * @param string $start_date Start date (Y-m-d format)
     * @param string $end_date End date (Y-m-d format)
     * @param string $device_id Optional device ID filter
     * @param int $interval_minutes Interval in minutes (15, 30, 60)
     * @return array
     */
    public function get_chart_data_by_interval($start_date, $end_date, $device_id = null, $interval_minutes = 60) {
        $filtered_data = $this->get_data_by_date_range($start_date, $end_date, $device_id);
        
        $interval_data = array();
        
        foreach ($filtered_data as $record) {
            $timestamp = strtotime($record['timestamp']);
            
            if ($interval_minutes == 15) {
                $interval_key = date('Y-m-d H:i', $timestamp - ($timestamp % (15 * 60)));
            } elseif ($interval_minutes == 30) {
                $interval_key = date('Y-m-d H:i', $timestamp - ($timestamp % (30 * 60)));
            } else {
                $interval_key = date('Y-m-d H:00:00', $timestamp);
            }
            
            if (!isset($interval_data[$interval_key])) {
                $interval_data[$interval_key] = array(
                    'time_period' => $interval_key,
                    'temperatures' => array(),
                    'humidities' => array(),
                    'readings_count' => 0
                );
            }
            
            $interval_data[$interval_key]['temperatures'][] = $record['temperature'];
            $interval_data[$interval_key]['humidities'][] = $record['humidity'];
            $interval_data[$interval_key]['readings_count']++;
        }
        
        $result = array();
        foreach ($interval_data as $interval => $data) {
            $result[] = (object) array(
                'time_period' => $data['time_period'],
                'avg_temperature' => round(array_sum($data['temperatures']) / count($data['temperatures']), 1),
                'avg_humidity' => round(array_sum($data['humidities']) / count($data['humidities']), 1),
                'readings_count' => $data['readings_count']
            );
        }
        
        return $result;
    }

    /**
     * Get real-time data for the last N minutes
     * @param int $minutes Number of minutes to look back
     * @param string $device_id Optional device ID filter
     * @return array
     */
    public function get_realtime_data($minutes = 60, $device_id = null) {
        $this->db->select('*');
        $this->db->from('telemetry');
        $this->db->where('timestamp >= datetime("now", "-' . $minutes . ' minutes")');
        
        if ($device_id) {
            $this->db->where('device_id', $device_id);
        }
        
        $this->db->order_by('timestamp', 'ASC');
        
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Get alerts data (values outside normal ranges)
     * @param float $temp_min Minimum temperature threshold
     * @param float $temp_max Maximum temperature threshold
     * @param float $humidity_min Minimum humidity threshold
     * @param float $humidity_max Maximum humidity threshold
     * @param string $device_id Optional device ID filter
     * @return array
     */
    public function get_alerts($temp_min = 15, $temp_max = 35, $humidity_min = 30, $humidity_max = 80, $device_id = null) {
        $this->db->select('*');
        $this->db->from('telemetry');
        
        $this->db->group_start();
        $this->db->where('temperature <', $temp_min);
        $this->db->or_where('temperature >', $temp_max);
        $this->db->or_where('humidity <', $humidity_min);
        $this->db->or_where('humidity >', $humidity_max);
        $this->db->group_end();
        
        if ($device_id) {
            $this->db->where('device_id', $device_id);
        }
        
        $this->db->order_by('timestamp', 'DESC');
        $this->db->limit(50);
        
        $query = $this->db->get();
        return $query->result();
    }



    /**
     * Get paginated telemetry data
     * @param int $limit Number of records per page
     * @param int $offset Offset for pagination
     * @param string $search Search term
     * @return array
     */
    public function get_paginated_data($limit = 10, $offset = 0, $search = '') {
        $filtered_data = $this->data;
        
        if (!empty($search)) {
            $filtered_data = array_filter($filtered_data, function($record) use ($search) {
                return stripos($record['device_id'], $search) !== false;
            });
        }
        
        // Sort by timestamp descending
        usort($filtered_data, function($a, $b) {
            return strtotime($b['timestamp']) - strtotime($a['timestamp']);
        });
        
        $paginated_data = array_slice($filtered_data, $offset, $limit);
        
        return array_map(function($record) {
            return (object) $record;
        }, $paginated_data);
    }

    /**
     * Get device count
     * @return int
     */
    public function get_device_count() {
        $devices = array_unique(array_column($this->data, 'device_id'));
        return count($devices);
    }

    /**
     * Get average temperature
     * @return float
     */
    public function get_average_temperature() {
        $temperatures = array_column($this->data, 'temperature');
        return !empty($temperatures) ? round(array_sum($temperatures) / count($temperatures), 1) : 0.0;
    }

    /**
     * Get average humidity
     * @return float
     */
    public function get_average_humidity() {
        $humidities = array_column($this->data, 'humidity');
        return !empty($humidities) ? round(array_sum($humidities) / count($humidities), 1) : 0.0;
    }

    /**
     * Get last update timestamp
     * @return string
     */
    public function get_last_update() {
        if (empty($this->data)) {
            return 'No data';
        }
        
        $latest = $this->get_latest_data(1);
        if (!empty($latest)) {
            return $latest[0]['timestamp'];
        }
        
        return 'No data';
    }
} 