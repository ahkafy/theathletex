@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Event Results - {{ $event->name }}</h1>
                    <p class="text-muted">Manage race results and rankings</p>
                </div>
                <div>
                    <a href="{{ route('admin.events.results.create', $event->id) }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Add Result
                    </a>
                    <a href="{{ route('admin.events.results.import.show', $event->id) }}" class="btn btn-success">
                        <i class="fas fa-upload me-2"></i>Import Results
                    </a>
                    <a href="{{ route('admin.events.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Events
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Event Info -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <strong>Event:</strong> {{ $event->name }}
                        </div>
                        <div class="col-md-3">
                            <strong>Date:</strong> {{ \Carbon\Carbon::parse($event->start_time)->format('M d, Y') }}
                        </div>
                        <div class="col-md-3">
                            <strong>Venue:</strong> {{ $event->venue }}
                        </div>
                        <div class="col-md-3">
                            <strong>Total Results:</strong> {{ $results->total() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Results Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Event Results</h5>
        </div>
        <div class="card-body">
            @if($results->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Pl.</th>
                            <th>Bib</th>
                            <th>Participant</th>
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
                            <th>Status</th>
                            <th>Actions</th>
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
                                <div>
                                    <strong>{{ $result->participant->name }}</strong><br>
                                    <small class="text-muted">{{ $result->participant->email }}</small>
                                </div>
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
                                @if($result->dsq)
                                    <span class="badge bg-danger">Disqualified</span>
                                @elseif($result->dnf)
                                    <span class="badge bg-warning">Did Not Finish</span>
                                @else
                                    <span class="badge bg-success">Finished</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.events.results.edit', [$event->id, $result->id]) }}"
                                       class="btn btn-sm btn-outline-primary" title="Edit">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.events.results.destroy', [$event->id, $result->id]) }}"
                                          style="display: inline-block;"
                                          onsubmit="return confirm('Are you sure you want to delete this result?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-center">
                {{ $results->links() }}
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-trophy fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No results added yet</h5>
                <p class="text-muted">Start by adding race results for this event.</p>
                <a href="{{ route('admin.events.results.create', $event->id) }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Add First Result
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

@if(session('success'))
    <div class="position-fixed top-0 end-0 p-3" style="z-index: 1080;">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    </div>
@endif
@endsection
