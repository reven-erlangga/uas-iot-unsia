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
                                <p class="text-2xl font-bold text-gray-900">{{ monitoringTelemetry.deviceCount }}</p>
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
                                <p class="text-2xl font-bold text-gray-900">{{ monitoringTelemetry.avgTemperature }}°C</p>
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
                                <p class="text-2xl font-bold text-gray-900">{{ monitoringTelemetry.avgHumidity }}%</p>
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
                                <p class="text-lg font-bold text-gray-900">{{ monitoringTelemetry.lastUpdate }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart Section -->
                <div class="mb-8">
                    <TelemetryChart :chart-data="chartData" :available-dates="availableDates" />
                </div>

                <!-- Data Table Section -->
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200">
                        <div class="flex items-center justify-between">
                            <h2 class="text-2xl font-bold text-gray-900">Sensor Data Table</h2>
                            <div class="flex items-center space-x-4">
                                <!-- Column Selector -->
                                <div class="relative">
                                    <button
                                        @click="showColumnSelector = !showColumnSelector"
                                        class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                    >
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                                        </svg>
                                        Columns
                                    </button>
                                    
                                    <!-- Column Selector Dropdown -->
                                    <div v-if="showColumnSelector" class="absolute right-0 mt-2 w-56 bg-white rounded-md shadow-lg z-50 border border-gray-200">
                                        <div class="py-2">
                                            <div class="px-4 py-2 border-b border-gray-200">
                                                <h3 class="text-sm font-medium text-gray-900">Select Columns</h3>
                                            </div>
                                            <div class="max-h-64 overflow-y-auto">
                                                <label v-for="column in availableColumns" :key="column.key" class="flex items-center px-4 py-2 hover:bg-gray-50 cursor-pointer">
                                                    <input
                                                        type="checkbox"
                                                        v-model="visibleColumns"
                                                        :value="column.key"
                                                        class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                                    >
                                                    <span class="ml-3 text-sm text-gray-700">{{ column.label }}</span>
                                                </label>
                                            </div>
                                            <div class="px-4 py-2 border-t border-gray-200 flex justify-between">
                                                <button
                                                    @click="selectAllColumns"
                                                    class="text-sm text-blue-600 hover:text-blue-800"
                                                >
                                                    Select All
                                                </button>
                                                <button
                                                    @click="deselectAllColumns"
                                                    class="text-sm text-red-600 hover:text-red-800"
                                                >
                                                    Deselect All
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

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
                                    @click="refreshAllData"
                                    :disabled="isRefreshing"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50 disabled:cursor-not-allowed"
                                >
                                    <svg v-if="isRefreshing" class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    <svg v-else class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                    </svg>
                                    {{ isRefreshing ? 'Refreshing...' : 'Refresh All' }}
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th v-if="isColumnVisible('id')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        ID
                                    </th>
                                    <th v-if="isColumnVisible('deviceId')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Device ID
                                    </th>
                                    <th v-if="isColumnVisible('deviceName')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Device Name
                                    </th>
                                    <th v-if="isColumnVisible('apiKey')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        API Key
                                    </th>
                                    <th v-if="isColumnVisible('temperature')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Temperature
                                    </th>
                                    <th v-if="isColumnVisible('humidity')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Humidity
                                    </th>

                                    <th v-if="isColumnVisible('timestamp')" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Timestamp
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="telemetry in telemetryData" :key="telemetry.id" class="hover:bg-gray-50">
                                    <td v-if="isColumnVisible('id')" class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        {{ telemetry.id }}
                                    </td>
                                    <td v-if="isColumnVisible('deviceId')" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <div :class="[
                                                'w-2 h-2 rounded-full mr-2',
                                                telemetry.status === 'online' ? 'bg-green-400' : 'bg-red-400'
                                            ]"></div>
                                            {{ telemetry.deviceId }}
                                        </div>
                                    </td>
                                     <td v-if="isColumnVisible('deviceName')" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            {{ telemetry.deviceName }}
                                        </div>
                                    </td>
                                    <td v-if="isColumnVisible('apiKey')" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center">
                                            <span class="font-mono text-xs bg-gray-100 px-2 py-1 rounded">
                                                {{ telemetry.apiKey ? telemetry.apiKey.substring(0, 8) + '...' : 'N/A' }}
                                            </span>
                                        </div>
                                    </td>
                                    <td v-if="isColumnVisible('temperature')" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                                </svg>
                                                <span class="font-medium">{{ telemetry.temperature || 'N/A' }}°C</span>
                                            </div>
                                            <!-- Temperature Trend Icon -->
                                            <div class="flex items-center ml-4">
                                                <div v-if="telemetry.temperatureTrend === 'up'" class="flex items-center text-green-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                    </svg>
                                                </div>
                                                <div v-else-if="telemetry.temperatureTrend === 'down'" class="flex items-center text-red-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                    </svg>
                                                </div>
                                                <div v-else-if="telemetry.temperatureTrend === 'stable'" class="flex items-center text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td v-if="isColumnVisible('humidity')" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z"></path>
                                                </svg>
                                                <span class="font-medium">{{ telemetry.humidity || 'N/A' }}%</span>
                                            </div>
                                            <!-- Humidity Trend Icon -->
                                            <div class="flex items-center ml-4">
                                                <div v-if="telemetry.humidityTrend === 'up'" class="flex items-center text-green-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                    </svg>
                                                </div>
                                                <div v-else-if="telemetry.humidityTrend === 'down'" class="flex items-center text-red-600">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                    </svg>
                                                </div>
                                                <div v-else-if="telemetry.humidityTrend === 'stable'" class="flex items-center text-gray-500">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td v-if="isColumnVisible('timestamp')" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ formatTimestamp(telemetry.createdAt || telemetry.timestamp) }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <button 
                                @click="previousPage"
                                :disabled="telemetries.currentPage === 1"
                                class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Previous
                            </button>
                            <button 
                                @click="nextPage"
                                :disabled="telemetries.currentPage >= telemetries.lastPage"
                                class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
                            >
                                Next
                            </button>
                        </div>
                        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                            <div>
                                <p class="text-sm text-gray-700">
                                    Showing 
                                    <span class="font-medium">{{ telemetries.from || 0 }}</span>
                                    to 
                                    <span class="font-medium">{{ telemetries.to || 0 }}</span>
                                    of 
                                    <span class="font-medium">{{ telemetries.total || 0 }}</span>
                                    results
                                </p>
                            </div>
                            <div class="flex items-center space-x-2">
                                <select 
                                    v-model="itemsPerPage"
                                    class="border border-gray-300 rounded-md text-sm px-2 py-1"
                                >
                                    <option value="5">5 per page</option>
                                    <option value="10">10 per page</option>
                                    <option value="15">15 per page</option>
                                    <option value="25">25 per page</option>
                                    <option value="50">50 per page</option>
                                </select>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                    <button 
                                        @click="previousPage"
                                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                                    >
                                        Previous
                                    </button>
                                    
                                    <!-- Page numbers -->
                                    <template v-for="page in getPageNumbers()" :key="page">
                                        <button 
                                            v-if="page !== '...'"
                                            @click="goToPage(page)"
                                            :class="[
                                                'relative inline-flex items-center px-4 py-2 border text-sm font-medium',
                                                page === telemetries.currentPage
                                                    ? 'z-10 bg-blue-50 border-blue-500 text-blue-600'
                                                    : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50'
                                            ]"
                                        >
                                            {{ page }}
                                        </button>
                                        <span 
                                            v-else
                                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700"
                                        >
                                            ...
                                        </span>
                                    </template>
                                    
                                    <button 
                                        @click="nextPage"
                                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
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

