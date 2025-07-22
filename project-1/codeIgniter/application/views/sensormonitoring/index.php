<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<style>
.column-header, .column-cell {
    transition: opacity 0.15s ease-in-out;
}

.toast-notification {
    position: fixed;
    top: 20px;
    right: 20px;
    background: #10B981;
    color: white;
    padding: 12px 20px;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    z-index: 1000;
    transform: translateX(100%);
    transition: transform 0.3s ease-in-out;
}

.toast-notification.show {
    transform: translateX(0);
}
</style>
<div class="py-8">
    <!-- Header Section -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-gray-900 mb-2">
            IoT Sensor Monitoring Dashboard
        </h1>
        <p class="text-gray-600">
            Real-time monitoring of temperature, humidity, and device status
        </p>
    </div>

    <!-- Status Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-blue-500">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Active Devices</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $monitoring_telemetry['deviceCount']; ?></p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-green-500">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Temperature</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $monitoring_telemetry['avgTemperature']; ?>°C</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-purple-500">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Avg Humidity</p>
                    <p class="text-2xl font-bold text-gray-900"><?php echo $monitoring_telemetry['avgHumidity']; ?>%</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6 border-l-4 border-orange-500">
            <div class="flex items-center">
                <div class="p-2 bg-orange-100 rounded-lg">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Last Update</p>
                    <p class="text-lg font-bold text-gray-900"><?php echo $monitoring_telemetry['lastUpdate']; ?></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart Section -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Sensor Data Chart</h3>
            <div class="flex items-center space-x-4">
                <!-- Date Selector -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Date:</label>
                    <select id="chartDate" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <?php foreach ($available_dates as $date): ?>
                            <option value="<?php echo $date; ?>" <?php echo ($date == date('Y-m-d')) ? 'selected' : ''; ?>>
                                <?php echo date('M j, Y', strtotime($date)); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Device Selector -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Device:</label>
                    <select id="chartDevice" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <?php $firstDevice = true; foreach ($chart_data['devices'] as $deviceId => $deviceName): ?>
                            <option value="<?php echo $deviceId; ?>" <?php echo $firstDevice ? 'selected' : ''; $firstDevice = false; ?>><?php echo $deviceName; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <!-- Interval Selector -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Interval:</label>
                    <select id="chartInterval" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="15">15 minutes</option>
                        <option value="30" selected>30 minutes</option>
                        <option value="60">1 hour</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Chart Container -->
        <div class="relative h-96">
            <canvas id="telemetryChart"></canvas>
            
            <!-- Loading overlay -->
            <div id="chartLoading" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center hidden">
                <div class="flex items-center space-x-2">
                    <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span class="text-gray-600">Loading chart data...</span>
                </div>
            </div>
            
            <!-- No data message -->
            <div id="chartNoData" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center hidden">
                <div class="text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <p class="mt-2 text-gray-500">No data available for the selected criteria</p>
                </div>
            </div>
        </div>
        
        <!-- Chart Legend -->
        <div class="flex items-center justify-center space-x-6 mt-4">
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-red-500 rounded"></div>
                <span class="text-sm text-gray-700">Temperature (°C)</span>
            </div>
            <div class="flex items-center space-x-2">
                <div class="w-4 h-4 bg-blue-500 rounded"></div>
                <span class="text-sm text-gray-700">Humidity (%)</span>
            </div>
        </div>
    </div>

    <!-- Data Table Section -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900">Sensor Data Table</h2>
                    <p class="text-sm text-gray-500 mt-1">Showing <span id="activeColumnsCount">6</span> of 6 columns</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Column Selector -->
                    <div class="relative">
                        <button
                            id="columnSelectorBtn"
                            class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            title="Click to show/hide columns (Ctrl+Shift+C)"
                        >
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                            </svg>
                            Columns <span id="columnCount" class="ml-1 bg-blue-500 text-white text-xs rounded-full px-2 py-1">6</span>
                        </button>
                        
                        <!-- Column Selector Dropdown -->
                        <div id="columnSelectorDropdown" class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-50 border border-gray-200 hidden">
                            <div class="py-2">
                                <div class="px-4 py-2 border-b border-gray-200">
                                    <h3 class="text-sm font-medium text-gray-900">Select Columns</h3>
                                    <p class="text-xs text-gray-500 mt-1">Currently showing <span id="activeColumnsText">6</span> columns</p>
                                    <p class="text-xs text-gray-400 mt-1">Press Ctrl+Shift+C to toggle</p>
                                </div>
                                <div class="max-h-64 overflow-y-auto">
                                    <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer" title="Toggle ID column visibility">
                                        <input type="checkbox" id="col-id" class="column-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="id" checked>
                                        <span class="ml-3 text-sm text-gray-700">ID</span>
                                    </label>
                                    <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer" title="Toggle Device ID column visibility">
                                        <input type="checkbox" id="col-device_id" class="column-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="device_id" checked>
                                        <span class="ml-3 text-sm text-gray-700">Device ID</span>
                                    </label>
                                    <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer" title="Toggle Temperature column visibility">
                                        <input type="checkbox" id="col-temperature" class="column-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="temperature" checked>
                                        <span class="ml-3 text-sm text-gray-700">Temperature</span>
                                    </label>
                                    <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer" title="Toggle Humidity column visibility">
                                        <input type="checkbox" id="col-humidity" class="column-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="humidity" checked>
                                        <span class="ml-3 text-sm text-gray-700">Humidity</span>
                                    </label>

                                    <label class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer" title="Toggle Timestamp column visibility">
                                        <input type="checkbox" id="col-timestamp" class="column-checkbox h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded" value="timestamp" checked>
                                        <span class="ml-3 text-sm text-gray-700">Timestamp</span>
                                    </label>
                                </div>
                                <div class="px-4 py-2 border-t border-gray-200 flex justify-between">
                                    <button
                                        id="selectAllColumns"
                                        class="text-sm text-blue-600 hover:text-blue-800"
                                    >
                                        Select All
                                    </button>
                                    <button
                                        id="deselectAllColumns"
                                        class="text-sm text-red-600 hover:text-red-800"
                                    >
                                        Deselect All
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Box -->
                    <div class="relative">
                        <form method="GET" action="<?php echo base_url('sensormonitoring'); ?>">
                            <input type="text" name="search" value="<?php echo $this->input->get('search'); ?>" placeholder="Search devices..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </form>
                    </div>
                    <!-- Items per page selector -->
                    <select onchange="window.location.href='<?php echo base_url('sensormonitoring'); ?>?per_page=' + this.value + '&search=<?php echo $this->input->get('search'); ?>'" class="border border-gray-300 rounded-md text-sm px-2 py-1">
                        <option value="5" <?php echo ($this->input->get('per_page') == 5) ? 'selected' : ''; ?>>5 per page</option>
                        <option value="10" <?php echo ($this->input->get('per_page') == 10 || !$this->input->get('per_page')) ? 'selected' : ''; ?>>10 per page</option>
                        <option value="15" <?php echo ($this->input->get('per_page') == 15) ? 'selected' : ''; ?>>15 per page</option>
                        <option value="25" <?php echo ($this->input->get('per_page') == 25) ? 'selected' : ''; ?>>25 per page</option>
                        <option value="50" <?php echo ($this->input->get('per_page') == 50) ? 'selected' : ''; ?>>50 per page</option>
                    </select>
                    <a href="<?php echo base_url('sensormonitoring'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">
                        Refresh Data
                    </a>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="column-header px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" data-column="station_id">Station ID</th>
                        <th class="column-header px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" data-column="id">ID</th>
                        <th class="column-header px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" data-column="device_id">Device ID</th>
                        <th class="column-header px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" data-column="temperature">Temperature</th>
                        <th class="column-header px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" data-column="humidity">Humidity</th>
                        <th class="column-header px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider" data-column="timestamp">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if (!empty($telemetries)): ?>
                        <?php foreach ($telemetries as $telemetry): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="column-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-column="station_id"><?php echo $telemetry->station_id ?? '-'; ?></td>
                            <td class="column-cell px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900" data-column="id"><?php echo $telemetry->id; ?></td>
                            <td class="column-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-column="device_id">
                                <div class="flex items-center">
                                    <div class="w-2 h-2 rounded-full mr-2 bg-green-400"></div>
                                    <?php echo $telemetry->device_id; ?>
                                </div>
                            </td>
                            <td class="column-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-column="temperature">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <span class="font-medium"><?php echo $telemetry->temperature ? $telemetry->temperature . '°C' : 'N/A'; ?></span>
                                </div>
                            </td>
                            <td class="column-cell px-6 py-4 whitespace-nowrap text-sm text-gray-900" data-column="humidity">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                    </svg>
                                    <span class="font-medium"><?php echo $telemetry->humidity ? $telemetry->humidity . '%' : 'N/A'; ?></span>
                                </div>
                            </td>
                            <td class="column-cell px-6 py-4 whitespace-nowrap text-sm text-gray-500" data-column="timestamp"><?php echo date('M j, Y H:i:s', strtotime($telemetry->timestamp)); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No data available</td>
                        </tr>
                    <?php endif; ?>
                    
                    <!-- No columns selected message -->
                    <tr id="noColumnsMessage" class="hidden">
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium">No columns selected</p>
                                <p class="text-sm">Please select at least one column to display data</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <?php if (!empty($pagination)): ?>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            <div class="flex-1 flex justify-between sm:hidden">
                <a href="<?php echo base_url('sensormonitoring?page=' . max(1, $this->input->get('page') - 1)); ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Previous</a>
                <a href="<?php echo base_url('sensormonitoring?page=' . ($this->input->get('page') + 1)); ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">Next</a>
            </div>
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing data from database
                    </p>
                    <p class="text-xs text-gray-500 mt-1">
                        <span id="footerColumnsCount">6</span> columns visible
                    </p>
                </div>
                <div class="flex items-center space-x-2">
                    <?php echo $pagination; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Toast Notification -->
