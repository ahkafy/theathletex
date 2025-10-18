<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use Carbon\Carbon;

class HomeController extends Controller
{

    public function index()
    {
        $events = Event::with('fees')->where('status', 'active')->get();
        return view('index', compact('events'));
    }

    public function allEvents()
    {
        $events = Event::with('fees')->get();
        return view('events.all', compact('events'));
    }

    public function upcomingEvents()
    {
        $events = Event::with('fees')
            ->where('start_time', '>', now())
            ->orderBy('start_time', 'asc')
            ->get();
        return view('events.upcoming', compact('events'));
    }

    public function pastEvents()
    {
        $events = Event::with('fees')
            ->where('end_time', '<', now())
            ->orderBy('end_time', 'desc')
            ->get();
        return view('events.past', compact('events'));
    }

    public function about()
    {
        return view('pages.about');
    }

    public function privacy()
    {
        return view('pages.privacy');
    }

    public function delivery()
    {
        return view('pages.delivery');
    }

    public function return()
    {
        return view('pages.return');
    }
}
