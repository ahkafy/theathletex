<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;

class HomeController extends Controller
{

    public function index()
    {
        $events = Event::with('fees')->get();
        return view('index', compact('events'));
    }
}
