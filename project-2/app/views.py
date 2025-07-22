from django.shortcuts import render
from django.http import JsonResponse
from django.views.decorators.csrf import csrf_exempt
from django.views.decorators.http import require_http_methods
import json
from .models import SensorData
from django.db.models import Q
from django.core.serializers.json import DjangoJSONEncoder
import pytz
from datetime import timedelta

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
    device_ids = list(set(SensorData.objects.values_list('deviceId', flat=True)))
    device_ids.sort()
    
    # Convert to list for template
    sensor_data_for_template = []
    wib = pytz.timezone('Asia/Jakarta')
    for data in sensor_data_list:
        ts = data.timestamp
        if ts.tzinfo is None:
            ts = ts.replace(tzinfo=pytz.UTC)
        ts_wib = ts.astimezone(wib)
        sensor_data_for_template.append({
            'deviceId': data.deviceId,
            'temperature': float(data.temperature),
            'humidity': float(data.humidity),
            'timestamp': ts_wib.strftime('%Y-%m-%d %H:%M:%S'),
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
    device_ids = list(set(SensorData.objects.values_list('deviceId', flat=True)))
    device_ids.sort()
    device_id = request.GET.get('deviceId')
    if not device_id and device_ids:
        device_id = device_ids[0]
    latest_data = SensorData.objects.filter(deviceId=device_id).order_by('-timestamp').first() if device_id else None
    device_data = {}
    wib = pytz.timezone('Asia/Jakarta')
    for device in device_ids:
        latest = SensorData.objects.filter(
            deviceId=device,
            temperature__isnull=False,
            humidity__isnull=False
        ).order_by('-timestamp').first()
        if latest:
            ts = latest.timestamp
            if ts.tzinfo is None:
                ts = ts.replace(tzinfo=pytz.UTC)
            ts_wib = ts.astimezone(wib)
            device_data[device] = {
                'temperature': float(latest.temperature),
                'humidity': float(latest.humidity),
                'timestamp': ts_wib.strftime('%Y-%m-%d %H:%M:%S')
            }
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
        required_fields = ['apikey', 'deviceId', 'temperature', 'humidity']
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
            temperature=data['temperature'],
            humidity=data['humidity']
        )
        
        return JsonResponse({
            'success': True,
            'message': 'Data saved successfully',
            'data': {
                'id': sensor_data.id,
                'deviceId': sensor_data.deviceId,
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