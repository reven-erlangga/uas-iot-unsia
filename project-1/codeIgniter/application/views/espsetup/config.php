<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Device Configuration</h1>
                <p class="text-gray-600 mb-8">Configure your ESP32 device settings and WiFi credentials.</p>
                
                <!-- Configuration Form -->
                <form action="<?php echo base_url('espsetup/save_config'); ?>" method="POST" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="device_name" class="block text-sm font-medium text-gray-700 mb-2">Device Name</label>
                            <input type="text" id="device_name" name="device_name" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter device name">
                        </div>
                        
                        <div>
                            <label for="device_id" class="block text-sm font-medium text-gray-700 mb-2">Device ID</label>
                            <input type="text" id="device_id" name="device_id" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter device ID">
                        </div>
                        
                        <div>
                            <label for="wifi_ssid" class="block text-sm font-medium text-gray-700 mb-2">WiFi SSID</label>
                            <input type="text" id="wifi_ssid" name="wifi_ssid" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter WiFi SSID">
                        </div>
                        
                        <div>
                            <label for="wifi_password" class="block text-sm font-medium text-gray-700 mb-2">WiFi Password</label>
                            <input type="password" id="wifi_password" name="wifi_password" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="Enter WiFi password">
                        </div>
                        
                        <div class="md:col-span-2">
                            <label for="server_url" class="block text-sm font-medium text-gray-700 mb-2">Server URL</label>
                            <input type="url" id="server_url" name="server_url" required 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                   placeholder="https://your-domain.com/api/telemetry"
                                   value="<?php echo base_url('api/telemetry'); ?>">
                        </div>
                    </div>
                    
                    <div class="flex justify-end space-x-4">
                        <a href="<?php echo base_url('espsetup'); ?>" 
                           class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            Save Configuration
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div> 