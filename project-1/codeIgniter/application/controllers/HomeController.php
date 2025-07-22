<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class HomeController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Load any required libraries, helpers, or models
        $this->load->helper('url');
        $this->load->library('session');
    }

    /**
     * Index Page for this controller.
     * Maps to: http://example.com/index.php/home
     */
    public function index() {
        $data['title'] = 'Home - ESP32 Monitoring System';
        $data['page'] = 'home';
        
        // Load the home view
        $this->load->view('templates/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('templates/footer');
    }

    /**
     * Dashboard page
     * Maps to: http://example.com/index.php/home/dashboard
     */
    public function dashboard() {
        $data['title'] = 'Dashboard - ESP32 Monitoring System';
        $data['page'] = 'dashboard';
        
        // Load the dashboard view
        $this->load->view('templates/header', $data);
        $this->load->view('home/dashboard', $data);
        $this->load->view('templates/footer');
    }

    /**
     * About page
     * Maps to: http://example.com/index.php/home/about
     */
    public function about() {
        $data['title'] = 'About - ESP32 Monitoring System';
        $data['page'] = 'about';
        
        // Load the about view
        $this->load->view('templates/header', $data);
        $this->load->view('home/about', $data);
        $this->load->view('templates/footer');
    }
} 