<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Login - The Athlete X Limited</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>

@include('partials.navbar')

<!-- Hero Section -->
<section class="hero-section text-white py-4">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2">Login to Your Account</h1>
        <p class="lead">Access your athlete profile and event history</p>
    </div>
</section>

<!-- Main Content -->
<main class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-5 col-md-7">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h4 class="mb-0">
                        <i class="bi bi-box-arrow-in-right me-2"></i>
                        Welcome Back
                    </h4>
                </div>
                <div class="card-body p-4">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-envelope"></i>
                                </span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                       placeholder="Enter your email">
                                @error('email')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" required autocomplete="current-password"
                                       placeholder="Enter your password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        <strong>{{ $message }}</strong>
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>

                        <div class="d-grid gap-2 mb-3">
                            <button type="submit" class="btn global_button py-2">
                                <i class="bi bi-box-arrow-in-right me-2"></i>
                                Login
                            </button>
                        </div>

                        <div class="text-center">
                            @if (Route::has('password.request'))
                                <a class="text-decoration-none" href="{{ route('password.request') }}">
                                    <i class="bi bi-question-circle me-1"></i>
                                    Forgot Your Password?
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
                <div class="card-footer text-center py-3 bg-light">
                    <p class="mb-0">
                        Don't have an account?
                        <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">
                            <i class="bi bi-person-plus me-1"></i>
                            Create Account
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row g-md-5 g-2 px-3 px-md-0">
            <!-- Logo & Details -->
            <div class="col-md-3 mb-4">
                <img src="{{ url('images/logo-removebg-preview.png') }}" alt="Sports Zone Logo" style="max-width: 150px;" class="mb-2">
                <p class="small">
                    Sports Zone brings you closer to your favorite sports. Discover, register, and enjoy events across the country.
                </p>
            </div>

            <!-- About -->
            <div class="col-md-3 mb-4">
                <h5>About</h5>
                <p class="small">We're a dedicated platform connecting athletes and organizers through technology-driven sports events.</p>
            </div>

            <!-- Policies -->
            <div class="col-md-3 mb-4">
                <h5>Policies</h5>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('about') }}" class="text-white text-decoration-none">About Us</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-white text-decoration-none">Privacy Policy</a></li>
                    <li><a href="{{ route('delivery') }}" class="text-white text-decoration-none">Delivery Policy</a></li>
                    <li><a href="{{ route('return') }}" class="text-white text-decoration-none">Return Policy</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-md-3 mb-4">
                <h5>Contact</h5>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-geo-alt me-2"></i>18/4, Pallabi Road, Dhaka 1216</li>
                    <li><i class="bi bi-telephone me-2"></i>+880 1775 269643</li>
                    <li><i class="bi bi-envelope me-2"></i>info@theathletex.net</li>
                    <li><i class="bi bi-card-text me-2"></i>Trade License: TRAD/DSCC/088847/2024</li>
                </ul>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="row mt-4 pt-4 border-top border-secondary">
            <div class="col-12 text-center">
                <h6>Secure Payment Methods</h6>
                <img src="{{ url('/payment_banner.png') }}" alt="Payment Methods" class="img-fluid" style="max-height: 260px;">
            </div>
        </div>

        <hr class="border-light">
        <div class="text-center small">
            © 2025 The Athlete X Limited. All rights reserved. | Built in Bangladesh
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
