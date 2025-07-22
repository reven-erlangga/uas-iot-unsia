<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($title) ? $title : 'ESP32 Monitoring'; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="bg-gray-50">
    <nav class="bg-blue-600 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="<?php echo base_url('home'); ?>" class="text-white font-bold text-xl">ESP32 Monitoring</a>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="<?php echo base_url('home'); ?>" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">Home</a>
                    <a href="<?php echo base_url('sensormonitoring'); ?>" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">Sensor Monitoring</a>
                    <a href="<?php echo base_url('espsetup'); ?>" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">ESP Setup</a>
                    <a href="<?php echo base_url('about'); ?>" class="text-white hover:text-blue-200 px-3 py-2 rounded-md text-sm font-medium">About</a>
                </div>
            </div>
        </div>
    </nav>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8"> 