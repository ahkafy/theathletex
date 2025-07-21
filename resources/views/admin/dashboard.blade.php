@extends('admin.layouts.admin')
@section('content')

<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>Admin Dashboard</h1>
            <p class="text-muted">Welcome to the admin dashboard. Here's an overview of your athletic events platform.</p>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['total_events'] }}</h4>
                            <p class="card-text">Total Events</p>
                        </div>
                        <i class="fas fa-calendar-alt fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-success text-white">
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
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">৳{{ number_format($stats['total_revenue'], 2) }}</h4>
                            <p class="card-text">Total Revenue</p>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">{{ $stats['active_events'] }}</h4>
                            <p class="card-text">Active Events</p>
                        </div>
                        <i class="fas fa-play-circle fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Quick Actions</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 col-lg-3 mb-2">
                            <a href="{{ route('admin.events.create') }}" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i>Create Event
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-2">
                            <a href="{{ route('admin.reports.participants') }}" class="btn btn-success w-100">
                                <i class="fas fa-users me-2"></i>View Participants
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-2">
                            <a href="{{ route('admin.reports.transactions') }}" class="btn btn-info w-100">
                                <i class="fas fa-credit-card me-2"></i>View Transactions
                            </a>
                        </div>
                        <div class="col-md-6 col-lg-3 mb-2">
                            <a href="{{ route('admin.reports.revenue') }}" class="btn btn-warning w-100">
                                <i class="fas fa-chart-line me-2"></i>Revenue Report
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Transactions -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Recent Transactions</h5>
                </div>
                <div class="card-body">
                    @if($stats['recent_transactions']->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Participant</th>
                                        <th>Event</th>
                                        <th>Amount</th>
                                        <th>Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($stats['recent_transactions'] as $transaction)
                                    <tr>
                                        <td>{{ $transaction->participant->name }}</td>
                                        <td>{{ Str::limit($transaction->event->name, 20) }}</td>
                                        <td>৳{{ number_format($transaction->amount, 2) }}</td>
                                        <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="text-end">
                            <a href="{{ route('admin.reports.transactions') }}" class="btn btn-sm btn-outline-primary">View All</a>
                        </div>
                    @else
                        <p class="text-muted">No recent transactions found.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Upcoming Events -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Upcoming Events</h5>
                </div>
                <div class="card-body">
                    @if($stats['upcoming_events']->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($stats['upcoming_events'] as $event)
                            <div class="list-group-item d-flex justify-content-between align-items-center px-0">
                                <div>
                                    <h6 class="mb-1">{{ $event->name }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        {{ Carbon\Carbon::parse($event->start_time)->format('M d, Y g:i A') }}
                                    </small>
                                </div>
                                <span class="badge bg-{{ $event->status == 'open' ? 'success' : 'warning' }} rounded-pill">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </div>
                            @endforeach
                        </div>
                        <div class="text-end mt-3">
                            <a href="{{ route('admin.events.index') }}" class="btn btn-sm btn-outline-primary">View All Events</a>
                        </div>
                    @else
                        <p class="text-muted">No upcoming events scheduled.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
