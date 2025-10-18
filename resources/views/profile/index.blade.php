<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>My Profile - The Athlete X Limited</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>

@include('partials.navbar')

<!-- Hero Section -->
<section class="hero-section text-white py-4">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2">My Profile</h1>
        <p class="lead">Manage your athlete profile and event history</p>
    </div>
</section>

<!-- Main Content -->
<main class="container my-5">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Info -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-body text-center">
                    @if($user->profile_photo)
                        <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                    @else
                        <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 150px; height: 150px;">
                            <i class="bi bi-person-fill text-white" style="font-size: 4rem;"></i>
                        </div>
                    @endif

                    <h4 class="card-title">{{ $user->name }}</h4>
                    <p class="text-muted">{{ $user->email }}</p>

                    <div class="row text-center mt-3">
                        <div class="col-6">
                            <div class="border-end">
                                <h5 class="mb-0">{{ $participants->count() }}</h5>
                                <small class="text-muted">Events Joined</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h5 class="mb-0">
                                @if($user->email_verified_at && $user->hasVerifiedPhone())
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                @else
                                    <i class="bi bi-exclamation-circle-fill text-warning"></i>
                                @endif
                            </h5>
                            <small class="text-muted">Verified</small>
                        </div>
                    </div>

                    <div class="mt-3">
                        <a href="{{ route('profile.edit') }}" class="btn global_button me-2">
                            <i class="bi bi-pencil"></i> Edit Profile
                        </a>
                    </div>

                    <!-- Verification Status -->
                    <div class="mt-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span>Email Verification</span>
                            @if($user->email_verified_at)
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <span>Phone Verification</span>
                            @if($user->hasVerifiedPhone())
                                <span class="badge bg-success">Verified</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Info -->
            @if($user->bio || $user->sports_interests)
            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">About Me</h6>
                </div>
                <div class="card-body">
                    @if($user->bio)
                        <p class="mb-3">{{ $user->bio }}</p>
                    @endif

                    @if($user->sports_interests)
                        <h6>Sports Interests:</h6>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($user->sports_interests as $sport)
                                <span class="badge bg-secondary">{{ $sport }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
            @endif
        </div>

        <!-- Event History -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Event History</h5>
                    <span class="badge bg-primary">{{ $participants->count() }} Total</span>
                </div>
                <div class="card-body">
                    @if($participants->isEmpty())
                        <div class="text-center py-5">
                            <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                            <h5 class="text-muted mt-3">No Event Participation Yet</h5>
                            <p class="text-muted">Start by registering for upcoming events!</p>
                            <a href="{{ route('events.upcoming') }}" class="btn global_button">
                                Browse Upcoming Events
                            </a>
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Participant ID</th>
                                        <th>Category</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($participants as $participant)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($participant->event->cover_photo)
                                                        <img src="{{ url($participant->event->cover_photo) }}" alt="Event" class="rounded me-2" style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <h6 class="mb-0">{{ $participant->event->name }}</h6>
                                                        <small class="text-muted">{{ $participant->event->venue ?? 'TBA' }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <code>{{ $participant->participant_id ?? $participant->id }}</code>
                                            </td>
                                            <td>{{ $participant->category }}</td>
                                            <td>{{ \Carbon\Carbon::parse($participant->event->start_time)->format('M d, Y') }}</td>
                                            <td>
                                                @if(\Carbon\Carbon::parse($participant->event->start_time) > now())
                                                    <span class="badge bg-primary">Upcoming</span>
                                                @elseif(\Carbon\Carbon::parse($participant->event->end_time) < now())
                                                    <span class="badge bg-secondary">Completed</span>
                                                @else
                                                    <span class="badge bg-warning">In Progress</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if(\Carbon\Carbon::parse($participant->event->end_time) < now())
                                                    <a href="{{ route('events.results', $participant->event->slug) }}" class="btn btn-sm btn-outline-primary">
                                                        <i class="bi bi-trophy"></i> Results
                                                    </a>
                                                @else
                                                    <button class="btn btn-sm btn-outline-secondary" disabled>
                                                        <i class="bi bi-clock"></i> Pending
                                                    </button>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
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