<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TelemetryChart from '@/Components/TelemetryChart.vue';

const props = defineProps({
    appName: {
        type: String,
        default: 'IoT Monitoring',
    },
    telemetries: {
        type: Object,
        default: () => ({ data: [] }),
    },
    monitoringTelemetry: {
        type: Object,
        default: () => ({})
    },
    chartData: {
        type: Object,
        default: () => ({
            labels: [],
            temperatureData: [],
            humidityData: [],
            devices: {}
        })
    },
    availableDates: {
        type: Array,
        default: () => []
    }
});

// Reactive data
const searchQuery = ref('');
const itemsPerPage = ref(props.telemetries.perPage || 10);
const showColumnSelector = ref(false);
const isRefreshing = ref(false);

// Column visibility state
const visibleColumns = ref(['id', 'deviceId', 'deviceName', 'temperature', 'humidity', 'timestamp']);

// Available columns configuration
const availableColumns = ref([
    { key: 'id', label: 'ID' },
    { key: 'deviceId', label: 'Device ID' },
    { key: 'deviceName', label: 'Device Name' },
    { key: 'apiKey', label: 'API Key' },
    { key: 'temperature', label: 'Temperature' },
    { key: 'humidity', label: 'Humidity' },
    { key: 'timestamp', label: 'Timestamp' }
]);

