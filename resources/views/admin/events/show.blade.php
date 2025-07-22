@extends('admin.layouts.admin')

@section('content')

<div class="row mb-4">
    <div class="col-md-12">
        <div class="d-flex justify-content-between align-items-center">
            <h1>Event Details</h1>
            <div>
                <a href="{{ route('admin.events.results.index', $event->id) }}" class="btn btn-info">Manage Results</a>
                <a href="{{ route('admin.events.edit', $event->slug) }}" class="btn btn-warning">Edit Event</a>
                <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Back to Events</a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>{{ $event->name }}</h4>
            </div>
            <div class="card-body">
                @if($event->cover_photo)
                    <div class="mb-3">
                        <img src="{{ asset($event->cover_photo) }}" alt="Event Cover Photo" class="img-fluid rounded" style="max-height: 300px; width: 100%; object-fit: cover;">
                    </div>
                @endif

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Status:</strong>
                        <span class="badge bg-{{ $event->status == 'open' ? 'success' : ($event->status == 'closed' ? 'danger' : ($event->status == 'complete' ? 'secondary' : 'warning')) }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </div>
                    <div class="col-md-6">
                        <strong>Capacity:</strong> {{ $event->capacity ?: 'Unlimited' }}
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <strong>Start Time:</strong><br>
                        {{ $event->start_time ? date('F j, Y g:i A', strtotime($event->start_time)) : 'Not set' }}
                    </div>
                    <div class="col-md-6">
                        <strong>End Time:</strong><br>
                        {{ $event->end_time ? date('F j, Y g:i A', strtotime($event->end_time)) : 'Not set' }}
                    </div>
                </div>

                @if($event->venue)
                <div class="mb-3">
                    <strong>Venue:</strong> {{ $event->venue }}
                </div>
                @endif

                @if($event->description)
                <div class="mb-3">
                    <strong>Description:</strong>
                    <div class="mt-2 p-3 bg-light rounded">
                        {!! $event->description !!}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Event Fees -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Event Fees</h5>
            </div>
            <div class="card-body">
                @if($event->fees && $event->fees->count())
                    <ul class="list-unstyled">
                        @foreach($event->fees as $fee)
                            <li class="mb-2">
                                <strong>{{ $fee->fee_type }}:</strong> ৳{{ number_format($fee->fee_amount, 2) }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No fees set for this event.</p>
                @endif
                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createFeeModal">
                    Add Fee
                </button>
            </div>
        </div>

        <!-- Event Categories -->
        <div class="card mb-3">
            <div class="card-header">
                <h5>Event Categories</h5>
            </div>
            <div class="card-body">
                @if($event->categories && $event->categories->count())
                    <ul class="list-unstyled">
                        @foreach($event->categories as $category)
                            <li class="mb-1">
                                <span class="badge bg-primary">{{ $category->name }}</span>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-muted">No categories set for this event.</p>
                @endif
                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoryModal">
                    Add Category
                </button>
            </div>
        </div>

        <!-- Event Statistics -->
        <div class="card">
            <div class="card-header">
                <h5>Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h4>{{ $event->participants->count() ?? 0 }}</h4>
                        <small class="text-muted">Participants</small>
                    </div>
                    <div class="col-6">
                        <h4>৳{{ number_format($event->transactions->where('status', 'complete')->sum('amount') ?? 0, 2) }}</h4>
                        <small class="text-muted">Revenue</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Fee Modal -->
<div class="modal fade" id="createFeeModal" tabindex="-1" aria-labelledby="createFeeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.fees.store') }}" method="POST">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createFeeModalLabel">Create Fee for {{ $event->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="fee_name" class="form-label">Fee Name</label>
                        <input type="text" class="form-control" id="fee_name" name="fee_type" required>
                    </div>
                    <div class="mb-3">
                        <label for="fee_amount" class="form-label">Amount</label>
                        <input type="number" class="form-control" id="fee_amount" name="fee_amount" step="0.01" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Fee</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <input type="hidden" name="event_id" value="{{ $event->id }}">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createCategoryModalLabel">Create Category for {{ $event->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_name" class="form-label">Category Name</label>
                        <input type="text" class="form-control" id="category_name" name="category_name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Create Category</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection
