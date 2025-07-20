<template>
    <AuthenticatedLayout :app-name="appName">
        <div class="py-8">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                                <p class="text-2xl font-bold text-gray-900">{{ activeDevices }}</p>
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
                                <p class="text-2xl font-bold text-gray-900">{{ averageTemperature }}°C</p>
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
                                <p class="text-2xl font-bold text-gray-900">{{ averageHumidity }}%</p>
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
                                <p class="text-lg font-bold text-gray-900">{{ lastUpdate }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Data Table Section -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900">Sensor Data Table</h2>
                            <div class="flex items-center space-x-4">
                                <div class="relative">
                                    <input
                                        v-model="searchQuery"
                                        type="text"
                                        placeholder="Search devices..."
                                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    >
                                    <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                    </svg>
                                </div>
                                <button 
                                    @click="refreshData"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    Refresh
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Device ID
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        API Key
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Temperature
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Humidity
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Timestamp
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="sensor in paginatedSensors" :key="sensor.id" class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ sensor.id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 bg-green-400 rounded-full mr-2"></div>
                                            {{ sensor.device_id }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                                {{ sensor.api_key.substring(0, 8) }}...
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-red-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            {{ sensor.temperature }}°C
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 text-blue-500 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                            </svg>
                                            {{ sensor.humidity }}%
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="[
                                            'inline-flex px-2 py-1 text-xs font-semibold rounded-full',
                                            sensor.status === 'online' 
                                                ? 'bg-green-100 text-green-800' 
                                                : 'bg-red-100 text-red-800'
                                        ]">
                                            {{ sensor.status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatTimestamp(sensor.timestamp) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button 
                                @click="currentPage--"
                                :disabled="currentPage === 1"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Previous
                            </button>
                            <button 
                                @click="currentPage++"
                                :disabled="currentPage >= totalPages"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing 
                                    <span class="font-medium">{{ (currentPage - 1) * itemsPerPage + 1 }}</span>
                                    to 
                                    <span class="font-medium">{{ Math.min(currentPage * itemsPerPage, filteredSensors.length) }}</span>
                                    of 
                                    <span class="font-medium">{{ filteredSensors.length }}</span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <button 
                                        @click="currentPage--"
                                        :disabled="currentPage === 1"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Previous
                                    </button>
                                    <button 
                                        @click="currentPage++"
                                        :disabled="currentPage >= totalPages"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                                    >
                                        Next
                                    </button>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    appName: {
        type: String,
        default: 'IoT Monitoring',
    },
});

// Reactive data
const sensors = ref([]);
const searchQuery = ref('');
const currentPage = ref(1);
const itemsPerPage = ref(10);

// Generate sample data
const generateSampleData = () => {
    const sampleData = [];
    const deviceIds = ['DEV001', 'DEV002', 'DEV003', 'DEV004', 'DEV005'];
    const apiKeys = ['ak_12345678', 'ak_87654321', 'ak_abcdefgh', 'ak_ijklmnop', 'ak_qrstuvwx'];
    
    for (let i = 1; i <= 50; i++) {
        const deviceId = deviceIds[Math.floor(Math.random() * deviceIds.length)];
        const apiKey = apiKeys[Math.floor(Math.random() * apiKeys.length)];
        const temperature = parseFloat((20 + Math.random() * 15).toFixed(1));
        const humidity = parseFloat((40 + Math.random() * 30).toFixed(1));
        const status = Math.random() > 0.1 ? 'online' : 'offline';
        const timestamp = new Date(Date.now() - Math.random() * 7 * 24 * 60 * 60 * 1000);
        
        sampleData.push({
            id: i,
            device_id: deviceId,
            api_key: apiKey,
            temperature: temperature,
            humidity: humidity,
            status: status,
            timestamp: timestamp
        });
    }
    
    return sampleData.sort((a, b) => b.timestamp - a.timestamp);
};

// Computed properties
const filteredSensors = computed(() => {
    let filtered = sensors.value;
    
    if (searchQuery.value) {
        filtered = filtered.filter(sensor => 
            sensor.device_id.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
            sensor.api_key.toLowerCase().includes(searchQuery.value.toLowerCase())
        );
    }
    
    return filtered;
});

const paginatedSensors = computed(() => {
    const start = (currentPage.value - 1) * itemsPerPage.value;
    const end = start + itemsPerPage.value;
    return filteredSensors.value.slice(start, end);
});

const totalPages = computed(() => {
    return Math.ceil(filteredSensors.value.length / itemsPerPage.value);
});

const activeDevices = computed(() => {
    return sensors.value.filter(sensor => sensor.status === 'online').length;
});

const averageTemperature = computed(() => {
    const onlineSensors = sensors.value.filter(sensor => sensor.status === 'online');
    if (onlineSensors.length === 0) return '0.0';
    const avg = onlineSensors.reduce((sum, sensor) => sum + sensor.temperature, 0) / onlineSensors.length;
    return avg.toFixed(1);
});

const averageHumidity = computed(() => {
    const onlineSensors = sensors.value.filter(sensor => sensor.status === 'online');
    if (onlineSensors.length === 0) return '0.0';
    const avg = onlineSensors.reduce((sum, sensor) => sum + sensor.humidity, 0) / onlineSensors.length;
    return avg.toFixed(1);
});

const lastUpdate = computed(() => {
    if (sensors.value.length === 0) return 'No data';
    const latest = sensors.value[0];
    return formatTimestamp(latest.timestamp);
});

// Methods
const formatTimestamp = (timestamp) => {
    return new Date(timestamp).toLocaleString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
        second: '2-digit'
    });
};

const refreshData = () => {
    sensors.value = generateSampleData();
    currentPage.value = 1;
};

// Lifecycle
onMounted(() => {
    sensors.value = generateSampleData();
    
    // Simulate real-time updates every 30 seconds
    setInterval(() => {
        const newSensor = {
            id: sensors.value.length + 1,
            device_id: `DEV00${Math.floor(Math.random() * 5) + 1}`,
            api_key: `ak_${Math.random().toString(36).substring(2, 10)}`,
            temperature: parseFloat((20 + Math.random() * 15).toFixed(1)),
            humidity: parseFloat((40 + Math.random() * 30).toFixed(1)),
            status: Math.random() > 0.1 ? 'online' : 'offline',
            timestamp: new Date()
        };
        
        sensors.value.unshift(newSensor);
        
        // Keep only last 100 records
        if (sensors.value.length > 100) {
            sensors.value = sensors.value.slice(0, 100);
        }
    }, 30000);
});

// Watch for search query changes to reset pagination
watch(searchQuery, () => {
    currentPage.value = 1;
});
</script> 