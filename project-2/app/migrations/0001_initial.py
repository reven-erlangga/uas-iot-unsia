# Generated by Django 3.2.25 on 2025-07-20 18:25

from django.db import migrations, models


class Migration(migrations.Migration):

    initial = True

    dependencies = [
    ]

    operations = [
        migrations.CreateModel(
            name='SensorData',
            fields=[
                ('id', models.AutoField(primary_key=True, serialize=False)),
                ('apikey', models.CharField(help_text='API key untuk autentikasi', max_length=100)),
                ('deviceId', models.CharField(help_text='ID perangkat sensor', max_length=50)),
                ('deviceName', models.CharField(help_text='Nama perangkat sensor', max_length=100)),
                ('temperature', models.FloatField(help_text='Nilai suhu dalam Celsius')),
                ('humidity', models.FloatField(help_text='Nilai kelembaban dalam persen')),
                ('timestamp', models.DateTimeField(auto_now_add=True, help_text='Waktu data diterima')),
            ],
            options={
                'verbose_name': 'Sensor Data',
                'verbose_name_plural': 'Sensor Data',
                'db_table': 'sensor_data',
                'ordering': ['-timestamp'],
            },
        ),
    ]
