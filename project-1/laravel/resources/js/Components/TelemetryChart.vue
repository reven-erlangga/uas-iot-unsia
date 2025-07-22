<template>
    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-xl font-bold text-gray-900">Sensor Data Chart</h3>
            <div class="flex items-center space-x-4">
                <!-- Date Selector -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Date:</label>
                    <select
                        v-model="selectedDate"
                        @change="updateChart"
                        :disabled="isUpdating"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <option v-for="date in availableDates" :key="date" :value="date">
                            {{ formatDate(date) }}
                        </option>
                    </select>
                </div>
                
                <!-- Device Selector -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Device:</label>
                    <select
                        v-model="selectedDevice"
                        @change="updateChart"
                        :disabled="isUpdating"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <option value="">All Devices</option>
                        <option v-for="deviceName in devices" :key="deviceName" :value="deviceName">
                            {{ deviceName }}
                        </option>
                    </select>
                </div>
                
                <!-- Interval Selector -->
                <div class="flex items-center space-x-2">
                    <label class="text-sm font-medium text-gray-700">Interval:</label>
                    <select
                        v-model="selectedInterval"
                        @change="updateChart"
                        :disabled="isUpdating"
                        class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <option value="15">15 minutes</option>
                        <option value="30">30 minutes</option>
                        <option value="60">1 hour</option>
                    </select>
                </div>
            </div>
        </div>
        
        <!-- Chart Container -->
        <div class="relative h-96">
            <canvas ref="chartCanvas"></canvas>
            
            <!-- Loading overlay -->
            <div v-if="isUpdating" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
                <div class="flex items-center space-x-2">
                    <svg class="animate-spin h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    <span class="text-gray-600">Loading chart data...</span>
                </div>
            </div>
            
            <!-- No data message -->
            <div v-if="!isUpdating && (!labels || labels.length === 0)" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center">
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
</template>

<script setup>
import { ref, onMounted, onUnmounted, watch, nextTick, computed } from 'vue';
import { router } from '@inertiajs/vue3';
import { Chart, registerables } from 'chart.js';

// Register Chart.js components
Chart.register(...registerables);

const props = defineProps({
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
const chartCanvas = ref(null);
const selectedDate = ref(props.availableDates.length > 0 ? props.availableDates[0] : new Date().toISOString().split('T')[0]);
const selectedDevice = ref('');
const selectedInterval = ref(30);
const chart = ref(null);
const isUpdating = ref(false);
const updateTimeout = ref(null);

// Computed properties
const devices = computed(() => props.chartData.devices || {});
const labels = computed(() => props.chartData.labels || []);
const temperatureData = computed(() => props.chartData.temperatureData || []);
const humidityData = computed(() => props.chartData.humidityData || []);

// Methods
const createChart = () => {
    // Destroy existing chart if it exists
    if (chart.value) {
        try {
            chart.value.stop();
            chart.value.destroy();
        } catch (error) {
            console.warn('Error destroying chart:', error);
        }
        chart.value = null;
    }

    // Check if canvas exists
    if (!chartCanvas.value) {
        console.warn('Canvas element not found');
        return;
    }

    // Check if we have data
    if (!labels.value || labels.value.length === 0) {
        console.warn('No chart data available');
        return;
    }

    const ctx = chartCanvas.value.getContext('2d');
    
    chart.value = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels.value,
            datasets: [
                {
                    label: 'Temperature (°C)',
                    data: temperatureData.value,
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
                    data: humidityData.value,
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
};

const updateChart = () => {
    if (isUpdating.value) {
        return; // Prevent multiple simultaneous requests
    }
    
    // Clear any existing timeout
    if (updateTimeout.value) {
        clearTimeout(updateTimeout.value);
    }
    
    // Debounce the update
    updateTimeout.value = setTimeout(() => {
        isUpdating.value = true;
        
        // Destroy chart immediately to prevent animation conflicts
        if (chart.value) {
            try {
                chart.value.stop();
                chart.value.destroy();
            } catch (error) {
                console.warn('Error destroying chart:', error);
            }
            chart.value = null;
        }
        
        router.reload({
            data: {
                chart_date: selectedDate.value,
                chart_device_name: selectedDevice.value || '',
                chart_interval: selectedInterval.value
            },
            only: ['chartData'],
            onFinish: () => {
                // Add 2 second delay before allowing next update
                setTimeout(() => {
                    isUpdating.value = false;
                }, 2000);
            }
        });
    }, 500); // 500ms debounce
};

const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('en-US', {
        weekday: 'short',
        year: 'numeric',
        month: 'short',
        day: 'numeric'
    });
};

// Watch for chart data changes
watch(() => props.chartData, () => {
    nextTick(() => {
        createChart();
    });
}, { deep: true });

// Initialize chart on mount
onMounted(() => {
    nextTick(() => {
        createChart();
    });
});

// Cleanup chart on unmount
onUnmounted(() => {
    // Clear any pending timeout
    if (updateTimeout.value) {
        clearTimeout(updateTimeout.value);
    }
    
    // Destroy chart
    if (chart.value) {
        try {
            chart.value.stop();
            chart.value.destroy();
        } catch (error) {
            console.warn('Error destroying chart on unmount:', error);
        }
        chart.value = null;
    }
});
</script> 