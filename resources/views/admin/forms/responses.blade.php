@extends('admin.layouts.admin')
@section('title', 'Responses — ' . $form->title)

@push('styles')
<style>
    .stat-card { border-radius: 12px; border: none; }
    .response-badge { font-size: 0.75rem; }
    .filter-card { background: #f8f9fa; border: 1px solid #dee2e6; border-radius: 10px; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="fw-bold mb-0" style="color:#001f3f">
            <i class="fas fa-table me-2"></i>{{ $form->title }} — Responses
        </h2>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('admin.forms.index') }}">Form Builder</a></li>
            <li class="breadcrumb-item active">Responses</li>
        </ol></nav>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('form.show', $form->slug) }}" target="_blank" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-eye me-1"></i>Preview Form
        </a>
        <a href="{{ route('admin.forms.edit', $form) }}" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-edit me-1"></i>Edit Form
        </a>
        <a href="{{ route('admin.forms.responses.export', $form) }}" class="btn btn-success btn-sm">
            <i class="fas fa-file-csv me-1"></i>Export CSV
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm bg-primary text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ $stats['total'] }}</div>
                    <div class="small opacity-75">Total Responses</div>
                </div>
                <i class="fas fa-inbox fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    @if($form->payment_required)
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm bg-success text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ $stats['paid'] }}</div>
                    <div class="small opacity-75">Paid</div>
                </div>
                <i class="fas fa-check-circle fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm bg-warning text-dark">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">{{ $stats['pending'] }}</div>
                    <div class="small opacity-75">Pending Payment</div>
                </div>
                <i class="fas fa-clock fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm bg-secondary text-white">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-2 fw-bold">
                        {{ number_format($form->payment_amount * $stats['paid'], 2) }}
                        <small class="fs-6">{{ $form->payment_currency }}</small>
                    </div>
                    <div class="small opacity-75">Total Collected</div>
                </div>
                <i class="fas fa-taka-sign fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    @else
    <div class="col-sm-6 col-lg-3">
        <div class="card stat-card shadow-sm bg-light border">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="fs-5 fw-bold text-success">Free Form</div>
                    <div class="small text-muted">No payment required</div>
                </div>
                <i class="fas fa-gift fa-2x text-success opacity-50"></i>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Filters --}}
<div class="filter-card p-3 mb-4">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3">
            <label class="form-label small fw-semibold mb-1">Search</label>
            <input type="text" name="search" class="form-control form-control-sm"
                   placeholder="Name, email, phone…" value="{{ request('search') }}">
        </div>
        @if($form->payment_required)
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">Payment Status</label>
            <select name="payment_status" class="form-select form-select-sm">
                <option value="">All</option>
                <option value="complete" {{ request('payment_status') == 'complete' ? 'selected' : '' }}>Paid</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        @endif
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">From</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small fw-semibold mb-1">To</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i>Filter</button>
            <a href="{{ route('admin.forms.responses', $form) }}" class="btn btn-outline-secondary btn-sm">Clear</a>
        </div>
    </form>
</div>

{{-- Table --}}
@if($responses->isEmpty())
<div class="card border-0 shadow-sm text-center py-5">
    <div class="card-body">
        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
        <h5 class="text-muted">No responses found</h5>
        <p class="text-muted small">Responses will appear here once the form is submitted.</p>
    </div>
