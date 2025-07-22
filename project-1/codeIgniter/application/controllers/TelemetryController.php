<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class TelemetryController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load required libraries and models
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->model('Telemetry');
        
        // Set JSON content type for API responses
        $this->output->set_content_type('application/json');
        
        // Add CORS headers for API access
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: Content-Type, Authorization');
        
        // Handle preflight OPTIONS request
        if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
            http_response_code(200);
            exit();
        }
    }

    /**
     * Index method for testing
     * Maps to: http://example.com/index.php/telemetry
     * Method: GET
     */
    public function index() {
        $this->output->set_output(json_encode(array(
            'status' => 'success',
            'message' => 'TelemetryController is working',
            'available_methods' => array(
                'health' => 'GET /api/telemetry/health',
                'receive' => 'POST /api/telemetry/receive',
                'latest' => 'GET /api/telemetry/latest',
                'devices' => 'GET /api/telemetry/devices'
            ),
            'timestamp' => date('Y-m-d H:i:s')
        )));
    }

    /**
     * Receive telemetry data from ESP32
     * Maps to: http://example.com/index.php/telemetry/receive
     * Method: POST
     */
    public function receive() {
        // Check if it's a POST request
        if ($this->input->method() !== 'post') {
            $this->output->set_status_header(405);
            $this->output->set_output(json_encode(array(
                'status' => 'error',
                'message' => 'Method not allowed. Use POST.'
            )));
            return;
        }

        // Get JSON input
        $input = json_decode(file_get_contents('php://input'), true);
        
        // Validate required fields
        if (!$input || !isset($input['station_id']) || empty($input['station_id']) || !isset($input['device_id']) || !isset($input['temperature']) || !isset($input['humidity'])) {
            $this->output->set_status_header(400);
            $this->output->set_output(json_encode(array(
                'status' => 'error',
                'message' => 'Missing required fields: station_id, device_id, temperature, humidity'
            )));
            return;
        }

        // Prepare data for insertion
        $telemetry_data = array(
            'station_id' => $input['station_id'],
            'device_id' => $input['device_id'],
            'temperature' => floatval($input['temperature']),
            'humidity' => floatval($input['humidity']),
            'timestamp' => isset($input['timestamp']) ? $input['timestamp'] : date('Y-m-d H:i:s'),
            'created_at' => date('Y-m-d H:i:s')
        );

        // Optional fields
        if (isset($input['latitude'])) {
            $telemetry_data['latitude'] = floatval($input['latitude']);
        }
        if (isset($input['longitude'])) {
            $telemetry_data['longitude'] = floatval($input['longitude']);
        }
        if (isset($input['altitude'])) {
            $telemetry_data['altitude'] = floatval($input['altitude']);
        }
        if (isset($input['battery_level'])) {
            $telemetry_data['battery_level'] = floatval($input['battery_level']);
        }
        if (isset($input['signal_strength'])) {
            $telemetry_data['signal_strength'] = intval($input['signal_strength']);
        }
        // Store metadata as JSON string if present
        if (isset($input['metadata'])) {
            $telemetry_data['metadata'] = is_string($input['metadata']) ? $input['metadata'] : json_encode($input['metadata']);
        } else {
            $telemetry_data['metadata'] = null;
        }

        // Insert data into database
        $result = $this->Telemetry->insert($telemetry_data);

        if ($result) {
            $this->output->set_status_header(201);
            $this->output->set_output(json_encode(array(
                'status' => 'success',
                'message' => 'Telemetry data received successfully',
                'data_id' => $result,
                'station_id' => $input['station_id']
            )));
        } else {
            $this->output->set_status_header(500);
            $this->output->set_output(json_encode(array(
                'status' => 'error',
                'message' => 'Failed to save telemetry data'
            )));
        }
    }

    /**
     * Get latest telemetry data
     * Maps to: http://example.com/index.php/telemetry/latest
     * Method: GET
     */
    public function latest() {
        $device_id = $this->input->get('device_id');
        $limit = $this->input->get('limit') ?: 10;
        
        $data = $this->Telemetry->get_latest_data($limit, $device_id);
        
        $this->output->set_output(json_encode(array(
            'status' => 'success',
            'data' => $data,
            'count' => count($data)
        )));
    }

    /**
     * Get telemetry data by date range
     * Maps to: http://example.com/index.php/telemetry/range
     * Method: GET
     */
    public function range() {
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');
        $device_id = $this->input->get('device_id');
        
        if (!$start_date || !$end_date) {
            $this->output->set_status_header(400);
            $this->output->set_output(json_encode(array(
                'status' => 'error',
                'message' => 'start_date and end_date are required'
            )));
            return;
        }

        $data = $this->Telemetry->get_data_by_date_range($start_date, $end_date, $device_id);
        
        $this->output->set_output(json_encode(array(
            'status' => 'success',
            'data' => $data,
            'count' => count($data),
            'start_date' => $start_date,
            'end_date' => $end_date
        )));
    }

    /**
     * Get telemetry statistics
     * Maps to: http://example.com/index.php/telemetry/stats
     * Method: GET
     */
    public function stats() {
        $device_id = $this->input->get('device_id');
        $period = $this->input->get('period') ?: '24h'; // 24h, 7d, 30d
        
        $stats = $this->Telemetry->get_sensor_statistics($period, $device_id);
        
        $this->output->set_output(json_encode(array(
            'status' => 'success',
            'data' => $stats,
            'period' => $period
        )));
    }

    /**
     * Get device list
     * Maps to: http://example.com/index.php/telemetry/devices
     * Method: GET
     */
    public function devices() {
        $devices = $this->Telemetry->get_device_list();
        
        $this->output->set_output(json_encode(array(
            'status' => 'success',
            'data' => $devices,
            'count' => count($devices)
        )));
    }

    /**
     * Delete telemetry data
     * Maps to: http://example.com/index.php/telemetry/delete
     * Method: DELETE
     */
    public function delete() {
        // Check if it's a DELETE request
        if ($this->input->method() !== 'delete') {
            $this->output->set_status_header(405);
            $this->output->set_output(json_encode(array(
                'status' => 'error',
                'message' => 'Method not allowed. Use DELETE.'
            )));
            return;
        }

        $id = $this->input->get('id');
        $device_id = $this->input->get('device_id');
        
        if (!$id && !$device_id) {
            $this->output->set_status_header(400);
            $this->output->set_output(json_encode(array(
                'status' => 'error',
                'message' => 'Either id or device_id is required'
            )));
            return;
        }

        $result = false;
        if ($id) {
            $result = $this->Telemetry->delete_by_id($id);
        } elseif ($device_id) {
            $result = $this->Telemetry->delete_by_device_id($device_id);
        }

        if ($result) {
            $this->output->set_output(json_encode(array(
                'status' => 'success',
                'message' => 'Data deleted successfully'
            )));
        } else {
            $this->output->set_status_header(500);
            $this->output->set_output(json_encode(array(
                'status' => 'error',
                'message' => 'Failed to delete data'
            )));
        }
    }

    /**
     * Health check endpoint
     * Maps to: http://example.com/index.php/telemetry/health
     * Method: GET
     */
    public function health() {
        $this->output->set_output(json_encode(array(
            'status' => 'success',
            'message' => 'Telemetry API is running',
            'timestamp' => date('Y-m-d H:i:s'),
            'version' => '1.0.0',
            'controller' => 'TelemetryController',
            'method' => 'health'
        )));
    }
} 