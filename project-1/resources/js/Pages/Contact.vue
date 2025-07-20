<template>
    <AuthenticatedLayout :app-name="appName">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            Contact Us
                        </h1>
                        <p class="text-gray-600 mb-6">
                            Send us a message using the form below.
                        </p>
                        
                        <form @submit.prevent="submit" class="max-w-lg">
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Name
                                </label>
                                <input
                                    id="name"
                                    v-model="form.name"
                                    type="text"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': errors.name }"
                                />
                                <p v-if="errors.name" class="mt-1 text-sm text-red-600">
                                    {{ errors.name }}
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    Email
                                </label>
                                <input
                                    id="email"
                                    v-model="form.email"
                                    type="email"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': errors.email }"
                                />
                                <p v-if="errors.email" class="mt-1 text-sm text-red-600">
                                    {{ errors.email }}
                                </p>
                            </div>
                            
                            <div class="mb-4">
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                                    Message
                                </label>
                                <textarea
                                    id="message"
                                    v-model="form.message"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                                    :class="{ 'border-red-500': errors.message }"
                                ></textarea>
                                <p v-if="errors.message" class="mt-1 text-sm text-red-600">
                                    {{ errors.message }}
                                </p>
                            </div>
                            
                            <div class="flex space-x-4">
                                <button
                                    type="submit"
                                    :disabled="processing"
                                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-50"
                                >
                                    {{ processing ? 'Sending...' : 'Send Message' }}
                                </button>
                                <Link
                                    href="/"
                                    class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150"
                                >
                                    Cancel
                                </Link>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

defineProps({
    appName: {
        type: String,
        default: 'Laravel',
    },
    errors: {
        type: Object,
        default: () => ({}),
    },
});

const form = useForm({
    name: '',
    email: '',
    message: '',
});

const submit = () => {
    form.post('/contact', {
        onSuccess: () => {
            form.reset();
        },
    });
};
</script> 