<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    // Require authentication and admin middleware
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // Display a listing of the resource
    public function index()
    {
        $events = Event::all();
        return view('admin.events.index', compact('events'));
    }

    // Show the form for creating a new event
    public function create()
    {
        return view('admin.events.create');
    }

    // Store a newly created event in storage
    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:events,slug',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'capacity' => 'nullable|string',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'venue' => 'nullable|string',
            'is_open' => 'boolean',
        ]);

        //cover photo upload handling
        if ($request->hasFile('cover_photo')) {
            $file = $request->file('cover_photo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/events'), $filename);
            $request->merge(['cover_photo' => 'uploads/events/' . $filename]);
        } else {
            $request->merge(['cover_photo' => null]);
        }

        // Create the event
        Event::create($request->all());

        // Redirect to events index with success message
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    // Other methods (show, edit, update, destroy) can be added here as needed
}
