<template>
    <AuthenticatedLayout :app-name="appName">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            About This Project
                        </h1>
                        <p class="text-gray-600 mb-6">
                            This is a demonstration of Inertia.js integration with Laravel and Vue 3.
                        </p>
                        
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                            <h2 class="text-lg font-semibold text-yellow-900 mb-2">What is Inertia.js?</h2>
                            <p class="text-yellow-800">
                                Inertia.js is a library that allows you to create single-page applications using classic server-side routing and controllers, 
                                without the complexity of building an API. It provides the modern development experience of SPAs with the simplicity and power of traditional server-side applications.
                            </p>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-purple-900 mb-2">Server-Side Benefits:</h3>
                                <ul class="list-disc list-inside text-purple-800 space-y-1">
                                    <li>Laravel's powerful ORM and Eloquent</li>
                                    <li>Built-in authentication and authorization</li>
                                    <li>Form validation and error handling</li>
                                    <li>Database migrations and seeders</li>
                                </ul>
                            </div>
                            
                            <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                <h3 class="text-lg font-semibold text-indigo-900 mb-2">Client-Side Benefits:</h3>
                                <ul class="list-disc list-inside text-indigo-800 space-y-1">
                                    <li>Vue 3's Composition API</li>
                                    <li>Reactive data and components</li>
                                    <li>Modern JavaScript features</li>
                                    <li>Hot module replacement</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="mt-6 flex space-x-4">
                            <Link
                                href="/"
                                class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150"
                            >
                                Back to Home
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