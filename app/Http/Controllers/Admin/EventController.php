<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Event;
use App\Models\EventFee;
use App\Models\EventCategory;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;



class EventController extends Controller
{
    // Require authentication and admin middleware
    public function __construct()
    {
        $this->middleware(['auth:admin']);
    }

    // Display a listing of the resource
    public function index()
    {
        $events = Event::with('fees') // Eager load fees relationship
            ->orderBy('start_time', 'desc') // Order by start time, descending
            ->get();
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
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'capacity' => 'nullable|string',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'venue' => 'nullable|string',
            'status' => 'string|in:scheduled,open,closed,complete',
        ]);

        // Generate a slug from the event name
        $baseSlug = Str::slug($request->input('name'), '-');
        $slug = $baseSlug;
        $count = 1;
        while (Event::where('slug', $slug)->exists()) {
            $slug = $baseSlug . '-' . $count++;
        }
        $request->merge(['slug' => $slug]);

        // Set default status if not provided
        if (!$request->has('status')) {
            $request->merge(['status' => 'scheduled']);
        }

        //validated description from summernote formatting
        // Allow Summernote HTML but sanitize, and handle embedded images if needed
        $description = $request->input('description');

        // Optionally, use a package like HTMLPurifier for better sanitization
        // For now, allow basic HTML tags, but strip script/style tags
        $allowed_tags = '<p><br><b><i><u><strong><em><ul><ol><li><a><img><h1><h2><h3><h4><h5><h6><blockquote>';
        $description = strip_tags($description, $allowed_tags);

        // Optionally, process embedded images (base64) and save them as files
        if (preg_match_all('/<img[^>]+src="data:image\/([^;]+);base64,([^"]+)"[^>]*>/i', $description, $matches, PREG_SET_ORDER)) {
            $uploadDir = public_path('uploads/events');
            if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
            }
            foreach ($matches as $img) {
            $imageData = base64_decode($img[2]);
            $extension = $img[1];
            $filename = 'uploads/events/' . uniqid() . '.' . $extension;
            file_put_contents(public_path($filename), $imageData);
            // Replace base64 src with file path
            $description = str_replace($img[0], '<img src="' . asset($filename) . '" />', $description);
            }
        }

        $request->merge(['description' => $description]);


        // Cover photo upload handling using Storage facade and renaming file
        if ($request->hasFile('cover_photo')) {
            $file = $request->file('cover_photo');
            $filename = 'event_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('events', $filename, 'public');
            $request->merge(['cover_photo' => 'storage/' . $path]);
        } else {
            $request->merge(['cover_photo' => null]);
        }


        // Create the event
        // Prepare the data for event creation, ensuring only fillable fields are used
        $eventData = [
            'name'        => $request->input('name'),
            'slug'        => $request->input('slug'),
            'description' => $request->input('description'),
            'start_time'  => $request->input('start_time'),
            'end_time'    => $request->input('end_time'),
            'capacity'    => $request->input('capacity'),
            'cover_photo' => $request->input('cover_photo'),
            'venue'       => $request->input('venue'),
            'status'      => $request->input('status'),
        ];

        // Create the event using the prepared data
        $event = Event::create($eventData);

        // Optionally, you can perform additional logic here, such as logging or dispatching events


        // Redirect to events index with success message
        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    // Other methods (show, edit, update, destroy) can be added here as needed

    public function storeFees(Request $request)
    {
        // Validate the request data for fees
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'fee_amount' => 'required|numeric|min:0',
            'fee_type' => 'required',
        ]);

        // Logic to store fees for the event
        // This would typically involve creating a new EventFee model instance
        $eventFee = EventFee::create([
            'event_id' => $request->input('event_id'),
            'fee_amount' => $request->input('fee_amount'),
            'fee_type' => $request->input('fee_type'),
            'is_active' => true, // Assuming fees are active by default
        ]);

        if ($eventFee) {
            return redirect()->route('admin.events.index')->with('success', 'Fees stored successfully.');
        } else {
            return redirect()->back()->with('error', 'Failed to store fees. Please try again.');
        }
    }

    public function storeCategories(Request $request)
    {
       //store
        $request->validate([
            'event_id' => 'required|exists:events,id',
            'category_name' => 'required|string|max:255',
        ]);

        // Create a new category for the event
        $category = new EventCategory();
        $category->event_id = $request->input('event_id');
        $category->name = $request->input('category_name');
        $category->save();

        // Redirect back with success message
        return redirect()->route('admin.events.index')->with('success', 'Category created successfully.');
    }


    public function show(Event $event)
    {
        // Show the details of a specific event
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        // Show the form for editing the specified event
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_time' => 'required|date',
            'end_time' => 'nullable|date|after_or_equal:start_time',
            'capacity' => 'nullable|string',
            'cover_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'venue' => 'nullable|string',
            'status' => 'string|in:scheduled,open,closed,complete',
        ]);

        // Update the event with the validated data
        $event->update($request->all());

        // Handle cover photo upload if provided
        if ($request->hasFile('cover_photo')) {
            $file = $request->file('cover_photo');
            $filename = 'event_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('events', $filename, 'public');
            $event->cover_photo = 'storage/' . $path;
        }

        // Save the updated event
        $event->save();

        // Redirect to events index with success message
        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy($event)
    {
        // Delete the specified event
        $event = Event::findOrFail($event);
        $event->delete();

        // Redirect to events index with success message
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
