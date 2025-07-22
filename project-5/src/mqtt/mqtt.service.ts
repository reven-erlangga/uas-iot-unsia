import { Injectable, OnModuleInit, OnModuleDestroy } from '@nestjs/common';
import * as mqtt from 'mqtt';

@Injectable()
export class MqttService implements OnModuleInit, OnModuleDestroy {
  private client: mqtt.MqttClient;
  private readonly brokerUrl = process.env.MQTT_BROKER_URL || 'mqtt://localhost:1883';
  private messageHandler: ((topic: string, message: Buffer) => void) | null = null;

  async onModuleInit() {
    await this.connect();
  }

  async onModuleDestroy() {
    await this.disconnect();
  }

  private async connect(): Promise<void> {
    return new Promise((resolve, reject) => {
      try {
        this.client = mqtt.connect(this.brokerUrl, {
          clientId: `nestjs-client-${Math.random().toString(16).slice(3)}`,
          clean: true,
          connectTimeout: 4000,
          reconnectPeriod: 1000,
        });

        this.client.on('connect', () => {
          console.log('Connected to MQTT broker');
          resolve();
        });

        this.client.on('error', (error) => {
          console.error('MQTT connection error:', error);
          reject(error);
        });

        this.client.on('reconnect', () => {
          console.log('Reconnecting to MQTT broker...');
        });

        this.client.on('close', () => {
          console.log('MQTT connection closed');
        });

        this.client.on('message', (topic, message) => {
          console.log(`Received message on topic ${topic}: ${message.toString()}`);
          if (this.messageHandler) {
            this.messageHandler(topic, message);
          }
        });

      } catch (error) {
        console.error('Failed to connect to MQTT broker:', error);
        reject(error);
      }
    });
  }

  private async disconnect(): Promise<void> {
    if (this.client) {
      this.client.end();
    }
  }

  async publish(topic: string, message: string): Promise<void> {
    if (!this.client || !this.client.connected) {
      throw new Error('MQTT client not connected');
    }

    return new Promise((resolve, reject) => {
      this.client.publish(topic, message, (error) => {
        if (error) {
          reject(error);
        } else {
          console.log(`Message published to ${topic}: ${message}`);
          resolve();
        }
      });
    });
  }

  async subscribe(topic: string): Promise<void> {
    if (!this.client || !this.client.connected) {
      throw new Error('MQTT client not connected');
    }

    return new Promise((resolve, reject) => {
      this.client.subscribe(topic, (error) => {
        if (error) {
          reject(error);
        } else {
          console.log(`Subscribed to topic: ${topic}`);
          resolve();
        }
      });
    });
  }

  isConnected(): boolean {
    return this.client && this.client.connected;
  }

  onMessage(handler: (topic: string, message: Buffer) => void): void {
    this.messageHandler = handler;
  }
}
