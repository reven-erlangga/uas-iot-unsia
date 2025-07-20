<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index()
    {
        return Inertia::render('Contact', [
            'appName' => config('app.name'),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:1000',
        ]);

        // Here you would typically save to database or send email
        // For demo purposes, we'll just redirect back with success
        
        return redirect()->back()->with('success', 'Thank you for your message! We will get back to you soon.');
    }
} 