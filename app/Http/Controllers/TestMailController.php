<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestMailController extends Controller
{
    public function showForm()
    {
        return view('testmail');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $to = $request->input('email');

        try {
            Mail::raw('This is a test email from The Athlete X.', function($message) use ($to) {
                $message->to($to)
                        ->subject('Test Email from The Athlete X');
            });

            return back()->with('success', 'Test email sent to ' . $to);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to send email: ' . $e->getMessage());
        }
    }
}
