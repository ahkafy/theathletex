@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Events Management</h1>
                    <p class="text-muted">Manage all events, categories, and results</p>
                </div>
                <div>
                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Event
                    </a>
                </div>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Events Grid -->
    <div class="row">
        @foreach($events as $event)
            <div class="col-lg-6 col-xl-4 mb-4">
                <div class="card h-100">
                    @if($event->cover_photo)
                        <img src="{{ asset($event->cover_photo) }}" class="card-img-top" style="height: 200px; object-fit: cover;" alt="{{ $event->name }}">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="fas fa-image fa-3x text-muted"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $event->name }}</h5>
                        <p class="card-text text-muted">{!! Str::limit($event->description, 100) !!}</p>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }} -
                                {{ \Carbon\Carbon::parse($event->end_time)->format('M d, Y') }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">
                                <i class="fas fa-map-marker-alt me-1"></i>
                                {{ $event->venue ?? 'No venue specified' }}
                            </small>
                        </div>

                        <div class="mb-3">
                            <span class="badge bg-{{ $event->status === 'active' ? 'success' : ($event->status === 'completed' ? 'secondary' : 'warning') }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </div>

                        <!-- Event Stats -->
                        <div class="row text-center mb-3">
                            <div class="col-4">
                                <div class="border-end">
                                    <div class="h6 mb-0">{{ $event->categories->count() }}</div>
                                    <small class="text-muted">Categories</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="border-end">
                                    <div class="h6 mb-0">{{ $event->fees->count() }}</div>
                                    <small class="text-muted">Fee Types</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="h6 mb-0">{{ $event->participants->count() ?? 0 }}</div>
                                <small class="text-muted">Participants</small>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="mt-auto">
                            <div class="d-grid gap-2">
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.events.show', $event->slug) }}" class="btn btn-outline-info btn-sm">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event->slug) }}" class="btn btn-outline-warning btn-sm">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    <a href="{{ route('admin.events.results.index', $event->id) }}" class="btn btn-outline-primary btn-sm">
                                        <i class="fas fa-trophy"></i> Results
                                    </a>
                                </div>

                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.events.results.import.show', $event->id) }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-upload"></i> Import Results
                                    </a>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#createFeeModal{{ $event->id }}">
                                        <i class="fas fa-dollar-sign"></i> Add Fee
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#createCategoryModal{{ $event->id }}">
                                        <i class="fas fa-tags"></i> Add Category
                                    </button>
                                </div>

                                <button class="btn btn-outline-danger btn-sm" onclick="confirmDelete('{{ $event->slug }}', '{{ $event->name }}')">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($events->count() === 0)
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <i class="fas fa-calendar-times fa-5x text-muted mb-3"></i>
                    <h4 class="text-muted">No Events Found</h4>
                    <p class="text-muted">Start by creating your first event.</p>
                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create First Event
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Modals for each event -->
@foreach($events as $event)
    <!-- Fee Modal for this event -->
    <div class="modal fade" id="createFeeModal{{ $event->id }}" tabindex="-1" aria-labelledby="createFeeModalLabel{{ $event->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.fees.store') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createFeeModalLabel{{ $event->id }}">
                            <i class="fas fa-dollar-sign me-2"></i>Create Fee for {{ $event->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="fee_name_{{ $event->id }}" class="form-label">Fee Type</label>
                            <input type="text" class="form-control" id="fee_name_{{ $event->id }}" name="fee_type" placeholder="e.g., Early Bird, Regular, Student" required>
                        </div>
                        <div class="mb-3">
                            <label for="fee_amount_{{ $event->id }}" class="form-label">Amount (BDT)</label>
                            <input type="number" class="form-control" id="fee_amount_{{ $event->id }}" name="fee_amount" step="0.01" placeholder="0.00" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Fee
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Modal for this event -->
    <div class="modal fade" id="createCategoryModal{{ $event->id }}" tabindex="-1" aria-labelledby="createCategoryModalLabel{{ $event->id }}" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event->id }}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="createCategoryModalLabel{{ $event->id }}">
                            <i class="fas fa-tags me-2"></i>Create Category for {{ $event->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="category_name_{{ $event->id }}" class="form-label">Category Name</label>
                            <input type="text" class="form-control" id="category_name_{{ $event->id }}" name="category_name" placeholder="e.g., Men 18-29, Women 30-39, Elite" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create Category
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endforeach

<!-- Delete Confirmation Form -->
<form id="deleteForm" action="" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@section('scripts')
<script>
function confirmDelete(eventSlug, eventName) {
    if (confirm(`Are you sure you want to delete "${eventName}"? This action cannot be undone.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/events/${eventSlug}`;
        form.submit();
    }
}
</script>
@endsection
