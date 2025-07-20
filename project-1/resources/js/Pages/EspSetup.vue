<template>
    <AuthenticatedLayout :app-name="appName">
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-3xl font-bold text-gray-900 mb-4">
                            ESP32 Setup Guide
                        </h1>
                        <p class="text-gray-600 mb-6">
                            Choose your preferred protocol and copy the code for your ESP32 device.
                        </p>
                        
                        <!-- Tab Navigation -->
                        <div class="border-b border-gray-200 mb-6">
                            <nav class="-mb-px flex space-x-8">
                                <button
                                    @click="activeTab = 'https'"
                                    :class="[
                                        'py-2 px-1 border-b-2 font-medium text-sm',
                                        activeTab === 'https'
                                            ? 'border-blue-500 text-blue-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                    ]"
                                >
                                    HTTPS
                                </button>
                                <button
                                    @click="activeTab = 'http'"
                                    :class="[
                                        'py-2 px-1 border-b-2 font-medium text-sm',
                                        activeTab === 'http'
                                            ? 'border-blue-500 text-blue-600'
                                            : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'
                                    ]"
                                >
                                    HTTP
                                </button>
                            </nav>
                        </div>

                        <!-- Tab Content -->
                        <div v-if="activeTab === 'https'" class="space-y-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">HTTPS Configuration</h2>
                                <p class="text-gray-600 mb-4">
                                    Use HTTPS for secure communication with the server. This requires SSL certificate handling.
                                </p>
                                
                                <!-- Code Display Section -->
                                <div class="bg-gray-900 rounded-lg p-4 mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-300 text-sm font-medium">ESP32 Code (HTTPS)</span>
                                        <button
                                            @click="copyCode('https')"
                                            class="text-gray-400 hover:text-white transition-colors"
                                            :title="copiedTab === 'https' ? 'Copied!' : 'Copy code'"
                                        >
                                            <svg v-if="copiedTab !== 'https'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <svg v-else class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <pre class="text-green-400 text-sm overflow-x-auto"><code>#include &lt;WiFi.h&gt;
#include &lt;HTTPClient.h&gt;
#include &lt;ArduinoJson.h&gt;

const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";
const char* serverUrl = "https://your-domain.com/api/telemetry";

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");
    
    // Create JSON payload
    StaticJsonDocument&lt;200&gt; doc;
    doc["temperature"] = random(20, 35);
    doc["humidity"] = random(40, 80);
    doc["pressure"] = random(1000, 1020);
    
    String jsonString;
    serializeJson(doc, jsonString);
    
    int httpResponseCode = http.POST(jsonString);
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("HTTP Response code: " + String(httpResponseCode));
      Serial.println("Response: " + response);
    } else {
      Serial.println("Error on sending POST: " + http.errorToString(httpResponseCode));
    }
    
    http.end();
  }
  
  delay(5000); // Send data every 5 seconds
}</code></pre>
                                </div>
                            </div>
                        </div>

                        <div v-if="activeTab === 'http'" class="space-y-6">
                            <div>
                                <h2 class="text-xl font-semibold text-gray-900 mb-4">HTTP Configuration</h2>
                                <p class="text-gray-600 mb-4">
                                    Use HTTP for simple communication. Note that this is not encrypted.
                                </p>
                                
                                <!-- Code Display Section -->
                                <div class="bg-gray-900 rounded-lg p-4 mb-4">
                                    <div class="flex justify-between items-center mb-2">
                                        <span class="text-gray-300 text-sm font-medium">ESP32 Code (HTTP)</span>
                                        <button
                                            @click="copyCode('http')"
                                            class="text-gray-400 hover:text-white transition-colors"
                                            :title="copiedTab === 'http' ? 'Copied!' : 'Copy code'"
                                        >
                                            <svg v-if="copiedTab !== 'http'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                            </svg>
                                            <svg v-else class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <pre class="text-green-400 text-sm overflow-x-auto"><code>#include &lt;WiFi.h&gt;
#include &lt;HTTPClient.h&gt;
#include &lt;ArduinoJson.h&gt;

const char* ssid = "YOUR_WIFI_SSID";
const char* password = "YOUR_WIFI_PASSWORD";
const char* serverUrl = "http://your-domain.com/api/telemetry";

void setup() {
  Serial.begin(115200);
  WiFi.begin(ssid, password);
  
  while (WiFi.status() != WL_CONNECTED) {
    delay(1000);
    Serial.println("Connecting to WiFi...");
  }
  Serial.println("Connected to WiFi");
}

void loop() {
  if (WiFi.status() == WL_CONNECTED) {
    HTTPClient http;
    http.begin(serverUrl);
    http.addHeader("Content-Type", "application/json");
    
    // Create JSON payload
    StaticJsonDocument&lt;200&gt; doc;
    doc["temperature"] = random(20, 35);
    doc["humidity"] = random(40, 80);
    doc["pressure"] = random(1000, 1020);
    
    String jsonString;
    serializeJson(doc, jsonString);
    
    int httpResponseCode = http.POST(jsonString);
    
    if (httpResponseCode > 0) {
      String response = http.getString();
      Serial.println("HTTP Response code: " + String(httpResponseCode));
      Serial.println("Response: " + response);
    } else {
      Serial.println("Error on sending POST: " + http.errorToString(httpResponseCode));
    }
    
    http.end();
  }
  
  delay(5000); // Send data every 5 seconds
}</code></pre>
                                </div>
                            </div>
                        </div>

                        <!-- Instructions -->
                        <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                            <h3 class="text-lg font-semibold text-blue-900 mb-2">Setup Instructions</h3>
                            <ol class="list-decimal list-inside text-blue-800 space-y-1">
                                <li>Copy the code from your preferred tab above</li>
                                <li>Replace "YOUR_WIFI_SSID" and "YOUR_WIFI_PASSWORD" with your actual WiFi credentials</li>
                                <li>Update the "serverUrl" to match your server's domain</li>
                                <li>Upload the code to your ESP32 device</li>
                                <li>Open the Serial Monitor to see the connection status</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';
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

const activeTab = ref('https');
const copiedTab = ref(null);

const copyCode = async (tab) => {
    const codeElement = document.querySelector(`pre code`);
    if (codeElement) {
        try {
            await navigator.clipboard.writeText(codeElement.textContent);
            copiedTab.value = tab;
            setTimeout(() => {
                copiedTab.value = null;
            }, 2000);
        } catch (err) {
            console.error('Failed to copy code:', err);
        }
    }
};
</script> 