<div id="toastNotification" class="toast-notification hidden">
    <div class="flex items-center">
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
        </svg>
        <span id="toastMessage">Column visibility updated</span>
    </div>
</div>

<script>
// Chart.js configuration and functionality
let telemetryChart = null;
let updateTimeout = null;
let lastFetchId = 0; // Add fetch id for race condition prevention

// Initialize chart data from PHP
const chartData = <?php echo json_encode($chart_data); ?>;
const baseUrl = '<?php echo base_url(); ?>';

// Column visibility state
let visibleColumns = JSON.parse(localStorage.getItem('sensorMonitoringVisibleColumns')) || 
                    JSON.parse(sessionStorage.getItem('sensorMonitoringVisibleColumns')) || 
                    ['id', 'device_id', 'temperature', 'humidity', 'timestamp'];

// Chart configuration
function createChart(labels, temperatureData, humidityData) {
    const ctx = document.getElementById('telemetryChart').getContext('2d');
    
    // Destroy existing chart if it exists
    if (telemetryChart) {
        telemetryChart.destroy();
    }
    
    telemetryChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Temperature (°C)',
                    data: temperatureData,
                    borderColor: 'rgb(239, 68, 68)',
                    backgroundColor: 'rgba(239, 68, 68, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y'
                },
                {
                    label: 'Humidity (%)',
                    data: humidityData,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 2,
                    fill: false,
                    tension: 0.4,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    yAxisID: 'y1'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(255, 255, 255, 0.1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: true,
                    callbacks: {
                        label: function(context) {
                            let label = context.dataset.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed.y !== null) {
                                label += context.parsed.y.toFixed(1);
                            }
                            return label;
                        }
                    }
                }
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Time'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    }
                },
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    title: {
                        display: true,
                        text: 'Temperature (°C)'
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.1)'
                    },
                    min: 0,
                    max: 50
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    title: {
                        display: true,
                        text: 'Humidity (%)'
                    },
                    grid: {
                        drawOnChartArea: false,
                    },
                    min: 0,
                    max: 100
                }
            }
        }
    });
}

