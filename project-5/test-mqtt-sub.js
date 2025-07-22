const mqtt = require('mqtt');

// Connect to MQTT broker
const client = mqtt.connect('mqtt://localhost:1883');

client.on('connect', () => {
  console.log('✅ Connected to MQTT broker');
  
  // Subscribe to topic
  const topic = 'test/topic';
  client.subscribe(topic, (error) => {
    if (error) {
      console.error('❌ Failed to subscribe:', error);
    } else {
      console.log(`✅ Subscribed to topic: ${topic}`);
      console.log('👂 Listening for messages... (Press Ctrl+C to exit)');
    }
  });
});

// Listen for messages
client.on('message', (topic, message) => {
  console.log(`📨 Received message on topic ${topic}: ${message.toString()}`);
});

client.on('error', (error) => {
  console.error('❌ MQTT connection error:', error);
});

// Keep the connection alive
console.log('MQTT Subscriber started...'); 