from django.urls import path
from . import views

urlpatterns = [
    path('', views.home, name='home'),
    path('sensor-data/', views.sensor_data, name='sensor_data'),
    path('sensor-gauge/', views.sensor_gauge, name='sensor_gauge'),
    
    # API endpoints
    path('api/store-sensor-data/', views.api_store_sensor_data, name='api_store_sensor_data'),
    path('api/get-sensor-data/', views.api_get_sensor_data, name='api_get_sensor_data'),
]
