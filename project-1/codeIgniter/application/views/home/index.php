<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="text-center py-12">
    <h1 class="text-4xl font-bold text-gray-900 mb-6">Welcome to ESP32 Monitoring System</h1>
    <p class="text-xl text-gray-600 mb-8">This is the Home Dashboard. Use the navigation bar to access features.</p>
    <div class="flex justify-center space-x-4">
        <a href="<?php echo base_url('sensormonitoring'); ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">Sensor Monitoring</a>
        <a href="<?php echo base_url('espsetup'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">ESP Setup</a>
        <a href="<?php echo base_url('about'); ?>" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">About Project</a>
    </div>
</div> 