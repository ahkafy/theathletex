<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    // Require authentication and admin middleware
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    // Display a listing of the resource
    public function index()
    {
        // Logic to retrieve transactions
        return view('admin.transactions.index');
    }

    // Show the form for creating a new transaction
    public function create()
    {
        return view('admin.transactions.create');
    }

    // Store a newly created transaction in storage
    public function store(Request $request)
    {
        // Validate and store the transaction
        return redirect()->route('admin.transactions.index')->with('success', 'Transaction created successfully.');
    }
}