// Update chart with new data
function updateChart() {
    const date = document.getElementById('chartDate').value;
    const device = document.getElementById('chartDevice').value;
    const interval = document.getElementById('chartInterval').value;
    
    // Require device selection
    if (!device) {
        document.getElementById('chartNoData').classList.remove('hidden');
        if (telemetryChart) telemetryChart.destroy();
        return;
    }
    // Show loading
    document.getElementById('chartLoading').classList.remove('hidden');
    document.getElementById('chartNoData').classList.add('hidden');
    
    if (updateTimeout) {
        clearTimeout(updateTimeout);
    }
    
    // Create unique fetch id for each request
    const fetchId = ++lastFetchId;
    
    // Debounce the update
    updateTimeout = setTimeout(() => {
        fetch(`${baseUrl}index.php/sensormonitoring/get_chart_data?date=${date}&device=${device}&interval=${interval}`)
            .then(response => response.json())
            .then(data => {
                // Only process response if this is the latest fetch
                if (fetchId !== lastFetchId) return;
                document.getElementById('chartLoading').classList.add('hidden');
                if (data.labels && data.labels.length > 0) {
                    createChart(data.labels, data.temperatureData, data.humidityData);
                } else {
                    document.getElementById('chartNoData').classList.remove('hidden');
                }
            })
            .catch(error => {
                if (fetchId !== lastFetchId) return;
                console.error('Error fetching chart data:', error);
                document.getElementById('chartLoading').classList.add('hidden');
                document.getElementById('chartNoData').classList.remove('hidden');
            });
    }, 500);
}

