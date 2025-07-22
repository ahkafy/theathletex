@extends('layouts.template')

@section('content')
<div class="container my-5">
    <div class="row">
        <div class="col-md-12">
            <!-- Event Header -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="mb-2">{{ $event->name }} - Results</h1>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar me-2"></i>{{ \Carbon\Carbon::parse($event->start_time)->format('F d, Y') }}
                                <i class="fas fa-map-marker-alt ms-3 me-2"></i>{{ $event->venue }}
                            </p>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success fs-6">Event Completed</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category Filter -->
            @if($categories->count() > 0)
            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Filter by Category</h5>
                    <div class="btn-group flex-wrap" role="group">
                        <a href="{{ route('events.results', $event->slug) }}"
                           class="btn {{ !isset($category) ? 'btn-primary' : 'btn-outline-primary' }}">
                            All Categories
                        </a>
                        @foreach($categories as $cat)
                        <a href="{{ route('events.results.category', [$event->slug, $cat]) }}"
                           class="btn {{ isset($category) && $category == $cat ? 'btn-primary' : 'btn-outline-primary' }}">
                            {{ $cat }}
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Results Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        @if(isset($category))
                            Results - {{ $category }} Category
                        @else
                            Overall Results
                        @endif
                    </h5>
                </div>
                <div class="card-body">
                    @if($results->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Pl.</th>
                                    <th>Bib</th>
                                    <th>Name</th>
                                    <th>Sx</th>
                                    <th>Cat</th>
                                    <th>By Cat.</th>
                                    <th>Laps</th>
                                    <th>Time</th>
                                    <th>Gap</th>
                                    <th>Distance</th>
                                    <th>Chip Time</th>
                                    <th>Speed</th>
                                    <th>Best Lap</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($results as $result)
                                <tr class="{{ $result->dsq ? 'table-danger' : ($result->dnf ? 'table-warning' : '') }}">
                                    <td>
                                        @if($result->dsq)
                                            <span class="badge bg-danger">DSQ</span>
                                        @elseif($result->dnf)
                                            <span class="badge bg-warning">DNF</span>
                                        @else
                                            <strong>{{ $result->position }}</strong>
                                        @endif
                                    </td>
                                    <td>{{ $result->bib_number ?: '-' }}</td>
                                    <td>
                                        <strong>{{ $result->participant->name }}</strong>
                                    </td>
                                    <td>{{ $result->sex ?: '-' }}</td>
                                    <td>{{ $result->category ?: '-' }}</td>
                                    <td>{{ $result->category_position ?: '-' }}</td>
                                    <td>{{ $result->laps ?: '-' }}</td>
                                    <td>
                                        @if($result->finish_time && !$result->dnf && !$result->dsq)
                                            <strong>{{ $result->finish_time }}</strong>
                                        @else
                                            <span class="text-muted">{{ $result->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $result->gap ?: '-' }}</td>
                                    <td>{{ $result->distance ? number_format($result->distance, 2) . ' km' : '-' }}</td>
                                    <td>{{ $result->chip_time ?: '-' }}</td>
                                    <td>{{ $result->speed ? number_format($result->speed, 2) . ' km/h' : '-' }}</td>
                                    <td>{{ $result->best_lap ?: '-' }}</td>
                                    <td>
                                        @if(!$result->dnf && !$result->dsq)
                                        <a href="{{ route('events.certificate', [$event->slug, $result->participant_id]) }}"
                                           class="btn btn-sm btn-success" target="_blank">
                                            <i class="fas fa-certificate me-1"></i>Certificate
                                        </a>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Summary Stats -->
                    <div class="row mt-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $results->where('dnf', false)->where('dsq', false)->count() }}</h4>
                                    <p class="mb-0">Finishers</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $results->where('dnf', true)->count() }}</h4>
                                    <p class="mb-0">DNF</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $results->where('dsq', true)->count() }}</h4>
                                    <p class="mb-0">DSQ</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h4>{{ $results->count() }}</h4>
                                    <p class="mb-0">Total</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                        <h5 class="text-muted">No results available yet</h5>
                        <p class="text-muted">Results will be published once the event is completed.</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
.table th {
    font-size: 0.9rem;
    white-space: nowrap;
}
.table td {
    font-size: 0.9rem;
    vertical-align: middle;
}
.btn-group .btn {
    margin: 2px;
}
@media (max-width: 768px) {
    .table-responsive {
        font-size: 0.8rem;
    }
    .btn-group {
        flex-direction: column;
    }
    .btn-group .btn {
        margin: 1px 0;
    }
}
</style>
@endpush
@endsection
