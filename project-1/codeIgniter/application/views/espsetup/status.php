<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Device Status</h1>
                <p class="text-gray-600 mb-8">Monitor the status and configuration of your ESP32 devices.</p>
                
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">
                                    <?php echo $this->session->flashdata('success'); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
                
                <!-- Device Configuration Status -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Configuration Status</h2>
                    
                    <?php if (isset($esp_config) && $esp_config): ?>
                        <div class="bg-gray-50 rounded-lg p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Device Name</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo $esp_config['device_name']; ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Device ID</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo $esp_config['device_id']; ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">WiFi SSID</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo $esp_config['wifi_ssid']; ?></p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Server URL</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo $esp_config['server_url']; ?></p>
                                </div>
                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700">Configuration Date</label>
                                    <p class="mt-1 text-sm text-gray-900"><?php echo $esp_config['created_at']; ?></p>
                                </div>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">No Configuration Found</h3>
                                    <p class="mt-1 text-sm text-yellow-700">
                                        No ESP32 configuration has been set up yet. Please configure your device first.
                                    </p>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Device Connection Status -->
                <div class="mb-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Connection Status</h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">WiFi Connection</p>
                                    <p class="text-sm text-gray-500">Connected</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Server Connection</p>
                                    <p class="text-sm text-gray-500">Online</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-white border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <div class="w-3 h-3 bg-green-400 rounded-full"></div>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">Data Transmission</p>
                                    <p class="text-sm text-gray-500">Active</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="flex justify-center space-x-4">
                    <a href="<?php echo base_url('espsetup'); ?>" 
                       class="px-4 py-2 bg-blue-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Back to Setup
                    </a>
                    <a href="<?php echo base_url('espsetup/config'); ?>" 
                       class="px-4 py-2 bg-green-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                        Edit Configuration
                    </a>
                    <a href="<?php echo base_url('espsetup/reset'); ?>" 
                       onclick="return confirm('Are you sure you want to reset the configuration?')"
                       class="px-4 py-2 bg-red-600 border border-transparent rounded-md text-sm font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500">
                        Reset Configuration
                    </a>
                </div>
            </div>
        </div>
    </div>
</div> 