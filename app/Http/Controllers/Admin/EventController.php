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
        $events = Event::with(['fees', 'categories', 'participants']) // Eager load relationships
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
            'dynamic_fields' => 'nullable|array',
            'dynamic_fields.*.label' => 'required_with:dynamic_fields|string|max:255',
            'dynamic_fields.*.type' => 'required_with:dynamic_fields|string|in:text,email,number,select,textarea,date',
            'dynamic_fields.*.info' => 'nullable|string|max:500',
            'dynamic_fields.*.options' => 'nullable|string',
            'dynamic_fields.*.required' => 'nullable|boolean',
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

        // Process dynamic fields configuration
        $dynamicFieldsConfig = [];
        if ($request->has('dynamic_fields')) {
            foreach ($request->input('dynamic_fields') as $field) {
                if (!empty($field['label']) && !empty($field['type'])) {
                    $configField = [
                        'label' => $field['label'],
                        'type' => $field['type'],
                        'required' => isset($field['required']) ? true : false,
                    ];

                    // Add info field if provided
                    if (!empty($field['info'])) {
                        $configField['info'] = $field['info'];
                    }

                    if ($field['type'] === 'select' && !empty($field['options'])) {
                        $configField['options'] = array_map('trim', explode(',', $field['options']));
                    }

                    $dynamicFieldsConfig[] = $configField;
                }
            }
        }

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
            'dynamic_fields_config' => $dynamicFieldsConfig,
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
        // Load relationships for better performance
        $event->load(['fees', 'categories', 'participants', 'transactions']);

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
            'dynamic_fields' => 'nullable|array',
            'dynamic_fields.*.label' => 'required_with:dynamic_fields|string|max:255',
            'dynamic_fields.*.type' => 'required_with:dynamic_fields|string|in:text,email,number,select,textarea,date',
            'dynamic_fields.*.info' => 'nullable|string|max:500',
            'dynamic_fields.*.options' => 'nullable|string',
            'dynamic_fields.*.required' => 'nullable|boolean',
        ]);

        // Generate a slug from the event name if name changed
        if ($event->name !== $request->input('name')) {
            $baseSlug = Str::slug($request->input('name'), '-');
            $slug = $baseSlug;
            $count = 1;
            while (Event::where('slug', $slug)->where('id', '!=', $event->id)->exists()) {
                $slug = $baseSlug . '-' . $count++;
            }
            $event->slug = $slug;
        }

        // Process description from summernote formatting
        $description = $request->input('description');
        if ($description) {
            // Allow Summernote HTML but sanitize
            $allowed_tags = '<p><br><b><i><u><strong><em><ul><ol><li><a><img><h1><h2><h3><h4><h5><h6><blockquote>';
            $description = strip_tags($description, $allowed_tags);

            // Process embedded images (base64) and save them as files
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
        }

        // Handle cover photo upload if provided
        if ($request->hasFile('cover_photo')) {
            // Delete old cover photo if exists
            if ($event->cover_photo && file_exists(public_path($event->cover_photo))) {
                unlink(public_path($event->cover_photo));
            }

            $file = $request->file('cover_photo');
            $filename = 'event_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('events', $filename, 'public');
            $event->cover_photo = 'storage/' . $path;
        }

        // Process dynamic fields configuration
        $dynamicFieldsConfig = [];
        if ($request->has('dynamic_fields')) {
            foreach ($request->input('dynamic_fields') as $field) {
                if (!empty($field['label']) && !empty($field['type'])) {
                    $configField = [
                        'label' => $field['label'],
                        'type' => $field['type'],
                        'required' => isset($field['required']) ? true : false,
                    ];

                    // Add info field if provided
                    if (!empty($field['info'])) {
                        $configField['info'] = $field['info'];
                    }

                    if ($field['type'] === 'select' && !empty($field['options'])) {
                        $configField['options'] = array_map('trim', explode(',', $field['options']));
                    }

                    $dynamicFieldsConfig[] = $configField;
                }
            }
        }

        // Update the event with the validated data
        $event->update([
            'name' => $request->input('name'),
            'description' => $description,
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
            'capacity' => $request->input('capacity'),
            'venue' => $request->input('venue'),
            'status' => $request->input('status'),
            'dynamic_fields_config' => $dynamicFieldsConfig,
        ]);

        // Save the updated event (this will save the cover_photo if it was updated)
        $event->save();

        // Redirect to events index with success message
        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Delete the specified event
        $event->delete();

        // Redirect to events index with success message
        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
    }
}
