@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Transaction Reports</h1>
                    <p class="text-muted">Comprehensive transaction analysis and reports
                        @if($selectedEvent)
                            - Filtered by: <strong>{{ $selectedEvent->name }}</strong>
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.export.transactions', request()->all()) }}" class="btn btn-success">
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
                    <form method="GET" action="{{ route('admin.reports.transactions') }}" class="row g-3">
                        <div class="col-md-4">
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
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-filter me-1"></i>Apply Filter
                                </button>
                            </div>
                        </div>
                        @if(request('event_id'))
                        <div class="col-md-2">
                            <label class="form-label">&nbsp;</label>
                            <div>
                                <a href="{{ route('admin.reports.transactions') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-times me-1"></i>Clear Filter
                                </a>
                            </div>
                        </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-4 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">৳{{ number_format($totalRevenue, 2) }}</h4>
                            <p class="card-text">Total Revenue</p>
                        </div>
                        <i class="fas fa-dollar-sign fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-warning text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">৳{{ number_format($pendingAmount, 2) }}</h4>
                            <p class="card-text">Pending Amount</p>
                        </div>
                        <i class="fas fa-clock fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h4 class="card-title">৳{{ number_format($todayRevenue, 2) }}</h4>
                            <p class="card-text">Today's Revenue</p>
                        </div>
                        <i class="fas fa-calendar-day fa-2x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">All Transactions</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Transaction ID</th>
                            <th>Participant</th>
                            <th>Event</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Payment Method</th>
                            <th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $transaction)
                        <tr>
                            <td>{{ $transaction->transaction_id ?: $transaction->id }}</td>
                            <td>
                                <div>
                                    <strong>{{ $transaction->participant->name }}</strong><br>
                                    <small class="text-muted">{{ $transaction->participant->email }}</small>
                                </div>
                            </td>
                            <td>{{ $transaction->event->name }}</td>
                            <td>৳{{ number_format($transaction->amount, 2) }}</td>
                            <td>
                                <span class="badge bg-{{ $transaction->status == 'complete' ? 'success' : ($transaction->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($transaction->status) }}
                                </span>
                            </td>
                            <td>{{ $transaction->payment_method ?: 'N/A' }}</td>
                            <td>{{ $transaction->created_at->format('M d, Y g:i A') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">No transactions found</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $transactions->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
