<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">

                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ms-auto">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }}
                                </a>

                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>

        <!-- Footer -->
        <footer class="bg-dark text-light py-5 mt-5">
            <div class="container">
                <div class="row">
                    <!-- About Section -->
                    <div class="col-lg-4 col-md-6 mb-4">
                        <h5 class="fw-bold mb-3">{{ config('app.name', 'TheAthleteX') }}</h5>
                        <p class="text-muted mb-3">
                            Your premier platform for athletic event registration and participation.
                            Join thousands of athletes in competitive sports events across Bangladesh.
                        </p>
                        <div class="text-muted small">
                            <p class="mb-1"><i class="fas fa-map-marker-alt me-2"></i>18/4, Pallabi Road, Dhaka 1216</p>
                            <p class="mb-1"><i class="fas fa-phone me-2"></i>+880 1234-567890</p>
                            <p class="mb-1"><i class="fas fa-envelope me-2"></i>info@theathletex.com</p>
                            <p class="mb-0"><i class="fas fa-certificate me-2"></i>Trade License: TRAD/DSCC/088847/2024</p>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="fw-bold mb-3">Quick Links</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Events</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Results</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Registration</a></li>
                        </ul>
                    </div>

                    <!-- Policies -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="fw-bold mb-3">Policies</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('about') }}" class="text-muted text-decoration-none">About Us</a></li>
                            <li class="mb-2"><a href="{{ route('privacy') }}" class="text-muted text-decoration-none">Privacy Policy</a></li>
                            <li class="mb-2"><a href="{{ route('delivery') }}" class="text-muted text-decoration-none">Delivery Policy</a></li>
                            <li class="mb-2"><a href="{{ route('return') }}" class="text-muted text-decoration-none">Return Policy</a></li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h6 class="fw-bold mb-3">Support</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Help Center</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Contact Us</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">FAQ</a></li>
                            <li class="mb-2"><a href="#" class="text-muted text-decoration-none">Live Chat</a></li>
                        </ul>
                    </div>

                    <!-- Social Media -->
                    <div class="col-lg-2 col-md-12 mb-4">
                        <h6 class="fw-bold mb-3">Follow Us</h6>
                        <div class="d-flex gap-3">
                            <a href="#" class="text-muted"><i class="fab fa-facebook fa-lg"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-twitter fa-lg"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-instagram fa-lg"></i></a>
                            <a href="#" class="text-muted"><i class="fab fa-youtube fa-lg"></i></a>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods -->
                <div class="row mt-4 pt-4 border-top border-secondary">
                    <div class="col-12 text-center">
                        <h6 class="fw-bold mb-3">Secure Payment Methods</h6>
                        <img src="{{ url('/payment_banner.png') }}" alt="Payment Methods" class="img-fluid" style="max-height: 60px;">
                    </div>
                </div>

                <!-- Copyright -->
                <div class="row mt-4 pt-4 border-top border-secondary">
                    <div class="col-md-6">
                        <p class="text-muted small mb-0">
                            &copy; {{ date('Y') }} {{ config('app.name', 'TheAthleteX') }}. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="text-muted small mb-0">
                            Powered by TheAthleteX Platform | Built in Bangladesh
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</body>
</html>
