@extends('admin.layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1>Participant Details</h1>
                    <p class="text-muted">Complete registration information for {{ $participant->name }}</p>
                </div>
                <div>
                    <a href="{{ route('admin.reports.participants') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to List
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Basic Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-primary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-user me-2"></i>Basic Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Participant ID:</th>
                            <td><strong class="text-primary fs-5">{{ $participant->participant_id ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Full Name:</th>
                            <td>{{ $participant->name }}</td>
                        </tr>
                        <tr>
                            <th>Email:</th>
                            <td>
                                <a href="mailto:{{ $participant->email }}">{{ $participant->email }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Phone:</th>
                            <td>
                                <a href="tel:{{ $participant->phone }}">{{ $participant->phone }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th>Emergency Contact:</th>
                            <td>{{ $participant->emergency_phone ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Gender:</th>
                            <td>{{ ucfirst($participant->gender ?? 'N/A') }}</td>
                        </tr>
                        <tr>
                            <th>Date of Birth:</th>
                            <td>
                                @if($participant->dob)
                                    {{ \Carbon\Carbon::parse($participant->dob)->format('F d, Y') }}
                                    ({{ \Carbon\Carbon::parse($participant->dob)->age }} years old)
                                @else
                                    N/A
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th>Nationality:</th>
                            <td>{{ $participant->nationality ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Event Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-success text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-calendar-alt me-2"></i>Event Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Event Name:</th>
                            <td><strong>{{ $participant->event->name ?? 'Event not found' }}</strong></td>
                        </tr>
                        <tr>
                            <th>Event Category:</th>
                            <td>{{ $participant->category ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Registration Type:</th>
                            <td>
                                <span class="badge bg-info">{{ ucfirst($participant->reg_type ?? 'N/A') }}</span>
                            </td>
                        </tr>
                        <tr>
                            <th>T-Shirt Size:</th>
                            <td>{{ $participant->tshirt_size ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Kit Option:</th>
                            <td>{{ $participant->kit_option ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Registration Fee:</th>
                            <td><strong>৳{{ number_format($participant->fee ?? 0, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <th>Registration Date:</th>
                            <td>{{ $participant->created_at->format('F d, Y g:i A') }}</td>
                        </tr>
                        <tr>
                            <th>Terms Agreed:</th>
                            <td>
                                @if($participant->terms_agreed)
                                    <span class="badge bg-success"><i class="fas fa-check"></i> Yes</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times"></i> No</span>
                                @endif
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Address Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-info text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-map-marker-alt me-2"></i>Address Information</h5>
                </div>
                <div class="card-body">
                    <table class="table table-borderless">
                        <tr>
                            <th width="40%">Full Address:</th>
                            <td>{{ $participant->address ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>Thana/Upazila:</th>
                            <td>{{ $participant->thana ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <th>District:</th>
                            <td>{{ $participant->district ?? 'N/A' }}</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Payment Information -->
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0"><i class="fas fa-credit-card me-2"></i>Payment Information</h5>
                </div>
                <div class="card-body">
                    @php
                        $completedTransactions = $participant->transactions->whereIn('status', ['complete', 'Complete']);
                        $totalPaid = $completedTransactions->sum('amount');
                        $hasCompletedTransaction = $completedTransactions->count() > 0;
                    @endphp

                    <div class="mb-3">
                        <h6>Payment Status:</h6>
                        @if($hasCompletedTransaction)
                            <span class="badge bg-success fs-6"><i class="fas fa-check-circle"></i> Paid</span>
                        @else
                            <span class="badge bg-warning fs-6"><i class="fas fa-clock"></i> Pending</span>
                        @endif
                    </div>

                    <div class="mb-3">
                        <h6>Total Amount Paid:</h6>
                        <strong class="fs-5 text-success">৳{{ number_format($totalPaid, 2) }}</strong>
                    </div>

                    @if($participant->transactions->count() > 0)
                        <h6>Transaction History:</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-striped">
                                <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Method</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($participant->transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction->created_at->format('M d, Y') }}</td>
                                        <td>৳{{ number_format($transaction->amount, 2) }}</td>
                                        <td>
                                            @if(in_array($transaction->status, ['complete', 'Complete']))
                                                <span class="badge bg-success">{{ ucfirst($transaction->status) }}</span>
                                            @elseif(in_array($transaction->status, ['pending', 'Pending']))
                                                <span class="badge bg-warning">{{ ucfirst($transaction->status) }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ ucfirst($transaction->status) }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $transaction->payment_method ?? 'N/A' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted">No transactions recorded yet.</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Additional Registration Fields -->
        @if($participant->additional_data && count($participant->additional_data) > 0)
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-secondary text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-list-ul me-2"></i>Additional Registration Fields</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @foreach($participant->additional_data as $key => $value)
                        <div class="col-md-6 mb-3">
                            <div class="border-bottom pb-2">
                                <strong class="text-muted">{{ ucwords(str_replace('_', ' ', $key)) }}:</strong>
                                <div class="mt-1">
                                    @if(is_array($value))
                                        @foreach($value as $item)
                                            <span class="badge bg-primary me-1">{{ $item }}</span>
                                        @endforeach
                                    @else
                                        <span class="fs-6">{{ $value }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- System Information -->
        <div class="col-md-12 mb-4">
            <div class="card">
                <div class="card-header bg-dark text-white">
                    <h5 class="card-title mb-0"><i class="fas fa-database me-2"></i>System Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <strong>Database ID:</strong> {{ $participant->id }}
                        </div>
                        <div class="col-md-4">
                            <strong>Created At:</strong> {{ $participant->created_at->format('Y-m-d H:i:s') }}
                        </div>
                        <div class="col-md-4">
                            <strong>Last Updated:</strong> {{ $participant->updated_at->format('Y-m-d H:i:s') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="d-flex gap-2">
                <a href="{{ route('admin.reports.participants') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Back to List
                </a>
                @if($participant->email)
                <a href="mailto:{{ $participant->email }}" class="btn btn-primary">
                    <i class="fas fa-envelope me-2"></i>Send Email
                </a>
                @endif
                @if($participant->phone)
                <a href="tel:{{ $participant->phone }}" class="btn btn-success">
                    <i class="fas fa-phone me-2"></i>Call Participant
                </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
