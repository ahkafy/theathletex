@extends('admin.layouts.app')
@section('content')

<div style="display: flex; height: 100vh;">
    <!-- Left Side (empty or for branding) -->
    <div style="flex: 1; background: #f5f5f5;">

        <div style="display: flex; flex-direction: column; align-items: center; justify-content: space-between; height: 100%; padding: 20vh 0;">

            <div class="logo" style="text-align: center;">
                <img src="https://cloudhousebd.com/logo.svg" alt="Logo" style="max-width: 150px; margin-bottom: 20px;">
                <h1 style="font-family: cursive, 'Comic Sans MS', 'Brush Script MT', sans-serif; color: #333;">Welcome to Admin Panel</h1>
                <p style="font-family: cursive, 'Comic Sans MS', 'Brush Script MT', sans-serif; color: #555;">Your gateway to managing the platform</p>
            </div>
            <div id="inspire-quote-top" style="font-size: 1.2em; font-family: cursive, 'Comic Sans MS', 'Brush Script MT', sans-serif; color: #555; text-align: center; max-width: 400px; margin-top: 20px;"></div>
            <script>
                const quotes = [
                    "Success is not final, failure is not fatal: It is the courage to continue that counts. – Winston Churchill",
                    "The only way to do great work is to love what you do. – Steve Jobs",
                    "Believe you can and you're halfway there. – Theodore Roosevelt",
                    "Opportunities don't happen, you create them. – Chris Grosser",
                    "Don't watch the clock; do what it does. Keep going. – Sam Levenson"
                ];
                function showRandomQuotes() {
                    let topIdx = Math.floor(Math.random() * quotes.length);
                    let bottomIdx;
                    do {
                        bottomIdx = Math.floor(Math.random() * quotes.length);
                    } while (bottomIdx === topIdx && quotes.length > 1);
                    document.getElementById('inspire-quote-top').innerText = quotes[topIdx];
                    document.getElementById('inspire-quote-bottom').innerText = quotes[bottomIdx];
                }
                document.addEventListener('DOMContentLoaded', showRandomQuotes);
            </script>
        </div>
    </div>
    <!-- Right Side (Login Form) -->
    <div style="flex: 1; display: flex; align-items: center; justify-content: center; background: #fff;">
        <form method="POST" action="{{ route('admin.login.submit') }}" style="width: 100%; max-width: 350px;">
            @csrf
            <h2 style="text-align:center; margin-bottom: 24px;">Admin Login</h2>

            <input type="email" name="email" required placeholder="Admin Email" value="{{ old('email') }}" style="width:100%; padding:10px; margin-bottom:8px; border:1px solid #ccc; border-radius:4px;">
            @error('email')
                <div style="color: #dc3545; font-size: 0.95em; margin-bottom: 8px;">{{ $message }}</div>
            @enderror

            <input type="password" name="password" required placeholder="Password" style="width:100%; padding:10px; margin-bottom:8px; border:1px solid #ccc; border-radius:4px;">
            @error('password')
                <div style="color: #dc3545; font-size: 0.95em; margin-bottom: 16px;">{{ $message }}</div>
            @enderror
            <button type="submit" style="width:100%; padding:10px; background:#007bff; color:#fff; border:none; border-radius:4px;">Login</button>
        </form>
    </div>
</div>

@endsection
