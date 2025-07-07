<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Admin;
use App\Models\Participant; // Assuming you have a Participants model


class DashboardController extends Controller
{

    public function index()
    {
        return view('admin.dashboard');
    }

    public function participants()
    {
        // Fetch participants data from the database
        $participants = Participant::all(); // Adjust this to your actual model and query

        return view('admin.reports.participants', compact('participants'));
    }
}