// Column visibility functions
function toggleColumnVisibility(columnKey, isVisible) {
    const headers = document.querySelectorAll(`.column-header[data-column="${columnKey}"]`);
    const cells = document.querySelectorAll(`.column-cell[data-column="${columnKey}"]`);
    
    headers.forEach(header => {
        if (isVisible) {
            header.style.display = 'table-cell';
            header.style.opacity = '1';
        } else {
            header.style.opacity = '0';
            setTimeout(() => {
                header.style.display = 'none';
            }, 150);
        }
    });
    
    cells.forEach(cell => {
        if (isVisible) {
            cell.style.display = 'table-cell';
            cell.style.opacity = '1';
        } else {
            cell.style.opacity = '0';
            setTimeout(() => {
                cell.style.display = 'none';
            }, 150);
        }
    });
}

function updateColumnVisibility() {
    // Show loading state on column selector button
    const columnSelectorBtn = document.getElementById('columnSelectorBtn');
    const originalText = columnSelectorBtn.innerHTML;
    columnSelectorBtn.innerHTML = `
        <svg class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
        </svg>
        Updating...
    `;
    
    // Update visible columns array
    visibleColumns = [];
    document.querySelectorAll('.column-checkbox:checked').forEach(checkbox => {
        visibleColumns.push(checkbox.value);
    });
    
    // Save to localStorage and sessionStorage
    localStorage.setItem('sensorMonitoringVisibleColumns', JSON.stringify(visibleColumns));
    sessionStorage.setItem('sensorMonitoringVisibleColumns', JSON.stringify(visibleColumns));
    
    // Update column count badge
    const columnCount = document.getElementById('columnCount');
    const activeColumnsText = document.getElementById('activeColumnsText');
    const activeColumnsCount = document.getElementById('activeColumnsCount');
    const footerColumnsCount = document.getElementById('footerColumnsCount');
    if (columnCount) {
        columnCount.textContent = visibleColumns.length;
    }
    if (activeColumnsText) {
        activeColumnsText.textContent = visibleColumns.length;
    }
    if (activeColumnsCount) {
        activeColumnsCount.textContent = visibleColumns.length;
    }
    if (footerColumnsCount) {
        footerColumnsCount.textContent = visibleColumns.length;
    }
    
    // Show/hide no columns message
    const noColumnsMessage = document.getElementById('noColumnsMessage');
    const hasData = <?php echo !empty($telemetries) ? 'true' : 'false'; ?>;
    
    if (visibleColumns.length === 0) {
        if (noColumnsMessage) {
            noColumnsMessage.classList.remove('hidden');
        }
        // Hide all data rows when no columns are selected
        document.querySelectorAll('tbody tr:not(#noColumnsMessage)').forEach(row => {
            row.style.display = 'none';
        });
    } else {
        if (noColumnsMessage) {
            noColumnsMessage.classList.add('hidden');
        }
        // Show data rows when columns are selected
        document.querySelectorAll('tbody tr:not(#noColumnsMessage)').forEach(row => {
            row.style.display = 'table-row';
        });
    }
    
    // Update all columns visibility
    const allColumns = ['id', 'device_id', 'temperature', 'humidity', 'timestamp'];
    allColumns.forEach(column => {
        const isVisible = visibleColumns.includes(column);
        toggleColumnVisibility(column, isVisible);
    });
    
    // Restore button text after a short delay
    setTimeout(() => {
        columnSelectorBtn.innerHTML = originalText;
    }, 300);
    
    // Log for debugging
    console.log('Column visibility updated:', visibleColumns);
    console.log('Active columns:', visibleColumns.join(', '));
}

