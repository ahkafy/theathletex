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
