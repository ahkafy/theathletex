@extends('layouts.template')

@section('content')

<!-- Main Content -->
<main class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-body text-center p-5">
                    @if($event->status === 'scheduled')
                        <div class="mb-4">
                            <i class="bi bi-clock-history text-info" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-3 text-info">Registration Not Started</h2>
                        <p class="lead mb-4">{{ $event->name }}</p>
                        <div class="alert alert-info" role="alert">
                            <i class="bi bi-info-circle me-2"></i>
                            Registration for this event has not started yet. The event is currently scheduled.
                        </div>
                        <p class="text-muted mb-4">
                            <strong>Event Start:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y - h:i A') }}
                        </p>
                    @elseif($event->status === 'closed')
                        <div class="mb-4">
                            <i class="bi bi-lock-fill text-secondary" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-3 text-secondary">Registration Closed</h2>
                        <p class="lead mb-4">{{ $event->name }}</p>
                        <div class="alert alert-secondary" role="alert">
                            <i class="bi bi-x-circle me-2"></i>
                            Registration for this event is now closed. No new registrations are being accepted.
                        </div>
                        <p class="text-muted mb-4">
                            <strong>Event Date:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y - h:i A') }}
                        </p>
                    @elseif($event->status === 'complete')
                        <div class="mb-4">
                            <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-3 text-success">Event Completed</h2>
                        <p class="lead mb-4">{{ $event->name }}</p>
                        <div class="alert alert-success" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            This event has been completed. Registration is no longer available.
                        </div>
                        <p class="text-muted mb-4">
                            <strong>Event Date:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}
                        </p>
                    @else
                        <div class="mb-4">
                            <i class="bi bi-exclamation-triangle-fill text-warning" style="font-size: 5rem;"></i>
                        </div>
                        <h2 class="fw-bold mb-3 text-warning">Registration Unavailable</h2>
                        <p class="lead mb-4">{{ $event->name }}</p>
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Registration is not available for this event at the moment.
                        </div>
                    @endif

                    <div class="d-grid gap-2 d-md-flex justify-content-md-center mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-lg px-5">
                            <i class="bi bi-arrow-left me-2"></i>Go Back
                        </a>
                        <a href="{{ route('events.all') }}" class="btn global_button btn-lg px-5">
                            <i class="bi bi-calendar-event me-2"></i>Browse Events
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

@endsection
