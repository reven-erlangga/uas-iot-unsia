from django.contrib import admin
from .models import SensorData

@admin.register(SensorData)
class SensorDataAdmin(admin.ModelAdmin):
    list_display = ('deviceId', 'temperature', 'humidity', 'timestamp')
    list_filter = ('deviceId', 'timestamp')
    search_fields = ('deviceId', 'apikey')
    readonly_fields = ('timestamp',)
    ordering = ('-timestamp',)
    
    fieldsets = (
        ('Device Information', {
            'fields': ('apikey', 'deviceId')
        }),
        ('Sensor Readings', {
            'fields': ('temperature', 'humidity')
        }),
        ('Timestamp', {
            'fields': ('timestamp',),
            'classes': ('collapse',)
        }),
    )
