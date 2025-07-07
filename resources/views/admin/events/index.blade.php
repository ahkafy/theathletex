@extends('admin.layouts.admin')

@section('content')

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Events List</h2>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">Create Event</a>
    </div>
    <div class="card">
        <div class="card-body">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Event Name</th>
                        <th>Cover Photo</th>
                        <th>Dates</th>
                        <th>Status</th>
                        <th>Ticket/Fees</th>
                        <th>Actions</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($events as $index => $event)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $event->name }}</td>
                            <td>
                                @if($event->cover_photo)
                                    <img src="{{ asset($event->cover_photo) }}" alt="Cover Photo" style="width: 80px; height: 50px; object-fit: cover;">
                                @else
                                    <span class="text-muted">No image</span>
                                @endif
                            </td>
                            <td>From: {{ $event->start_time }} <br> To:  {{ $event->end_time }}</td>
                            <td class="text-capitalize">{{ $event->status }}</td>
                            <td>
                                @if($event->fees && $event->fees->count())
                                    <ol class="mb-0 ps-3">
                                        @foreach($event->fees as $fee)
                                            <li>{{ $fee->fee_type }}: &#2547;{{ number_format($fee->fee_amount, 2) }}</li>
                                        @endforeach
                                    </ol>
                                @else
                                    <span class="text-muted">No fees</span>
                                @endif
                            </td>
                            <td>
                                <!-- Button trigger modal -->
                                <button type="button" class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#createFeeModal{{ $event->id }}">
                                    Create Fee
                                </button>

                                <!-- Modal -->
                                <div class="modal fade" id="createFeeModal{{ $event->id }}" tabindex="-1" aria-labelledby="createFeeModalLabel{{ $event->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('admin.fees.store') }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="createFeeModalLabel{{ $event->id }}">Create Fee for {{ $event->name }}</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <label for="fee_name_{{ $event->id }}" class="form-label">Fee Name</label>
                                                        <input type="text" class="form-control" id="fee_name_{{ $event->id }}" name="fee_type" required>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="fee_amount_{{ $event->id }}" class="form-label">Amount</label>
                                                        <input type="number" class="form-control" id="fee_amount_{{ $event->id }}" name="fee_amount" step="0.01" required>
                                                    </div>
                                                    <input type="hidden" name="event_id" value="{{ $event->id }}">
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-primary">Create Fee</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td>
                               <!-- <a href="{{ route('admin.events.show', $event->id) }}" class="btn btn-sm btn-info">View</a>
                                <a href="{{ route('admin.events.edit', $event->id) }}" class="btn btn-sm btn-warning">Edit</a>-->
                                <form action="{{ route('admin.events.destroy', $event->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>









@endsection
