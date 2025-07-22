from django.db import models

# Create your models here.

class SensorData(models.Model):
    id = models.AutoField(primary_key=True)
    apikey = models.CharField(max_length=100, help_text="API key untuk autentikasi")
    deviceId = models.CharField(max_length=50, help_text="ID perangkat sensor")
    temperature = models.FloatField(help_text="Nilai suhu dalam Celsius")
    humidity = models.FloatField(help_text="Nilai kelembaban dalam persen")
    timestamp = models.DateTimeField(auto_now_add=True, help_text="Waktu data diterima")
    
    class Meta:
        db_table = 'sensor_data'
        ordering = ['-timestamp']
        verbose_name = 'Sensor Data'
        verbose_name_plural = 'Sensor Data'
    
    def __str__(self):
        return f"{self.deviceId} - {self.temperature}°C, {self.humidity}% - {self.timestamp}"
