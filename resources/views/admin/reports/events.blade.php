@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Event Reports</h1>
                    <p class="text-muted">Comprehensive event statistics and performance analysis</p>
                </div>
                <div>
                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Create Event
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Statistics -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Event Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-2 col-6 text-center">
                            <h3 class="text-primary">{{ $eventStats['total_events'] }}</h3>
                            <small class="text-muted">Total Events</small>
                        </div>
                        <div class="col-md-2 col-6 text-center">
                            <h3 class="text-warning">{{ $eventStats['scheduled'] }}</h3>
                            <small class="text-muted">Scheduled</small>
                        </div>
                        <div class="col-md-2 col-6 text-center">
                            <h3 class="text-success">{{ $eventStats['open'] }}</h3>
                            <small class="text-muted">Open</small>
                        </div>
                        <div class="col-md-2 col-6 text-center">
                            <h3 class="text-danger">{{ $eventStats['closed'] }}</h3>
                            <small class="text-muted">Closed</small>
                        </div>
                        <div class="col-md-2 col-6 text-center">
                            <h3 class="text-secondary">{{ $eventStats['completed'] }}</h3>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Events Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Events</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Date & Time</th>
                            <th>Status</th>
                            <th>Participants</th>
                            <th>Revenue</th>
                            <th>Categories</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events as $event)
                        <tr>
                            <td>
                                <div>
                                    <strong>{{ $event->name }}</strong><br>
                                    <small class="text-muted">{{ $event->venue ?: 'No venue specified' }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('M d, Y') : 'TBD' }}</strong><br>
                                    <small class="text-muted">{{ $event->start_time ? \Carbon\Carbon::parse($event->start_time)->format('g:i A') : '' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $event->status == 'open' ? 'success' : ($event->status == 'scheduled' ? 'warning' : ($event->status == 'closed' ? 'danger' : 'secondary')) }}">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $event->participants->count() }}</span>
                            </td>
                            <td>
                                ৳{{ number_format($event->transactions->where('status', 'complete')->sum('amount'), 2) }}
                            </td>
                            <td>
                                @if($event->categories->count() > 0)
                                    @foreach($event->categories as $category)
                                        <span class="badge bg-primary me-1">{{ $category->name }}</span>
                                    @endforeach
                                @else
                                    <span class="text-muted">No categories</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.events.show', $event->slug) }}" class="btn btn-sm btn-outline-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event->slug) }}" class="btn btn-sm btn-outline-warning">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No events found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $events->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
