const mqtt = require('mqtt');

// Connect to MQTT broker
const client = mqtt.connect('mqtt://localhost:1883');

client.on('connect', () => {
  console.log('✅ Connected to MQTT broker');
  
  // Publish message
  const topic = 'test/topic';
  const message = 'Hello from Node.js MQTT client!';
  
  client.publish(topic, message, (error) => {
    if (error) {
      console.error('❌ Failed to publish:', error);
    } else {
      console.log(`✅ Message published to ${topic}: ${message}`);
    }
    
    // Disconnect after publishing
    client.end();
  });
});

client.on('error', (error) => {
  console.error('❌ MQTT connection error:', error);
}); 