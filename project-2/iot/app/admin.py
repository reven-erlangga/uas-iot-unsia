from django.contrib import admin
from .models import SensorData

@admin.register(SensorData)
class SensorDataAdmin(admin.ModelAdmin):
    list_display = ('deviceName', 'deviceId', 'temperature', 'humidity', 'timestamp')
    list_filter = ('deviceId', 'deviceName', 'timestamp')
    search_fields = ('deviceId', 'deviceName', 'apikey')
    readonly_fields = ('timestamp',)
    ordering = ('-timestamp',)
    
    fieldsets = (
        ('Device Information', {
            'fields': ('apikey', 'deviceId', 'deviceName')
        }),
        ('Sensor Readings', {
            'fields': ('temperature', 'humidity')
        }),
        ('Timestamp', {
            'fields': ('timestamp',),
            'classes': ('collapse',)
        }),
    )
