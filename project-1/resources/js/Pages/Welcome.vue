<template>
    <AuthenticatedLayout :app-name="appName">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            Welcome to Inertia.js + Vue 3 + Laravel!
                        </h1>
                        <p class="text-gray-600 mb-6">
                            This is a sample page demonstrating Inertia.js integration with Vue 3 and Laravel.
                        </p>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                <h2 class="text-lg font-semibold text-blue-900 mb-2">Features:</h2>
                                <ul class="list-disc list-inside text-blue-800 space-y-1">
                                    <li>Server-side rendering with Laravel</li>
                                    <li>Client-side navigation with Vue 3</li>
                                    <li>No API endpoints needed</li>
                                    <li>Full-stack development experience</li>
                                </ul>
                            </div>
                            
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h2 class="text-lg font-semibold text-green-900 mb-2">Benefits:</h2>
                                <ul class="list-disc list-inside text-green-800 space-y-1">
                                    <li>Single-page app feel</li>
                                    <li>Traditional server-side routing</li>
                                    <li>Shared data between server and client</li>
                                    <li>Built-in CSRF protection</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="mt-6 p-4 bg-gray-50 rounded-lg">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Getting Started:</h3>
                            <p class="text-gray-600 mb-4">
                                You can now create new Vue components in the <code class="bg-gray-200 px-1 rounded">resources/js/Pages</code> directory 
                                and render them from your Laravel controllers using <code class="bg-gray-200 px-1 rounded">Inertia::render()</code>.
                            </p>
                            <div class="flex space-x-4">
                                <Link
                                    href="/sensor-monitoring"
                                    class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Sensor Monitoring
                                </Link>
                                <Link
                                    href="/about"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Learn More
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