<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="py-12">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-6">Tentang Sistem Monitoring IoT</h1>
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
                            <p class="text-sm text-gray-600">CodeIgniter SQLite, API REST</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-blue-100 rounded-lg p-3 mb-2">
                                <span class="text-blue-800 font-semibold">Frontend</span>
                            </div>
                            <p class="text-sm text-gray-600">Tailwind CSS, HTML5, CSS3</p>
                        </div>
                        <div class="text-center">
                            <div class="bg-green-100 rounded-lg p-3 mb-2">
                                <span class="text-green-800 font-semibold">IoT</span>
                            </div>
                            <p class="text-sm text-gray-600">ESP32, Sensor DHT22</p>
                        </div>
                    </div>
                </div>
                <div class="flex space-x-4">
                    <a href="<?php echo base_url('home'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">Kembali ke Beranda</a>
                    <a href="<?php echo base_url('sensormonitoring'); ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">Lihat Monitoring</a>
                </div>
            </div>
        </div>
    </div>
</div> 