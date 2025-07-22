<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ESP32 Setup Guide</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        pre code { color: #4ade80; background: none; }
        .tab-btn.active { border-bottom: 2px solid #0d6efd; color: #0d6efd; }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h1 class="display-6 fw-bold mb-4 text-primary">ESP32 Setup Guide</h1>
                        <p class="text-muted mb-4">
                            Choose your preferred protocol and copy the code for your ESP32 device.
                        </p>
                        <!-- Tab Navigation -->
                        <ul class="nav nav-tabs mb-4" id="espTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active tab-btn" id="https-tab" data-bs-toggle="tab" data-bs-target="#https" type="button" role="tab">HTTPS</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link tab-btn" id="http-tab" data-bs-toggle="tab" data-bs-target="#http" type="button" role="tab">HTTP</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="espTabContent">
                            <!-- HTTPS Tab -->
                            <div class="tab-pane fade show active" id="https" role="tabpanel">
                                <h2 class="h5 fw-semibold text-primary mb-3">HTTPS Configuration</h2>
                                <p class="text-muted mb-3">Use HTTPS for secure communication with the server. This requires SSL certificate handling.</p>
                                <div class="bg-dark rounded-3 p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-light small fw-medium">ESP32 Code (HTTPS)</span>
                                        <button class="btn btn-sm btn-outline-light" onclick="copyCode('https')" id="copy-https-btn" title="Copy code">
                                            <span id="copy-https-icon">📋</span> <span id="copy-https-label" class="d-none">Copied!</span>
                                        </button>
                                    </div>
                                    <pre class="mb-0"><code id="https-code">
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
                            <!-- HTTP Tab -->
                            <div class="tab-pane fade" id="http" role="tabpanel">
                                <h2 class="h5 fw-semibold text-primary mb-3">HTTP Configuration</h2>
                                <p class="text-muted mb-3">Use HTTP for simple communication. Note that this is not encrypted.</p>
                                <div class="bg-dark rounded-3 p-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <span class="text-light small fw-medium">ESP32 Code (HTTP)</span>
                                        <button class="btn btn-sm btn-outline-light" onclick="copyCode('http')" id="copy-http-btn" title="Copy code">
                                            <span id="copy-http-icon">📋</span> <span id="copy-http-label" class="d-none">Copied!</span>
                                        </button>
                                    </div>
<pre class="mb-0"><code id="http-code">
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
}
</code></pre>
                                </div>
                            </div>
                        </div>
                        <!-- Instructions -->
                        <div class="mt-4 p-3 bg-primary bg-opacity-10 rounded-3">
                            <h3 class="h6 fw-bold text-primary mb-2">Setup Instructions</h3>
                            <ol class="mb-0 text-primary">
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
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    function copyCode(tab) {
        var codeId = tab === 'https' ? 'https-code' : 'http-code';
        var code = document.getElementById(codeId).innerText;
        navigator.clipboard.writeText(code).then(function() {
            var btn = document.getElementById('copy-' + tab + '-btn');
            var icon = document.getElementById('copy-' + tab + '-icon');
            var label = document.getElementById('copy-' + tab + '-label');
            icon.style.display = 'none';
            label.classList.remove('d-none');
            setTimeout(function() {
                icon.style.display = '';
                label.classList.add('d-none');
            }, 1500);
        });
    }
    </script>
</body>
</html> 