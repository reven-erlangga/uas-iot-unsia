<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
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
                            id="https-tab"
                            class="py-2 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600 tab-button"
                            data-tab="https"
                        >
                            HTTPS
                        </button>
                        <button
                            id="http-tab"
                            class="py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 tab-button"
                            data-tab="http"
                        >
                            HTTP
                        </button>
                    </nav>
                </div>

                <!-- Tab Content -->
                <div id="https-content" class="tab-content space-y-6">
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
                                    onclick="copyCode('https')"
                                    class="text-gray-400 hover:text-white transition-colors"
                                    title="Copy code"
                                    id="copy-https-btn"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                            <pre class="text-green-400 text-sm overflow-x-auto"><code id="https-code">
#include &lt;DHT.h&gt;
#include &lt;ESP8266WiFi.h&gt;
#include &lt;ESP8266HTTPClient.h&gt;

#define DHTPIN 0         // Pin data sensor DHT11
#define DHTTYPE DHT11    // Tipe sensor DHT11
#define STUDENTID "STUDENT_ID"

const char* ssid       = "YOUR_WIFI_SSID";
const char* password   = "YOUR_WIFI_PASSWORD";
const char* serverUrl  = "https://your-domain.com/api/telemetry/receive";
const char* station_id = STUDENTID;
const char* device_id  = "wemos mini";

DHT dht(DHTPIN, DHTTYPE);
WiFiClientSecure client;

void setup() {
    Serial.begin(115200);
    Serial.println("Started");

    WiFi.begin(ssid, password);
    dht.begin();

    Serial.print("Connecting to WiFi");
    while (WiFi.status() != WL_CONNECTED) {
        delay(500);
        Serial.print(".");
    }

    IPAddress ip = WiFi.localIP();
    Serial.print("IP address: ");
    Serial.println(ip);
    Serial.println("\nWiFi connected");

    client.setInsecure();
}

void loop() {
    float humidity = dht.readHumidity();
    float temperature = dht.readTemperature();

    if (isnan(humidity) || isnan(temperature)) {
        Serial.println("Failed to read from DHT sensor!");
        return;
    }

    if (WiFi.status() == WL_CONNECTED) {
        HTTPClient http;
        http.begin(client, serverUrl);
        http.addHeader("Content-Type", "application/json");

        // Metadata sebagai string JSON di dalam string
        String metadata = "{\"sensor\":\"DHT11\", \"location\":\"lab\"}";

        // Buat JSON string yang valid
        String postData = String("{\"station_id\":\"") + station_id +
                          String("\",\"temperature\":") + String(temperature, 1) +
                          String(",\"humidity\":") + String(humidity) +
                          String(",\"device_id\":\"") + device_id +
                          String("\",\"metadata\":") + String(metadata) +
                          String("}");

        int httpResponseCode = http.POST(postData);

        Serial.print("POST Data: ");
        Serial.println(postData);
        Serial.print("Response code: ");
        Serial.println(httpResponseCode);

        http.end();
    } else {
        Serial.println("WiFi Disconnected");
    }

    delay(5000); // Send data every 5 seconds
}
</code></pre>
                        </div>
                    </div>
                </div>

                <div id="http-content" class="tab-content space-y-6 hidden">
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
                                    onclick="copyCode('http')"
                                    class="text-gray-400 hover:text-white transition-colors"
                                    title="Copy code"
                                    id="copy-http-btn"
                                >
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                </button>
                            </div>
                            <pre class="text-green-400 text-sm overflow-x-auto"><code id="http-code">
#include &lt;WiFi.h&gt;
#include &lt;HTTPClient.h&gt;
#include &lt;ArduinoJson.h&gt;

const char* ssid       = "YOUR_WIFI_SSID";
const char* password   = "YOUR_WIFI_PASSWORD";
const char* serverUrl  = "http://your-domain.com/api/telemetry/receive";

DHT dht(DHTPIN, DHTTYPE);
WiFiClient client;

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
        http.begin(client, serverUrl);
        http.addHeader("Content-Type", "application/json");

        // Create JSON payload
        StaticJsonDocument<200> doc;
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

                <!-- Quick Actions -->
                <div class="mt-8 flex justify-center space-x-4">
                    <a href="<?php echo base_url('espsetup/config'); ?>" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        Device Configuration
                    </a>
                    <a href="<?php echo base_url('espsetup/status'); ?>" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition duration-300">
                        Device Status
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Tab functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', function() {
            const tabName = this.getAttribute('data-tab');
            
            // Update tab button styles
            tabButtons.forEach(btn => {
                btn.classList.remove('border-blue-500', 'text-blue-600');
                btn.classList.add('border-transparent', 'text-gray-500');
            });
            this.classList.remove('border-transparent', 'text-gray-500');
            this.classList.add('border-blue-500', 'text-blue-600');
            
            // Show/hide tab content
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            document.getElementById(tabName + '-content').classList.remove('hidden');
        });
    });
});

// Copy code functionality
function copyCode(tab) {
    const codeElement = document.getElementById(tab + '-code');
    const copyButton = document.getElementById('copy-' + tab + '-btn');
    
    if (codeElement) {
        const textArea = document.createElement('textarea');
        textArea.value = codeElement.textContent;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        
        // Show success feedback
        const originalHTML = copyButton.innerHTML;
        copyButton.innerHTML = `
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
        `;
        copyButton.title = 'Copied!';
        
        setTimeout(() => {
            copyButton.innerHTML = originalHTML;
            copyButton.title = 'Copy code';
        }, 2000);
    }
}
</script> 