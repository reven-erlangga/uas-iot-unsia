from django.shortcuts import render
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_http_methods
import json
from .models import SensorData
from django.db.models import Q
from django.core.serializers.json import DjangoJSONEncoder

# Create your views here.

def home(request):
    # Get statistics from real data
    total_devices = SensorData.objects.values('deviceId').distinct().count()
    total_sensors = SensorData.objects.count()
    latest_data = SensorData.objects.order_by('-timestamp').first()
    
    context = {
        'total_devices': total_devices,
        'total_sensors': total_sensors,
        'last_update': latest_data.timestamp if latest_data else None
    }
    return render(request, 'home.html', context)

def sensor_data(request):
    # Get real sensor data from database
    device_id = request.GET.get('deviceId', '')
    
    if device_id:
        sensor_data_list = SensorData.objects.filter(deviceId=device_id).order_by('-timestamp')
    else:
        sensor_data_list = SensorData.objects.order_by('-timestamp')
    
    # Get unique device IDs for filter dropdown
    device_ids = SensorData.objects.values_list('deviceId', flat=True).distinct()
    
    # Convert to list for template
    sensor_data_for_template = []
    for data in sensor_data_list:
        # Determine status based on values
        if data.temperature > 40 or data.humidity < 20:
            status = 'Alert'
        elif data.temperature > 35 or data.humidity > 80:
            status = 'Warning'
        else:
            status = 'Active'
            
        sensor_data_for_template.append({
            'deviceId': data.deviceId,
            'sensorType': 'Temperature',
            'value': float(data.temperature),
            'unit': '°C',
            'timestamp': data.timestamp.strftime('%Y-%m-%d %H:%M:%S'),
            'status': status
        })
        
        sensor_data_for_template.append({
            'deviceId': data.deviceId,
            'sensorType': 'Humidity',
            'value': float(data.humidity),
            'unit': '%',
            'timestamp': data.timestamp.strftime('%Y-%m-%d %H:%M:%S'),
            'status': status
        })
    
    # Convert to JSON string for template
    sensor_data_json = json.dumps(sensor_data_for_template, cls=DjangoJSONEncoder)
    
    context = {
        'sensor_data': sensor_data_json,
        'device_ids': device_ids,
        'selected_device': device_id
    }
    return render(request, 'sensor_data.html', context)

def sensor_gauge(request):
    # Get real device data from database
    device_id = request.GET.get('deviceId', '')
    
    if device_id:
        latest_data = SensorData.objects.filter(deviceId=device_id).order_by('-timestamp').first()
    else:
        latest_data = SensorData.objects.order_by('-timestamp').first()
    
    # Get all device IDs for dropdown
    device_ids = SensorData.objects.values_list('deviceId', flat=True).distinct()
    
    # Get latest data for each device
    device_data = {}
    for device in device_ids:
        latest = SensorData.objects.filter(deviceId=device).order_by('-timestamp').first()
        if latest:
            device_data[device] = {
                'temperature': float(latest.temperature),
                'humidity': float(latest.humidity),
                'timestamp': latest.timestamp.isoformat()
            }
    
    # Convert to JSON string for template
    device_data_json = json.dumps(device_data, cls=DjangoJSONEncoder)
    
    context = {
        'device_data': device_data_json,
        'device_ids': device_ids,
        'selected_device': device_id,
        'latest_data': latest_data
    }
    return render(request, 'sensor_gauge.html', context)

@csrf_exempt
@require_http_methods(["POST"])
def api_store_sensor_data(request):
    """
    API endpoint untuk menyimpan data sensor dari Postman
    Expected JSON format:
    {
        "apikey": "your_api_key",
        "deviceId": "device-001",
        "deviceName": "Sensor Device 1",
        "temperature": 25.5,
        "humidity": 65.2
    }
    """
    try:
        data = json.loads(request.body)
        
        # Validasi required fields
        required_fields = ['apikey', 'deviceId', 'deviceName', 'temperature', 'humidity']
        for field in required_fields:
            if field not in data:
                return JsonResponse({
                    'success': False,
                    'error': f'Missing required field: {field}'
                }, status=400)
        
        # Validasi tipe data
        if not isinstance(data['temperature'], (int, float)):
            return JsonResponse({
                'success': False,
                'error': 'Temperature must be a number'
            }, status=400)
            
        if not isinstance(data['humidity'], (int, float)):
            return JsonResponse({
                'success': False,
                'error': 'Humidity must be a number'
            }, status=400)
        
        # Simpan data ke database
        sensor_data = SensorData.objects.create(
            apikey=data['apikey'],
            deviceId=data['deviceId'],
            deviceName=data['deviceName'],
            temperature=data['temperature'],
            humidity=data['humidity']
        )
        
        return JsonResponse({
            'success': True,
            'message': 'Data saved successfully',
            'data': {
                'id': sensor_data.id,
                'deviceId': sensor_data.deviceId,
                'deviceName': sensor_data.deviceName,
                'temperature': sensor_data.temperature,
                'humidity': sensor_data.humidity,
                'timestamp': sensor_data.timestamp.isoformat()
            }
        }, status=201)
        
    except json.JSONDecodeError:
        return JsonResponse({
            'success': False,
            'error': 'Invalid JSON format'
        }, status=400)
    except Exception as e:
        return JsonResponse({
            'success': False,
            'error': str(e)
        }, status=500)

@require_http_methods(["GET"])
def api_get_sensor_data(request):
    """
    API endpoint untuk mengambil data sensor
    Query parameters:
    - deviceId: filter by device ID
    - limit: limit number of records (default: 100)
    """
    try:
        device_id = request.GET.get('deviceId')
        limit = int(request.GET.get('limit', 100))
        
        queryset = SensorData.objects.all()
        
        if device_id:
            queryset = queryset.filter(deviceId=device_id)
        
        # Ambil data terbaru
        sensor_data = queryset.order_by('-timestamp')[:limit]
        
        data_list = []
        for data in sensor_data:
            data_list.append({
                'id': data.id,
                'apikey': data.apikey,
                'deviceId': data.deviceId,
                'deviceName': data.deviceName,
                'temperature': data.temperature,
                'humidity': data.humidity,
                'timestamp': data.timestamp.isoformat()
            })
        
        return JsonResponse({
            'success': True,
            'count': len(data_list),
            'data': data_list
        })
        
    except ValueError:
        return JsonResponse({
            'success': False,
            'error': 'Invalid limit parameter'
        }, status=400)
    except Exception as e:
        return JsonResponse({
            'success': False,
            'error': str(e)
        }, status=500)