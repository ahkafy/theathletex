<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display the user's profile.
     */
    public function index()
    {
        $user = Auth::user();
        $participants = $user->participants()->with('event')->latest()->get();

        return view('profile.index', compact('user', 'participants'));
    }

    /**
     * Show the form for editing the profile.
     */
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:100'],
            'thana' => ['nullable', 'string', 'max:100'],
            'emergency_phone' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:male,female,other'],
            'dob' => ['nullable', 'date', 'before:today'],
            'nationality' => ['nullable', 'string', 'max:100'],
            'tshirt_size' => ['nullable', 'string', 'max:10'],
            'sports_interests' => ['nullable', 'array'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'profile_photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        // Handle profile photo upload
        if ($request->hasFile('profile_photo')) {
            // Delete old photo if exists
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }

            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $validated['profile_photo'] = $path;
        }

        // If email changed, reset email verification
        if ($user->email !== $validated['email']) {
            $validated['email_verified_at'] = null;
        }

        // If phone changed, reset phone verification
        if ($user->phone !== $validated['phone']) {
            $validated['phone_verified_at'] = null;
            $validated['phone_verification_code'] = null;
        }

        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }

    /**
     * Send phone verification code
     */
    public function sendPhoneVerification(Request $request)
    {
        $user = Auth::user();

        if (!$user->phone) {
            return response()->json(['error' => 'Phone number not provided'], 400);
        }

        if ($user->hasVerifiedPhone()) {
            return response()->json(['error' => 'Phone already verified'], 400);
        }

        $code = $user->generatePhoneVerificationCode();

        // Here you would typically send SMS with the code
        // For now, we'll just return it in the response (remove in production)
        return response()->json([
            'success' => true,
            'message' => 'Verification code sent to your phone',
            'code' => $code // Remove this in production
        ]);
    }

    /**
     * Verify phone number
     */
    public function verifyPhone(Request $request)
    {
        $request->validate([
            'verification_code' => ['required', 'string', 'size:6']
        ]);

        $user = Auth::user();

        if ($user->phone_verification_code !== $request->verification_code) {
            return response()->json(['error' => 'Invalid verification code'], 400);
        }

        $user->markPhoneAsVerified();

        return response()->json([
            'success' => true,
            'message' => 'Phone verified successfully!'
        ]);
    }

    /**
     * Update password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect']);
        }

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('password_success', 'Password updated successfully!');
    }
}
