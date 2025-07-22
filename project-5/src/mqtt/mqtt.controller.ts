import { Controller, Post, Get, Body, Param, Query } from '@nestjs/common';
import { MqttService } from './mqtt.service';

@Controller('mqtt')
export class MqttController {
  constructor(private readonly mqttService: MqttService) {}

  @Post('publish')
  async publishMessage(
    @Body() body: { topic: string; message: string },
  ): Promise<{ success: boolean; message: string }> {
    try {
      await this.mqttService.publish(body.topic, body.message);
      return {
        success: true,
        message: `Message published to topic: ${body.topic}`,
      };
    } catch (error) {
      return {
        success: false,
        message: `Failed to publish message: ${error.message}`,
      };
    }
  }

  @Post('subscribe/:topic')
  async subscribeToTopic(
    @Param('topic') topic: string,
  ): Promise<{ success: boolean; message: string }> {
    try {
      await this.mqttService.subscribe(topic);
      return {
        success: true,
        message: `Subscribed to topic: ${topic}`,
      };
    } catch (error) {
      return {
        success: false,
        message: `Failed to subscribe to topic: ${error.message}`,
      };
    }
  }

  @Post('subscribe')
  async subscribeToTopicQuery(
    @Query('topic') topic: string,
  ): Promise<{ success: boolean; message: string }> {
    try {
      await this.mqttService.subscribe(topic);
      return {
        success: true,
        message: `Subscribed to topic: ${topic}`,
      };
    } catch (error) {
      return {
        success: false,
        message: `Failed to subscribe to topic: ${error.message}`,
      };
    }
  }

  @Get('status')
  getConnectionStatus(): { connected: boolean; message: string } {
    const isConnected = this.mqttService.isConnected();
    return {
      connected: isConnected,
      message: isConnected ? 'Connected to MQTT broker' : 'Disconnected from MQTT broker',
    };
  }
}
