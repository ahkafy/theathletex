<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>All Events - The Athlete X Limited</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>

@include('partials.navbar')

<!-- Hero Section -->
<section class="hero-section text-white py-5">
    <div class="container text-center">
        <h1 class="display-4 fw-bold mb-3">All Events</h1>
        <p class="lead">Discover all the exciting sports events available on our platform</p>
    </div>
</section>

<!-- Main Content -->
<main class="container my-5">
    <!-- All Events Section -->
    <section class="my-5">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <h2 class="fw-bold">All Events ({{ $events->count() }})</h2>
            <div class="d-flex gap-2">
                <a class="px-4 py-2 rounded-4 text-decoration-none fw-medium global_button-outline" href="{{ route('events.upcoming') }}">Upcoming</a>
                <a class="px-4 py-2 rounded-4 text-decoration-none fw-medium global_button-outline" href="{{ route('events.past') }}">Past</a>
            </div>
        </div>

        <div class="row">
            @if($events->isEmpty())
                <div class="col-12">
                    <div class="text-center py-5">
                        <h3 class="text-muted">No events found!</h3>
                        <p class="text-muted">Check back later for exciting events.</p>
                    </div>
                </div>
            @else
                @foreach ($events as $event)
                    <div class="col-12 col-md-6 col-lg-4 col-xl-3 mb-4">
                        <div class="card rounded-1 h-100">
                            <a href="{{ url('#') }}" class="card-title d-block nav-link p-3 fw-bold">{{ $event->name }}</a>
                            <a class="d-block px-3" href="{{ url('#') }}">
                                <img src="{{ url($event->cover_photo) }}" class="card-img-top rounded-0 object-fit-cover" alt="{{ $event->name }}" style="height: 200px;">
                            </a>
                            <div class="card-body p-0 d-flex flex-column">
                                <p class="mb-1 px-3 pt-3"><span class="fw-semibold">Status:</span> {{ ucfirst($event->status) }}</p>
                                <p class="mb-1 px-3"><span class="fw-semibold">Start Date:</span> {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}</p>
                                @if($event->venue)
                                    <p class="mb-1 px-3"><span class="fw-semibold">Venue:</span> {{ $event->venue }}</p>
                                @endif
                                <div class="mt-auto">
                                    @if(\Carbon\Carbon::parse($event->start_time) > now())
                                        <a href="{{ route('otp.form', $event->id) }}" class="btn global_button mt-2 d-block rounded-0 rounded-bottom-1 text-uppercase">Register Now</a>
                                    @elseif(\Carbon\Carbon::parse($event->end_time) < now())
                                        <a href="{{ url('#') }}" class="btn btn-secondary mt-2 d-block rounded-0 rounded-bottom-1 text-uppercase">View Results</a>
                                    @else
                                        <div class="btn btn-warning mt-2 d-block rounded-0 rounded-bottom-1 text-uppercase">Event in Progress</div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
    </section>
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
