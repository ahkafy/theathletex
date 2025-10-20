@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Participants Report</h1>
                    <p class="text-muted">Comprehensive participant management and analysis
                        @if($selectedEvent)
                            - Filtered by: <strong>{{ $selectedEvent->name }}</strong>
                        @endif
                    </p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.export.participants', request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Export CSV
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Filter -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reports.participants') }}" class="row g-3">
                        <div class="col-md-3">
                            <label for="event_id" class="form-label">Filter by Event</label>
                            <select name="event_id" id="event_id" class="form-select">
                                <option value="">All Events</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                        {{ $event->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="event_category_id" class="form-label">Filter by Event Category</label>
                            <select name="event_category_id" id="event_category_id" class="form-select">
                                <option value="">All Categories</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="payment_status" class="form-label">Filter by Payment Status</label>
                            <select name="payment_status" id="payment_status" class="form-select">
                                <option value="">All Payment Status</option>
                                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid</option>
                                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>Apply Filter
                                </button>
                            </div>
                        </div>
                        @if(request('event_id') || request('payment_status'))
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <a href="{{ route('admin.reports.participants') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear Filters
                                </a>
                            </div>
                        </div>
                        @endif
                    </form>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const eventSelect = document.getElementById('event_id');
    const categorySelect = document.getElementById('event_category_id');
    function loadCategories(eventId, selectedCategory = '') {
        categorySelect.innerHTML = '<option value="">All Categories</option>';
        if (!eventId) return;
        fetch(`/admin/events/${eventId}/categories`)
            .then(res => res.json())
            .then(data => {
                data.forEach(cat => {
                    const opt = document.createElement('option');
                    opt.value = cat.name;
                    opt.textContent = cat.name;
                    if (cat.name == selectedCategory) opt.selected = true;
                    categorySelect.appendChild(opt);
                });
            });
    }
    eventSelect.addEventListener('change', function() {
        loadCategories(this.value);
    });
    // On page load, if event is selected, load categories
    if (eventSelect.value) {
        loadCategories(eventSelect.value, '{{ request('event_category_id') }}');
    }
});
</script>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Statistics -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['total_participants'] }}</h4>
                            <p class="card-text">Total Participants</p>
                        </div>
                        <i class="fas fa-users fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['paid_participants'] }}</h4>
                            <p class="card-text">Paid Participants</p>
                        </div>
                        <i class="fas fa-check-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['pending_participants'] }}</h4>
                            <p class="card-text">Pending Payments</p>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['today_registrations'] }}</h4>
                            <p class="card-text">Today's Registrations</p>
                        </div>
                        <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Participants Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Participants</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Participant ID</th>
                            <th>Participant Info</th>
                            <th>Event</th>
                            <th>Personal Details</th>
                            <th>Additional Fields</th>
                            <th>Address</th>
                            <th>Registration</th>
                            <th>Payment Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($participants as $index => $participant)
                        <tr>
                            <td>{{ ($participants->currentPage() - 1) * $participants->perPage() + $index + 1 }}</td>
                            <td>
                                <strong class="text-primary">{{ $participant->participant_id ?? 'N/A' }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $participant->name }}</strong><br>
                                    <small class="text-muted">
                                        <i class="fas fa-envelope me-1"></i>{{ $participant->email }}<br>
                                        <i class="fas fa-phone me-1"></i>{{ $participant->phone }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $participant->event->name ?? 'Event not found' }}</strong><br>
                                    <small class="text-muted">
                                        Category: {{ $participant->category ?? 'N/A' }}<br>
                                        Type: {{ ucfirst($participant->reg_type ?? 'N/A') }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small>
                                        <strong>Gender:</strong> {{ ucfirst($participant->gender ?? 'N/A') }}<br>
                                        <strong>DOB:</strong> {{ $participant->dob ? \Carbon\Carbon::parse($participant->dob)->format('M d, Y') : 'N/A' }}<br>
                                        <strong>T-Shirt:</strong> {{ $participant->tshirt_size ?? 'N/A' }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    @if($participant->additional_data && count($participant->additional_data) > 0)
                                        <small>
                                            @php
                                                $additionalCount = count($participant->additional_data);
                                                $firstThree = array_slice($participant->additional_data, 0, 3, true);
                                            @endphp
                                            @foreach($firstThree as $key => $value)
                                                <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                                @if(is_array($value))
                                                    {{ implode(', ', array_slice($value, 0, 2)) }}{{ count($value) > 2 ? '...' : '' }}
                                                @else
                                                    {{ strlen($value) > 30 ? substr($value, 0, 30) . '...' : $value }}
                                                @endif
                                                <br>
                                            @endforeach
                                            @if($additionalCount > 3)
                                                <span class="badge bg-secondary">+{{ $additionalCount - 3 }} more</span>
                                            @endif
                                        </small>
                                    @else
                                        <small class="text-muted">No additional fields</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small>
                                        {{ $participant->address }}<br>
                                        {{ $participant->thana }}, {{ $participant->district }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <small>
                                        <strong>Date:</strong> {{ $participant->created_at->format('M d, Y') }}<br>
                                        <strong>Time:</strong> {{ $participant->created_at->format('g:i A') }}<br>
                                        <strong>Emergency:</strong> {{ $participant->emergency_phone ?? 'N/A' }}
                                    </small>
                                </div>
                            </td>
                            <td>
                                @php
                                    $hasCompletedTransaction = $participant->transactions->whereIn('status', ['complete', 'Complete'])->count() > 0;
                                    $totalPaid = $participant->transactions->whereIn('status', ['complete', 'Complete'])->sum('amount');
                                @endphp
                                @if($hasCompletedTransaction)
                                    <span class="badge bg-success">Paid</span><br>
                                    <small>৳{{ number_format($totalPaid, 2) }}</small>
                                @else
                                    <span class="badge bg-warning">Pending</span><br>
                                    <small>৳{{ number_format($participant->fee ?? 0, 2) }}</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group-vertical btn-group-sm" role="group">
                                    <a href="{{ route('admin.reports.participant.view', $participant->id) }}" class="btn btn-sm btn-info text-white" title="View Full Details">
                                        <i class="fas fa-eye"></i> View
                                    </a>
                                    @if($participant->email)
                                    <a href="mailto:{{ $participant->email }}" class="btn btn-sm btn-outline-primary" title="Send Email">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    @endif
                                    @if($participant->phone)
                                    <a href="tel:{{ $participant->phone }}" class="btn btn-sm btn-outline-success" title="Call">
                                        <i class="fas fa-phone"></i>
                                    </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="10" class="text-center text-muted">No participants found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $participants->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