// Computed properties for telemetry data
const telemetryData = computed(() => {
    return props.telemetries.data || [];
});

const activeDevices = computed(() => {
    return telemetryData.value.filter(telemetry => telemetry.status === 'online').length;
});

const averageTemperature = computed(() => {
    const onlineTelemetries = telemetryData.value.filter(telemetry => telemetry.status === 'online');
    if (onlineTelemetries.length === 0) return '0.0';
    const avg = onlineTelemetries.reduce((sum, telemetry) => sum + parseFloat(telemetry.temperature || 0), 0) / onlineTelemetries.length;
    return avg.toFixed(1);
});

const averageHumidity = computed(() => {
    const onlineTelemetries = telemetryData.value.filter(telemetry => telemetry.status === 'online');
    if (onlineTelemetries.length === 0) return '0.0';
    const avg = onlineTelemetries.reduce((sum, telemetry) => sum + parseFloat(telemetry.humidity || 0), 0) / onlineTelemetries.length;
    return avg.toFixed(1);
});

const lastUpdate = computed(() => {
    if (telemetryData.value.length === 0) return 'No data';
    const latest = telemetryData.value[0];
    return formatTimestamp(latest.createdAt || latest.timestamp);
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

const isColumnVisible = (columnKey) => {
    return visibleColumns.value.includes(columnKey);
};

const selectAllColumns = () => {
    visibleColumns.value = availableColumns.value.map(col => col.key);
};

const deselectAllColumns = () => {
    visibleColumns.value = [];
};

const refreshAllData = () => {
    if (isRefreshing.value) {
        return; // Prevent multiple clicks
    }
    
    isRefreshing.value = true;
    router.reload({
        onFinish: () => {
            // Add 2 second delay before allowing next refresh
            setTimeout(() => {
                isRefreshing.value = false;
            }, 2000);
        }
    });
};

const goToPage = (page) => {
    console.log('goToPage called with:', page);
    router.reload({
        data: {
            page: page,
            per_page: itemsPerPage.value,
            search: searchQuery.value
        },
        only: ['telemetries']
    });
};

const previousPage = () => {
    console.log('previousPage called');
    const newPage = props.telemetries.currentPage - 1;
    if (newPage >= 1) {
        goToPage(newPage);
    }
};

const nextPage = () => {
    console.log('nextPage called');
    const newPage = props.telemetries.currentPage + 1;
    if (newPage <= props.telemetries.lastPage) {
        goToPage(newPage);
    }
};

const performSearch = () => {
    router.reload({
        data: {
            page: 1,
            per_page: itemsPerPage.value,
            search: searchQuery.value
        },
        only: ['telemetries']
    });
};

const getPageNumbers = () => {
    const pages = [];
    const current = props.telemetries.currentPage;
    const last = props.telemetries.lastPage;
    
    if (last <= 7) {
        // Show all pages if total pages <= 7
        for (let i = 1; i <= last; i++) {
            pages.push(i);
        }
    } else {
        // Show first page
        pages.push(1);
        
        if (current > 4) {
            pages.push('...');
        }
        
        // Show pages around current page
        const start = Math.max(2, current - 1);
        const end = Math.min(last - 1, current + 1);
        
        for (let i = start; i <= end; i++) {
            pages.push(i);
        }
        
        if (current < last - 3) {
            pages.push('...');
        }
        
        // Show last page
        if (last > 1) {
            pages.push(last);
        }
    }
    
    return pages;
};

// Watch for search query changes
watch(searchQuery, () => {
    // Debounce search
    clearTimeout(window.searchTimeout);
    window.searchTimeout = setTimeout(() => {
        performSearch();
    }, 500);
});

// Watch for items per page changes
watch(itemsPerPage, () => {
    goToPage(1);
});

// Close column selector when clicking outside
onMounted(() => {
    document.addEventListener('click', (event) => {
        const target = event.target as HTMLElement;
        if (!target.closest('.relative')) {
            showColumnSelector.value = false;
        }
    });
});
</script>