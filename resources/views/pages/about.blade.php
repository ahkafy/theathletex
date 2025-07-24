@extends('layouts.app')

@section('title', 'About Us - TheAthleteX')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="text-center mb-5">
                <h1 class="display-4 fw-bold text-primary">About TheAthleteX</h1>
                <p class="lead text-muted">Empowering athletes and sports enthusiasts across Bangladesh</p>
            </div>

            <div class="row">
                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-bullseye fa-3x text-primary"></i>
                            </div>
                            <h4 class="card-title text-center">Our Mission</h4>
                            <p class="card-text text-muted">
                                To create a comprehensive platform that connects athletes, organizers, and sports enthusiasts,
                                making sports events more accessible, organized, and enjoyable for everyone in Bangladesh.
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body p-4">
                            <div class="text-center mb-3">
                                <i class="fas fa-eye fa-3x text-primary"></i>
                            </div>
                            <h4 class="card-title text-center">Our Vision</h4>
                            <p class="card-text text-muted">
                                To become the leading sports event management platform in Bangladesh, fostering a vibrant
                                athletic community and promoting healthy, active lifestyles across all age groups.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="text-center mb-4">What We Do</h3>
                    <div class="row">
                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-calendar-alt fa-2x"></i>
                                </div>
                                <h5>Event Management</h5>
                                <p class="text-muted">
                                    Complete event organization from registration to results, making it easy for organizers
                                    to manage athletic competitions of all sizes.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-users fa-2x"></i>
                                </div>
                                <h5>Athlete Registration</h5>
                                <p class="text-muted">
                                    Streamlined registration process with secure payment integration, making it simple
                                    for athletes to join events and track their participation.
                                </p>
                            </div>
                        </div>

                        <div class="col-md-4 mb-4">
                            <div class="text-center">
                                <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 80px; height: 80px;">
                                    <i class="fas fa-trophy fa-2x"></i>
                                </div>
                                <h5>Results & Certificates</h5>
                                <p class="text-muted">
                                    Real-time results tracking with digital certificates, providing athletes with
                                    instant access to their performance data and achievements.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <div class="bg-light rounded p-5">
                        <h3 class="text-center mb-4">Our Story</h3>
                        <p class="text-muted text-center lead">
                            TheAthleteX was born from a passion for sports and technology. We recognized the challenges
                            faced by event organizers and athletes in Bangladesh's growing sports community. Traditional
                            registration methods were often cumbersome, result tracking was manual, and communication
                            between organizers and participants was fragmented.
                        </p>
                        <p class="text-muted text-center">
                            Our platform bridges these gaps by providing a modern, user-friendly solution that handles
                            everything from event registration to result publication. We're committed to supporting
                            Bangladesh's athletic community with technology that makes sports events more professional,
                            accessible, and enjoyable for everyone involved.
                        </p>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12">
                    <h3 class="text-center mb-4">Why Choose TheAthleteX?</h3>
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-success fa-lg mt-1"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Secure Payment Processing</h6>
                                    <p class="text-muted mb-0">Integrated with trusted payment gateways for safe and reliable transactions.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-success fa-lg mt-1"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Real-time Updates</h6>
                                    <p class="text-muted mb-0">Instant notifications and updates throughout the event lifecycle.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-success fa-lg mt-1"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>Professional Results</h6>
                                    <p class="text-muted mb-0">Comprehensive result tracking with detailed performance analytics.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-success fa-lg mt-1"></i>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6>24/7 Support</h6>
                                    <p class="text-muted mb-0">Dedicated support team to assist organizers and participants.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <p class="text-muted">
                    Join thousands of athletes and organizers who trust TheAthleteX for their sporting events.
                </p>
                <a href="{{ route('index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-home me-2"></i>Explore Events
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
