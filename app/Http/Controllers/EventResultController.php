<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\EventResult;
use App\Models\Participant;
use App\Imports\EventResultsImport;

class EventResultController extends Controller
{
    public function index($eventSlug)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();

        // Get all results for this event ordered by position
        $results = EventResult::with(['participant'])
            ->where('event_id', $event->id)
            ->byPosition()
            ->get();

        // Get unique categories for filtering
        $categories = EventResult::where('event_id', $event->id)
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('events.results', compact('event', 'results', 'categories'));
    }

    public function byCategory($eventSlug, $category)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();

        // Get results for specific category
        $results = EventResult::with(['participant'])
            ->where('event_id', $event->id)
            ->byCategory($category)
            ->get();

        // Get all categories for navigation
        $categories = EventResult::where('event_id', $event->id)
            ->distinct()
            ->pluck('category')
            ->filter();

        return view('events.results', compact('event', 'results', 'categories', 'category'));
    }

    public function certificate($eventSlug, $participantId)
    {
        $event = Event::where('slug', $eventSlug)->firstOrFail();
        $result = EventResult::with(['participant', 'event'])
            ->where('event_id', $event->id)
            ->where('participant_id', $participantId)
            ->firstOrFail();

        return view('events.certificate', compact('event', 'result'));
    }

    // Admin methods
    public function adminIndex($eventId)
    {
        $event = Event::findOrFail($eventId);
        $results = EventResult::with(['participant'])
            ->where('event_id', $event->id)
            ->byPosition()
            ->paginate(50);

        return view('admin.events.results.index', compact('event', 'results'));
    }

    public function create($eventId)
    {
        $event = Event::findOrFail($eventId);
        $participants = Participant::where('event_id', $event->id)->get();

        return view('admin.events.results.create', compact('event', 'participants'));
    }

    public function store(Request $request, $eventId)
    {
        $request->validate([
            'participant_id' => 'required|exists:participants,id',
            'position' => 'required|integer|min:1',
            'bib_number' => 'nullable|string',
            'category' => 'nullable|string',
            'laps' => 'nullable|integer|min:0',
            'finish_time' => 'nullable|date_format:H:i:s',
            'gap' => 'nullable|string',
            'distance' => 'nullable|numeric|min:0',
            'chip_time' => 'nullable|date_format:H:i:s',
            'speed' => 'nullable|numeric|min:0',
            'best_lap' => 'nullable|date_format:H:i:s',
            'dnf' => 'boolean',
            'dsq' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $participant = Participant::findOrFail($request->participant_id);

        EventResult::create([
            'event_id' => $eventId,
            'participant_id' => $request->participant_id,
            'position' => $request->position,
            'bib_number' => $request->bib_number,
            'sex' => $participant->gender,
            'category' => $request->category,
            'category_position' => $this->calculateCategoryPosition($eventId, $request->category, $request->position),
            'laps' => $request->laps,
            'finish_time' => $request->finish_time,
            'gap' => $request->gap,
            'distance' => $request->distance,
            'chip_time' => $request->chip_time,
            'speed' => $request->speed,
            'best_lap' => $request->best_lap,
            'dnf' => $request->boolean('dnf'),
            'dsq' => $request->boolean('dsq'),
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.events.results.index', $eventId)
            ->with('success', 'Result added successfully!');
    }

    public function edit($eventId, $resultId)
    {
        $event = Event::findOrFail($eventId);
        $result = EventResult::findOrFail($resultId);
        $participants = Participant::where('event_id', $event->id)->get();

        return view('admin.events.results.edit', compact('event', 'result', 'participants'));
    }

    public function update(Request $request, $eventId, $resultId)
    {
        $request->validate([
            'position' => 'required|integer|min:1',
            'bib_number' => 'nullable|string',
            'category' => 'nullable|string',
            'laps' => 'nullable|integer|min:0',
            'finish_time' => 'nullable|date_format:H:i:s',
            'gap' => 'nullable|string',
            'distance' => 'nullable|numeric|min:0',
            'chip_time' => 'nullable|date_format:H:i:s',
            'speed' => 'nullable|numeric|min:0',
            'best_lap' => 'nullable|date_format:H:i:s',
            'dnf' => 'boolean',
            'dsq' => 'boolean',
            'notes' => 'nullable|string'
        ]);

        $result = EventResult::findOrFail($resultId);

        $result->update([
            'position' => $request->position,
            'bib_number' => $request->bib_number,
            'category' => $request->category,
            'category_position' => $this->calculateCategoryPosition($eventId, $request->category, $request->position),
            'laps' => $request->laps,
            'finish_time' => $request->finish_time,
            'gap' => $request->gap,
            'distance' => $request->distance,
            'chip_time' => $request->chip_time,
            'speed' => $request->speed,
            'best_lap' => $request->best_lap,
            'dnf' => $request->boolean('dnf'),
            'dsq' => $request->boolean('dsq'),
            'notes' => $request->notes
        ]);

        return redirect()->route('admin.events.results.index', $eventId)
            ->with('success', 'Result updated successfully!');
    }

    public function destroy($eventId, $resultId)
    {
        $result = EventResult::findOrFail($resultId);
        $result->delete();

        return redirect()->route('admin.events.results.index', $eventId)
            ->with('success', 'Result deleted successfully!');
    }

    public function showImport($eventId)
    {
        $event = Event::findOrFail($eventId);
        return view('admin.events.results.import', compact('event'));
    }

    public function import(Request $request, $eventId)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $event = Event::findOrFail($eventId);

        try {
            $filePath = $request->file('file')->getRealPath();
            $import = new EventResultsImport($eventId);
            $results = $import->import($filePath);

            $count = count($results);
            return redirect()->route('admin.events.results.index', $eventId)
                ->with('success', "Successfully imported {$count} results!");
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function downloadSample()
    {
        $filePath = public_path('sample_results.csv');

        if (file_exists($filePath)) {
            return response()->download($filePath, 'sample_results.csv');
        }

        return redirect()->back()->with('error', 'Sample file not found.');
    }

    private function calculateCategoryPosition($eventId, $category, $overallPosition)
    {
        if (!$category) return null;

        return EventResult::where('event_id', $eventId)
            ->where('category', $category)
            ->where('position', '<=', $overallPosition)
            ->count();
    }
}
