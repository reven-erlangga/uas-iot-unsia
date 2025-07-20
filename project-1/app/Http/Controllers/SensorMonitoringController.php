<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class SensorMonitoringController extends Controller
{
    public function index()
    {
        return Inertia::render('SensorMonitoring', [
            'appName' => 'IoT Sensor Monitoring',
        ]);
    }
} 