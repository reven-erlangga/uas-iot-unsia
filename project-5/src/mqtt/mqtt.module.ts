import { Module, OnModuleInit } from '@nestjs/common';
import { MqttService } from './mqtt.service';
import { MqttController } from './mqtt.controller';

@Module({
  controllers: [MqttController],
  providers: [MqttService],
  exports: [MqttService],
})
export class MqttModule implements OnModuleInit {
  constructor(private readonly mqttService: MqttService) {}

  async onModuleInit() {
    // Wait a bit for connection to establish, then subscribe to default topic
    setTimeout(async () => {
      try {
        await this.mqttService.subscribe('test/topic');
        // Publish a test message
        await this.mqttService.publish('test/topic', 'Hello from NestJS!');
      } catch (error) {
        console.error('Failed to subscribe or publish:', error);
      }
    }, 2000);
  }
}
