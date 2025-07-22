<template>
    <AuthenticatedLayout :app-name="appName">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            UAS IoT
                        </h1>
                        
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
                                <Link
                                    href="/sensor-monitoring"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Sensor Monitoring
                                </Link>
                                <Link
                                    href="/esp-setup"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    ESP Setup
                                </Link>
                                <Link
                                    href="/about"
                                    class="inline-flex items-center px-4 py-2 bg-purple-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700 focus:bg-purple-700 active:bg-purple-900 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    About Project
                                </Link>
                            </div>
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
    title: String,
    appName: {
        type: String,
        default: 'Laravel',
    },
});
</script> 