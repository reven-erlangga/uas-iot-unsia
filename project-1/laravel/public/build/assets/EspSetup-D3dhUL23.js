import{r as d,c as m,o as n,w as h,a as e,e as r,f as p,n as u}from"./app-C8G5Ai_V.js";import{_ as v}from"./AuthenticatedLayout-KZzF2LHa.js";/* empty css            */const g={class:"py-12"},y={class:"max-w-7xl mx-auto sm:px-6 lg:px-8"},S={class:"bg-white overflow-hidden shadow-sm sm:rounded-lg"},x={class:"p-6 text-gray-900"},b={class:"border-b border-gray-200 mb-6"},C={class:"-mb-px flex space-x-8"},T={key:0,class:"space-y-6"},f={class:"bg-gray-900 rounded-lg p-4 mb-4"},w={class:"flex justify-between items-center mb-2"},k=["title"],P={key:0,class:"w-5 h-5",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},W={key:1,class:"w-5 h-5 text-green-400",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},_={key:1,class:"space-y-6"},R={class:"bg-gray-900 rounded-lg p-4 mb-4"},O={class:"flex justify-between items-center mb-2"},F=["title"],E={key:0,class:"w-5 h-5",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},H={key:1,class:"w-5 h-5 text-green-400",fill:"none",stroke:"currentColor",viewBox:"0 0 24 24"},U={__name:"EspSetup",props:{appName:{type:String,default:"Laravel"},errors:{type:Object,default:()=>({})}},setup(c){const o=d("https"),s=d(null),l=async a=>{const t=document.querySelector("pre code");if(t)try{await navigator.clipboard.writeText(t.textContent),s.value=a,setTimeout(()=>{s.value=null},2e3)}catch(i){console.error("Failed to copy code:",i)}};return(a,t)=>(n(),m(v,{"app-name":c.appName},{default:h(()=>[e("div",g,[e("div",y,[e("div",S,[e("div",x,[t[16]||(t[16]=e("h1",{class:"text-3xl font-bold text-gray-900 mb-4"}," ESP32 Setup Guide ",-1)),t[17]||(t[17]=e("p",{class:"text-gray-600 mb-6"}," Choose your preferred protocol and copy the code for your ESP32 device. ",-1)),e("div",b,[e("nav",C,[e("button",{onClick:t[0]||(t[0]=i=>o.value="https"),class:u(["py-2 px-1 border-b-2 font-medium text-sm",o.value==="https"?"border-blue-500 text-blue-600":"border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"])}," HTTPS ",2),e("button",{onClick:t[1]||(t[1]=i=>o.value="http"),class:u(["py-2 px-1 border-b-2 font-medium text-sm",o.value==="http"?"border-blue-500 text-blue-600":"border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300"])}," HTTP ",2)])]),o.value==="https"?(n(),r("div",T,[e("div",null,[t[8]||(t[8]=e("h2",{class:"text-xl font-semibold text-gray-900 mb-4"},"HTTPS Configuration",-1)),t[9]||(t[9]=e("p",{class:"text-gray-600 mb-4"}," Use HTTPS for secure communication with the server. This requires SSL certificate handling. ",-1)),e("div",f,[e("div",w,[t[6]||(t[6]=e("span",{class:"text-gray-300 text-sm font-medium"},"ESP32 Code (HTTPS)",-1)),e("button",{onClick:t[2]||(t[2]=i=>l("https")),class:"text-gray-400 hover:text-white transition-colors",title:s.value==="https"?"Copied!":"Copy code"},[s.value!=="https"?(n(),r("svg",P,t[4]||(t[4]=[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"},null,-1)]))):(n(),r("svg",W,t[5]||(t[5]=[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M5 13l4 4L19 7"},null,-1)])))],8,k)]),t[7]||(t[7]=e("pre",{class:"text-green-400 text-sm overflow-x-auto"},[e("code",null,`#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

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
}`)],-1))])])])):p("",!0),o.value==="http"?(n(),r("div",_,[e("div",null,[t[14]||(t[14]=e("h2",{class:"text-xl font-semibold text-gray-900 mb-4"},"HTTP Configuration",-1)),t[15]||(t[15]=e("p",{class:"text-gray-600 mb-4"}," Use HTTP for simple communication. Note that this is not encrypted. ",-1)),e("div",R,[e("div",O,[t[12]||(t[12]=e("span",{class:"text-gray-300 text-sm font-medium"},"ESP32 Code (HTTP)",-1)),e("button",{onClick:t[3]||(t[3]=i=>l("http")),class:"text-gray-400 hover:text-white transition-colors",title:s.value==="http"?"Copied!":"Copy code"},[s.value!=="http"?(n(),r("svg",E,t[10]||(t[10]=[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"},null,-1)]))):(n(),r("svg",H,t[11]||(t[11]=[e("path",{"stroke-linecap":"round","stroke-linejoin":"round","stroke-width":"2",d:"M5 13l4 4L19 7"},null,-1)])))],8,F)]),t[13]||(t[13]=e("pre",{class:"text-green-400 text-sm overflow-x-auto"},[e("code",null,`#include <WiFi.h>
#include <HTTPClient.h>
#include <ArduinoJson.h>

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
}`)],-1))])])])):p("",!0),t[18]||(t[18]=e("div",{class:"mt-8 p-4 bg-blue-50 rounded-lg"},[e("h3",{class:"text-lg font-semibold text-blue-900 mb-2"},"Setup Instructions"),e("ol",{class:"list-decimal list-inside text-blue-800 space-y-1"},[e("li",null,"Copy the code from your preferred tab above"),e("li",null,'Replace "YOUR_WIFI_SSID" and "YOUR_WIFI_PASSWORD" with your actual WiFi credentials'),e("li",null,`Update the "serverUrl" to match your server's domain`),e("li",null,"Upload the code to your ESP32 device"),e("li",null,"Open the Serial Monitor to see the connection status")])],-1))])])])])]),_:1},8,["app-name"]))}};export{U as default};
