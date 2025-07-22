<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class SensorMonitoringController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load required libraries and helpers
        $this->load->helper('url');
        $this->load->library('session');
        // Load the Telemetry model for real data
        $this->load->model('Telemetry');
    }

    /**
     * Index Page for Sensor Monitoring
     * Maps to: http://example.com/index.php/sensormonitoring
     */
    public function index() {
        $data['title'] = 'Sensor Monitoring - ESP32 Monitoring System';
        $data['page'] = 'sensor_monitoring';
        
        // Get real data from JSON database
        $data['monitoring_telemetry'] = array(
            'deviceCount' => $this->Telemetry->get_device_count(),
            'avgTemperature' => $this->Telemetry->get_average_temperature(),
            'avgHumidity' => $this->Telemetry->get_average_humidity(),
            'lastUpdate' => $this->Telemetry->get_last_update()
        );
        
        // Get pagination parameters
        $page = $this->input->get('page') ?: 1;
        $per_page = $this->input->get('per_page') ?: 10;
        $search = $this->input->get('search') ?: '';
        
        // Calculate offset
        $offset = ($page - 1) * $per_page;
        
        // Get real telemetry data with pagination
        $data['telemetries'] = $this->Telemetry->get_paginated_data($per_page, $offset, $search);
        
        // Get total count for pagination
        $total_count = $this->Telemetry->get_total_count(null, $search);
        $total_pages = ceil($total_count / $per_page);
        
        // Generate pagination HTML
        $data['pagination'] = $this->generate_pagination($page, $total_pages, $per_page, $search);
        
        // Chart data - using real data from JSON
        $data['chart_data'] = $this->getChartData();
        $data['available_dates'] = $this->getAvailableDates();
        
        // Load the sensor monitoring view
        $this->load->view('templates/header', $data);
        $this->load->view('sensormonitoring/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Generate pagination HTML
     */
    private function generate_pagination($current_page, $total_pages, $per_page, $search) {
        if ($total_pages <= 1) {
            return '';
        }
        
        $html = '<nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">';
        
        // Previous button
        if ($current_page > 1) {
            $prev_url = base_url('sensormonitoring?page=' . ($current_page - 1) . '&per_page=' . $per_page);
            if ($search) $prev_url .= '&search=' . urlencode($search);
            $html .= '<a href="' . $prev_url . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-l-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>';
        } else {
            $html .= '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-l-md text-gray-300 bg-gray-100">Previous</span>';
        }
        
        // Page numbers
        $start_page = max(1, $current_page - 2);
        $end_page = min($total_pages, $current_page + 2);
        
        for ($i = $start_page; $i <= $end_page; $i++) {
            $url = base_url('sensormonitoring?page=' . $i . '&per_page=' . $per_page);
            if ($search) $url .= '&search=' . urlencode($search);
            
            if ($i == $current_page) {
                $html .= '<span class="z-10 bg-blue-50 border-blue-500 text-blue-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">' . $i . '</span>';
            } else {
                $html .= '<a href="' . $url . '" class="relative inline-flex items-center px-4 py-2 border text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">' . $i . '</a>';
            }
        }
        
        // Next button
        if ($current_page < $total_pages) {
            $next_url = base_url('sensormonitoring?page=' . ($current_page + 1) . '&per_page=' . $per_page);
            if ($search) $next_url .= '&search=' . urlencode($search);
            $html .= '<a href="' . $next_url . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-700 bg-white hover:bg-gray-50">Next</a>';
        } else {
            $html .= '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-r-md text-gray-300 bg-gray-100">Next</span>';
        }
        
        $html .= '</nav>';
        return $html;
    }

    /**
     * Get chart data for the monitoring dashboard
     */
    private function getChartData() {
        // Get real chart data from JSON database for the last 24 hours
        $end_date = date('Y-m-d');
        $start_date = date('Y-m-d', strtotime('-1 day'));
        
        $chart_data = $this->Telemetry->get_chart_data($start_date, $end_date);
        
        $labels = array();
        $temperatureData = array();
        $humidityData = array();
        $devices = array();
        
        // Process the chart data
        foreach ($chart_data as $data) {
            $labels[] = date('H:i', strtotime($data->hour));
            $temperatureData[] = round($data->avg_temperature, 1);
            $humidityData[] = round($data->avg_humidity, 1);
        }
        
        // Get device list for selector
        $device_list = $this->Telemetry->get_device_list();
        foreach ($device_list as $device) {
            $devices[$device->device_id] = $device->device_id; // You can customize device names here
        }
        
        return array(
            'labels' => $labels,
            'temperatureData' => $temperatureData,
            'humidityData' => $humidityData,
            'devices' => $devices
        );
    }
    
    /**
     * Get available dates for chart filtering
     */
    private function getAvailableDates() {
        $dates = array();
        for ($i = 7; $i >= 0; $i--) {
            $dates[] = date('Y-m-d', strtotime("-$i days"));
        }
        return $dates;
    }

    /**
     * API endpoint to get chart data
     * Maps to: http://example.com/index.php/sensormonitoring/get_chart_data
     */
    public function get_chart_data() {
        $date = $this->input->get('date') ?: date('Y-m-d');
        $device = $this->input->get('device') ?: '';
        $interval = $this->input->get('interval') ?: 30;
        
        // Get real chart data based on parameters
        $chartData = $this->getChartDataByParams($date, $device, $interval);
        
        header('Content-Type: application/json');
        echo json_encode($chartData);
    }
    
    /**
     * Get chart data based on parameters
     */
    private function getChartDataByParams($date, $device, $interval) {
        // Get data for the specified date with interval
        $chart_data = $this->Telemetry->get_chart_data_by_interval($date, $date, $device, $interval);
        
        $labels = array();
        $temperatureData = array();
        $humidityData = array();
        
        // Process data based on interval
        foreach ($chart_data as $data) {
            // Format time label based on interval
            if ($interval == 15) {
                $labels[] = date('H:i', strtotime($data->time_period));
            } elseif ($interval == 30) {
                $labels[] = date('H:i', strtotime($data->time_period));
            } else {
                $labels[] = date('H:i', strtotime($data->time_period));
            }
            
            $temperatureData[] = round($data->avg_temperature, 1);
            $humidityData[] = round($data->avg_humidity, 1);
        }
        
        return array(
            'labels' => $labels,
            'temperatureData' => $temperatureData,
            'humidityData' => $humidityData,
            'date' => $date,
            'device' => $device,
            'interval' => $interval
        );
    }

    /**
     * Real-time monitoring page
     * Maps to: http://example.com/index.php/sensormonitoring/realtime
     */
    public function realtime() {
        $data['title'] = 'Real-time Monitoring - ESP32 Monitoring System';
        $data['page'] = 'realtime_monitoring';
        
        // Load the real-time monitoring view
        $this->load->view('templates/header', $data);
        $this->load->view('sensormonitoring/realtime', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Historical data page
     * Maps to: http://example.com/index.php/sensormonitoring/history
     */
    public function history() {
        $data['title'] = 'Historical Data - ESP32 Monitoring System';
        $data['page'] = 'historical_data';
        
        // Mock historical data
        $data['historical_data'] = array();
        $data['start_date'] = date('Y-m-d', strtotime('-7 days'));
        $data['end_date'] = date('Y-m-d');
        
        // Load the historical data view
        $this->load->view('templates/header', $data);
        $this->load->view('sensormonitoring/history', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Sensor details page
     * Maps to: http://example.com/index.php/sensormonitoring/details
     */
    public function details() {
        $data['title'] = 'Sensor Details - ESP32 Monitoring System';
        $data['page'] = 'sensor_details';
        
        // Mock sensor details
        $data['sensor_details'] = (object) array(
            'device_id' => 'ESP32_001',
            'last_seen' => date('Y-m-d H:i:s'),
            'total_readings' => 150
        );
        $data['sensor_readings'] = array();
        
        // Load the sensor details view
        $this->load->view('templates/header', $data);
        $this->load->view('sensormonitoring/details', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Export data functionality
     * Maps to: http://example.com/index.php/sensormonitoring/export
     */
    public function export() {
        // Mock export functionality
        $data = array(
            array('id' => 1, 'device_id' => 'ESP32_001', 'temperature' => 25.4, 'humidity' => 60.2, 'timestamp' => date('Y-m-d H:i:s')),
            array('id' => 2, 'device_id' => 'ESP32_002', 'temperature' => 24.9, 'humidity' => 63.5, 'timestamp' => date('Y-m-d H:i:s', strtotime('-1 hour')))
        );
        
        $format = $this->input->get('format') ?: 'csv';
        
        if ($format === 'json') {
            header('Content-Type: application/json');
            echo json_encode($data);
        } else {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="telemetry_export.csv"');
            
            $output = fopen('php://output', 'w');
            fputcsv($output, array('ID', 'Device ID', 'Temperature', 'Humidity', 'Timestamp'));
            foreach ($data as $row) {
                fputcsv($output, $row);
            }
            fclose($output);
        }
    }

    /**
     * API endpoint to get telemetry data
     * Maps to: http://example.com/index.php/sensormonitoring/get_data
     */
    public function get_data() {
        // Mock API response
        $data = array(
            'status' => 'success',
            'data' => array(
                array(
                    'id' => 1,
                    'device_id' => 'ESP32_001',
                    'temperature' => 25.4,
                    'humidity' => 60.2,
                    'timestamp' => date('Y-m-d H:i:s')
                )
            ),
            'total' => 1
        );
        
        header('Content-Type: application/json');
        echo json_encode($data);
    }
} 