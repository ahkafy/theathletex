@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Analytics Dashboard</h1>
                    <p class="text-muted">Advanced analytics and insights for your athletic events platform</p>
                </div>
                <div>
                    <button class="btn btn-outline-primary" onclick="window.print()">
                        <i class="fas fa-print me-2"></i>Print Report
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Registration Trends -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Registration Trends (Last 30 Days)</h5>
                </div>
                <div class="card-body">
                    @if($registrationTrends->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Registrations</th>
                                        <th>Trend</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrationTrends->sortByDesc('date')->take(10) as $trend)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($trend->date)->format('M d, Y') }}</td>
                                        <td>
                                            <span class="badge bg-primary">{{ $trend->count }}</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px; min-width: 60px;">
                                                <div class="progress-bar bg-success"
                                                     style="width: {{ $registrationTrends->max('count') > 0 ? ($trend->count / $registrationTrends->max('count') * 100) : 0 }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No registration data available for the last 30 days</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Top Events by Participation -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Most Popular Events</h5>
                </div>
                <div class="card-body">
                    @if($topEvents->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Event</th>
                                        <th>Participants</th>
                                        <th>Popularity</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topEvents as $event)
                                    <tr>
                                        <td>{{ Str::limit($event->name, 30) }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $event->participants_count }}</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px; min-width: 60px;">
                                                <div class="progress-bar bg-info"
                                                     style="width: {{ $topEvents->max('participants_count') > 0 ? ($event->participants_count / $topEvents->max('participants_count') * 100) : 0 }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No event participation data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Event Categories Statistics -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Event Categories Distribution</h5>
                </div>
                <div class="card-body">
                    @if($categoryStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Category</th>
                                        <th>Events</th>
                                        <th>Distribution</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($categoryStats as $category)
                                    <tr>
                                        <td>{{ $category->name }}</td>
                                        <td>
                                            <span class="badge bg-warning">{{ $category->event_count }}</span>
                                        </td>
                                        <td>
                                            <div class="progress" style="height: 20px; min-width: 60px;">
                                                <div class="progress-bar bg-warning"
                                                     style="width: {{ $categoryStats->max('event_count') > 0 ? ($category->event_count / $categoryStats->max('event_count') * 100) : 0 }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No category data available</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Methods Analysis -->
        <div class="col-md-6 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Payment Methods Analysis</h5>
                </div>
                <div class="card-body">
                    @if($paymentMethodStats->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Payment Method</th>
                                        <th>Transactions</th>
                                        <th>Total Amount</th>
                                        <th>Usage</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($paymentMethodStats as $method)
                                    <tr>
                                        <td>{{ $method->payment_method ?: 'Unknown' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $method->count }}</span>
                                        </td>
                                        <td>৳{{ number_format($method->total, 2) }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px; min-width: 60px;">
                                                <div class="progress-bar bg-info"
                                                     style="width: {{ $paymentMethodStats->max('count') > 0 ? ($method->count / $paymentMethodStats->max('count') * 100) : 0 }}%">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No payment method data available</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Key Performance Indicators -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Key Performance Indicators</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-primary">{{ $topEvents->sum('participants_count') }}</h4>
                                <small class="text-muted">Total Registrations</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-success">{{ $registrationTrends->sum('count') }}</h4>
                                <small class="text-muted">30-Day Registrations</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-info">{{ number_format($registrationTrends->avg('count'), 1) }}</h4>
                                <small class="text-muted">Daily Avg. Registrations</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-warning">{{ $categoryStats->count() }}</h4>
                                <small class="text-muted">Active Categories</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-danger">{{ $paymentMethodStats->count() }}</h4>
                                <small class="text-muted">Payment Methods</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <h4 class="text-secondary">{{ $topEvents->where('participants_count', '>', 0)->count() }}</h4>
                                <small class="text-muted">Active Events</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
