<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="py-12">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="p-8">
                <h1 class="text-4xl font-bold text-gray-900 mb-6">UAS IoT</h1>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-blue-900 mb-2">Student Information:</h2>
                        <ul class="list-none text-blue-800 space-y-1">
                            <li><strong>Nama:</strong> Reven Ferlian Erlangga</li>
                            <li><strong>NIM:</strong> 240401020001</li>
                            <li><strong>Kuliah:</strong> Unsia</li>
                            <li><strong>Kelas:</strong> IF601</li>
                        </ul>
                    </div>
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-green-900 mb-2">Lecturers:</h2>
                        <ul class="list-none text-green-800 space-y-1">
                            <li><strong>Dosen:</strong> Syahid Abdullah, S.Si., M.Kom.</li>
                            <li><strong>Profesor:</strong> Prof. Jong-Dae Park</li>
                        </ul>
                    </div>
                </div>
                <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Project Navigation:</h3>
                    <p class="text-gray-600 mb-4">
                        Welcome to the IoT project dashboard. You can navigate to different sections of the application below.
                    </p>
                    <div class="flex space-x-4">
                        <a href="<?php echo base_url('sensormonitoring'); ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded transition duration-300">Sensor Monitoring</a>
                        <a href="<?php echo base_url('espsetup'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-300">ESP Setup</a>
                        <a href="<?php echo base_url('home/about'); ?>" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded transition duration-300">About Project</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div> 