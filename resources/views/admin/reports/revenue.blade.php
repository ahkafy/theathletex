@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Revenue Reports</h1>
                    <p class="text-muted">Detailed revenue analysis and financial insights
                        @if($selectedEvent)
                            - Filtered by: <strong>{{ $selectedEvent->name }}</strong>
                        @endif
                    </p>
                </div>
                <div>
                    <a href="{{ route('admin.export.transactions', request()->all()) }}" class="btn btn-success">
                        <i class="fas fa-download me-2"></i>Export Financial Data
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
                    <form method="GET" action="{{ route('admin.reports.revenue') }}" class="row g-3">
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
                                <a href="{{ route('admin.reports.revenue') }}" class="btn btn-outline-secondary">
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

    <!-- Revenue Summary -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3">
            <div class="card bg-success text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">৳{{ number_format($totalRevenue, 2) }}</h3>
                            <p class="card-text">Total Revenue</p>
                        </div>
                        <i class="fas fa-chart-line fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-3">
            <div class="card bg-info text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="card-title">৳{{ number_format($averageTransactionValue, 2) }}</h3>
                            <p class="card-text">Average Transaction Value</p>
                        </div>
                        <i class="fas fa-calculator fa-3x opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Monthly Revenue Trend -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Monthly Revenue Trend</h5>
                </div>
                <div class="card-body">
                    @if($monthlyRevenue->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Month</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($monthlyRevenue as $month)
                                    <tr>
                                        <td>{{ date('F Y', mktime(0, 0, 0, $month->month, 1, $month->year)) }}</td>
                                        <td>৳{{ number_format($month->total, 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No revenue data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Revenue by Event -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Top Revenue Generating Events</h5>
                </div>
                <div class="card-body">
                    @if($revenueByEvent->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Participants</th>
                                        <th>Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($revenueByEvent->take(10) as $event)
                                    <tr>
                                        <td>{{ Str::limit($event['event_name'], 25) }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $event['participant_count'] }}</span>
                                        </td>
                                        <td>৳{{ number_format($event['total_revenue'], 2) }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No revenue data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Revenue Performance Chart Placeholder -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Revenue Performance Overview</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($revenueByEvent->take(5) as $index => $event)
                        <div class="col-md-2 col-6 text-center mb-3">
                            <div class="progress" style="height: 100px;">
                                <div class="progress-bar bg-{{ ['primary', 'success', 'info', 'warning', 'secondary'][$index % 5] }}"
                                     role="progressbar"
                                     style="width: {{ $totalRevenue > 0 ? ($event['total_revenue'] / $totalRevenue * 100) : 0 }}%; writing-mode: vertical-rl; text-orientation: mixed;">
                                    ৳{{ number_format($event['total_revenue']) }}
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2">{{ Str::limit($event['event_name'], 15) }}</small>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