</div>
@else
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Respondent</th>
                    <th>Submitted</th>
                    @if($form->payment_required)
                    <th>Payment</th>
                    <th>Amount</th>
                    <th>Method</th>
                    @endif
                    @foreach($form->fields as $field)
                    <th>{{ Str::limit($field->label, 20) }}</th>
                    @endforeach
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($responses as $i => $response)
                <tr>
                    <td class="text-muted">{{ $responses->firstItem() + $i }}</td>
                    <td>
                        <strong>{{ $response->respondent_name }}</strong><br>
                        <small class="text-muted">{{ $response->respondent_email }}</small>
                        @if($response->respondent_phone)
                        <br><small class="text-muted">{{ $response->respondent_phone }}</small>
                        @endif
                    </td>
                    <td class="small text-muted">{{ $response->created_at->format('d M Y, H:i') }}</td>
                    @if($form->payment_required)
                    <td>
                        @php $status = $response->payment_status; @endphp
                        @if($status === 'complete')
                            <span class="badge bg-success">Paid</span>
                        @elseif($status === 'pending')
                            <span class="badge bg-warning text-dark">Pending</span>
                        @elseif($status === 'failed')
                            <span class="badge bg-danger">Failed</span>
                        @else
                            <span class="badge bg-secondary">{{ ucfirst($status) }}</span>
                        @endif
                    </td>
                    <td>
                        @if($response->transaction)
                            {{ number_format($response->transaction->amount, 2) }}
                            {{ $response->transaction->currency }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td class="small">
                        @if($response->transaction && $response->transaction->payment_method)
                            {{ $response->transaction->payment_method }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    @endif
                    @foreach($form->fields as $field)
                    <td>
                        @php $val = $response->response_data[$field->id] ?? null; @endphp
                        @if(is_array($val))
                            {{ implode(', ', $val) }}
                        @elseif($val)
                            {{ Str::limit($val, 40) }}
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    @endforeach
                    <td>
                        <button class="btn btn-sm btn-outline-info" onclick="showDetail({{ $response->id }})">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @if($responses->hasPages())
    <div class="card-footer bg-white d-flex justify-content-center">
        {{ $responses->links() }}
    </div>
    @endif
</div>

{{-- Response Detail Modals --}}
@foreach($responses as $response)
<div class="modal fade" id="detail-{{ $response->id }}" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user me-2"></i>{{ $response->respondent_name }}
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-4"><strong>Email:</strong><br>{{ $response->respondent_email }}</div>
                    <div class="col-md-4"><strong>Phone:</strong><br>{{ $response->respondent_phone ?? '—' }}</div>
                    <div class="col-md-4"><strong>Submitted:</strong><br>{{ $response->created_at->format('d M Y H:i') }}</div>
                </div>

                @if($form->payment_required)
                <div class="card mb-3 border-{{ $response->payment_status === 'complete' ? 'success' : ($response->payment_status === 'pending' ? 'warning' : 'danger') }}">
                    <div class="card-header bg-{{ $response->payment_status === 'complete' ? 'success' : ($response->payment_status === 'pending' ? 'warning' : 'danger') }}
                        {{ $response->payment_status !== 'pending' ? 'text-white' : '' }}">
                        <i class="fas fa-credit-card me-2"></i>Payment Information
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"><strong>Status:</strong><br>
                                <span class="badge bg-{{ $response->payment_status === 'complete' ? 'success' : ($response->payment_status === 'pending' ? 'warning text-dark' : 'danger') }}">
                                    {{ ucfirst($response->payment_status) }}
                                </span>
                            </div>
                            @if($response->transaction)
                            <div class="col-md-3"><strong>Amount:</strong><br>
                                {{ number_format($response->transaction->amount, 2) }} {{ $response->transaction->currency }}
                            </div>
                            <div class="col-md-3"><strong>Method:</strong><br>
                                {{ $response->transaction->payment_method ?? '—' }}
                            </div>
                            <div class="col-md-3"><strong>Bank TXN:</strong><br>
                                <code class="small">{{ $response->transaction->bank_tran_id ?? '—' }}</code>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                <h6 class="fw-bold mb-3"><i class="fas fa-list me-2"></i>Form Responses</h6>
                <div class="table-responsive">
                    <table class="table table-sm table-bordered">
                        <thead class="table-light">
                            <tr><th>Field</th><th>Response</th></tr>
                        </thead>
                        <tbody>
                            @foreach($form->fields as $field)
                            @php $val = $response->response_data[$field->id] ?? null; @endphp
                            <tr>
                                <td class="fw-semibold" style="width:35%">{{ $field->label }}</td>
                                <td>
                                    @if(is_array($val))
                                        {{ implode(', ', $val) }}
                                    @elseif($val)
                                        {{ $val }}
                                    @else
                                        <em class="text-muted">No response</em>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif
@endsection

@push('scripts')
<script>
function showDetail(id) {
    const modal = new bootstrap.Modal(document.getElementById('detail-' + id));
    modal.show();
}
</script>
@endpush
