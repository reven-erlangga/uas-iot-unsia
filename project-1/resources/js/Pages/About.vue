<template>
    <AuthenticatedLayout :app-name="appName">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            Tentang Sistem Monitoring IoT
                        </h1>
                        <p class="text-gray-600 mb-6">
                            Selamat datang di platform monitoring IoT yang dirancang untuk memantau dan mengelola data sensor secara real-time.
                        </p>
                        
                        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                            <h2 class="text-lg font-semibold text-blue-900 mb-2">Apa itu Sistem Monitoring IoT?</h2>
                            <p class="text-blue-800">
                                Sistem ini adalah platform web yang terintegrasi dengan perangkat IoT (Internet of Things) untuk memantau, 
                                mengumpulkan, dan menganalisis data sensor secara real-time. Platform ini memungkinkan Anda untuk 
                                mengelola dan memvisualisasikan data dari berbagai sensor yang terhubung.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-green-900 mb-2">Fungsi Utama Website:</h3>
                                <ul class="list-disc list-inside text-green-800 space-y-1">
                                    <li>Monitoring data sensor real-time</li>
                                    <li>Visualisasi data dalam grafik</li>
                                    <li>Manajemen perangkat IoT</li>
                                    <li>Riwayat data historis</li>
                                    <li>Notifikasi dan alert</li>
                                    <li>Dashboard analitik</li>
                                </ul>
                            </div>
                            
                            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-orange-900 mb-2">Integrasi IoT:</h3>
                                <ul class="list-disc list-inside text-orange-800 space-y-1">
                                    <li>Koneksi ESP32/ESP8266</li>
                                    <li>Sensor suhu dan kelembaban</li>
                                    <li>Kontrol perangkat jarak jauh</li>
                                    <li>Data logging otomatis</li>
                                </ul>
                            </div>
                        </div>

                        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Teknologi yang Digunakan:</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="text-center">
                                    <div class="bg-red-100 rounded-lg p-3 mb-2">
                                        <span class="text-red-800 font-semibold">Backend</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Laravel PHP, MySQL, API REST</p>
                                </div>
                                <div class="text-center">
                                    <div class="bg-blue-100 rounded-lg p-3 mb-2">
                                        <span class="text-blue-800 font-semibold">Frontend</span>
                                    </div>
                                    <p class="text-sm text-gray-600">Vue.js 3, Inertia.js, Tailwind CSS</p>
                                </div>
                                <div class="text-center">
                                    <div class="bg-green-100 rounded-lg p-3 mb-2">
                                        <span class="text-green-800 font-semibold">IoT</span>
                                    </div>
                                    <p class="text-sm text-gray-600">ESP32, Sensor DHT22</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex space-x-4">
                            <Link
                                href="/"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Kembali ke Beranda
                            </Link>
                            <Link
                                href="/sensor-monitoring"
                                class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Lihat Monitoring
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    appName: {
        type: String,
        default: 'Laravel',
    },
});
</script> 