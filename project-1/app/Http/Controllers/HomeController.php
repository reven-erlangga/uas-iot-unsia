<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class HomeController extends Controller
{
    public function index()
    {
        return Inertia::render('Welcome', [
            'title' => 'Welcome to Inertia.js',
            'appName' => config('app.name'),
        ]);
    }
} 