function selectAllColumns() {
    document.querySelectorAll('.column-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
    updateColumnVisibility();
    showToast('All columns selected');
}

function deselectAllColumns() {
    document.querySelectorAll('.column-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
    updateColumnVisibility();
    showToast('All columns deselected');
}

function showToast(message) {
    const toast = document.getElementById('toastNotification');
    const toastMessage = document.getElementById('toastMessage');
    
    if (toast && toastMessage) {
        toastMessage.textContent = message;
        toast.classList.remove('hidden');
        toast.classList.add('show');
        
        setTimeout(() => {
            toast.classList.remove('show');
            setTimeout(() => {
                toast.classList.add('hidden');
            }, 300);
        }, 2000);
    }
}

// Initialize chart on page load
document.addEventListener('DOMContentLoaded', function() {
    // If no device is selected, select the first one
    const chartDevice = document.getElementById('chartDevice');
    if (chartDevice && !chartDevice.value) {
        chartDevice.selectedIndex = 0;
    }
    // Create initial chart
    if (chartData.labels && chartData.labels.length > 0) {
        createChart(chartData.labels, chartData.temperatureData, chartData.humidityData);
    }
    
    // Add event listeners for chart controls
    document.getElementById('chartDate').addEventListener('change', updateChart);
    document.getElementById('chartDevice').addEventListener('change', updateChart);
    document.getElementById('chartInterval').addEventListener('change', updateChart);
    
    // Column selector functionality
    const columnSelectorBtn = document.getElementById('columnSelectorBtn');
    const columnSelectorDropdown = document.getElementById('columnSelectorDropdown');
    const selectAllBtn = document.getElementById('selectAllColumns');
    const deselectAllBtn = document.getElementById('deselectAllColumns');
    
    // Toggle column selector dropdown
    columnSelectorBtn.addEventListener('click', function(e) {
        e.stopPropagation();
        columnSelectorDropdown.classList.toggle('hidden');
    });
    
    // Close dropdown when clicking outside
    document.addEventListener('click', function(e) {
        if (!columnSelectorBtn.contains(e.target) && !columnSelectorDropdown.contains(e.target)) {
            columnSelectorDropdown.classList.add('hidden');
        }
    });
    
    // Keyboard shortcut for column selector (Ctrl+Shift+C)
    document.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.shiftKey && e.key === 'C') {
            e.preventDefault();
            columnSelectorDropdown.classList.toggle('hidden');
        }
    });
    
    // Column checkbox event listeners
    document.querySelectorAll('.column-checkbox').forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            updateColumnVisibility();
            const columnName = this.nextElementSibling.textContent;
            const action = this.checked ? 'shown' : 'hidden';
            showToast(`${columnName} column ${action}`);
        });
    });
    
    // Select all / deselect all buttons
    selectAllBtn.addEventListener('click', selectAllColumns);
    deselectAllBtn.addEventListener('click', deselectAllColumns);
    
    // Initialize column visibility based on saved preferences
    const allColumns = ['id', 'device_id', 'temperature', 'humidity', 'timestamp'];
    
    // Set checkbox states based on saved preferences
    allColumns.forEach(column => {
        const checkbox = document.getElementById(`col-${column}`);
        if (checkbox) {
            checkbox.checked = visibleColumns.includes(column);
        }
    });
    
    updateColumnVisibility();
    
    // Log initial state
    console.log('Initial column visibility loaded:', visibleColumns);
});

// Auto-refresh chart every 30 seconds
setInterval(() => {
    updateChart();
}, 30000);
</script> 