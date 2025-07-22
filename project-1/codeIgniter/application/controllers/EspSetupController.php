<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EspSetupController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load required libraries and models
        $this->load->helper('url');
        $this->load->library('session');
        $this->load->library('form_validation');
        // Don't load model for now to avoid database issues
        // $this->load->model('Telemetry');
    }

    /**
     * Index Page for ESP Setup
     * Maps to: http://example.com/index.php/espsetup
     */
    public function index() {
        $data['title'] = 'ESP32 Setup - ESP32 Monitoring System';
        $data['page'] = 'esp_setup';
        
        // Load the ESP setup view
        $this->load->view('templates/header', $data);
        $this->load->view('espsetup/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Device configuration page
     * Maps to: http://example.com/index.php/espsetup/config
     */
    public function config() {
        $data['title'] = 'Device Configuration - ESP32 Monitoring System';
        $data['page'] = 'esp_config';
        
        // Load the configuration view
        $this->load->view('templates/header', $data);
        $this->load->view('espsetup/config', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Save device configuration
     * Maps to: http://example.com/index.php/espsetup/save_config
     */
    public function save_config() {
        // Set validation rules
        $this->form_validation->set_rules('device_name', 'Device Name', 'required|trim');
        $this->form_validation->set_rules('device_id', 'Device ID', 'required|trim');
        $this->form_validation->set_rules('wifi_ssid', 'WiFi SSID', 'required|trim');
        $this->form_validation->set_rules('wifi_password', 'WiFi Password', 'required|trim');
        $this->form_validation->set_rules('server_url', 'Server URL', 'required|trim|valid_url');

        if ($this->form_validation->run() == FALSE) {
            // Validation failed, show config page again
            $this->config();
        } else {
            // Validation passed, save configuration
            $config_data = array(
                'device_name' => $this->input->post('device_name'),
                'device_id' => $this->input->post('device_id'),
                'wifi_ssid' => $this->input->post('wifi_ssid'),
                'wifi_password' => $this->input->post('wifi_password'),
                'server_url' => $this->input->post('server_url'),
                'created_at' => date('Y-m-d H:i:s')
            );

            // Save to session for now (you might want to save to database)
            $this->session->set_userdata('esp_config', $config_data);
            
            // Set success message
            $this->session->set_flashdata('success', 'ESP32 configuration saved successfully!');
            
            // Redirect to dashboard
            redirect('home/dashboard');
        }
    }

    /**
     * Device status page
     * Maps to: http://example.com/index.php/espsetup/status
     */
    public function status() {
        $data['title'] = 'Device Status - ESP32 Monitoring System';
        $data['page'] = 'esp_status';
        
        // Get device configuration from session
        $data['esp_config'] = $this->session->userdata('esp_config');
        
        // Load the status view
        $this->load->view('templates/header', $data);
        $this->load->view('espsetup/status', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Reset device configuration
     * Maps to: http://example.com/index.php/espsetup/reset
     */
    public function reset() {
        // Clear ESP configuration from session
        $this->session->unset_userdata('esp_config');
        
        // Set success message
        $this->session->set_flashdata('success', 'ESP32 configuration has been reset!');
        
        // Redirect to setup page
        redirect('espsetup');
    }
} 