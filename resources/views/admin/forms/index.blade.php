@extends('admin.layouts.admin')
@section('title', 'Form Builder — All Forms')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0" style="color:#001f3f"><i class="fas fa-wpforms me-2"></i>Form Builder</h2>
        <p class="text-muted mb-0">Create and manage custom forms with optional payment</p>
    </div>
    <a href="{{ route('admin.forms.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-1"></i> New Form
    </a>
</div>

@if($forms->isEmpty())
    <div class="card border-0 shadow-sm text-center py-5">
        <div class="card-body">
            <i class="fas fa-wpforms fa-3x text-muted mb-3"></i>
            <h5 class="text-muted">No forms yet</h5>
            <p class="text-muted">Click "New Form" to create your first form.</p>
            <a href="{{ route('admin.forms.create') }}" class="btn btn-primary mt-2">
                <i class="fas fa-plus me-1"></i> Create Form
            </a>
        </div>
    </div>
@else
<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Fields</th>
                    <th>Responses</th>
                    <th>Payment</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($forms as $i => $form)
                <tr>
                    <td class="text-muted">{{ $i + 1 }}</td>
                    <td>
                        <strong>{{ $form->title }}</strong><br>
                        <small class="text-muted font-monospace">/forms/{{ $form->slug }}</small>
                    </td>
                    <td><span class="badge bg-secondary">{{ $form->fields_count }}</span></td>
                    <td>
                        <a href="{{ route('admin.forms.responses', $form) }}" class="text-decoration-none">
                            <span class="badge bg-info text-dark">{{ $form->responses_count }}</span>
                        </a>
                    </td>
                    <td>
                        @if($form->payment_required)
                            <span class="badge bg-success">
                                <i class="fas fa-credit-card me-1"></i>
                                {{ number_format($form->payment_amount, 2) }} {{ $form->payment_currency }}
                            </span>
                        @else
                            <span class="badge bg-light text-muted border">Free</span>
                        @endif
                    </td>
                    <td>
                        @if($form->is_active)
                            <span class="badge bg-success">Active</span>
                        @else
                            <span class="badge bg-danger">Inactive</span>
                        @endif
                    </td>
                    <td class="text-muted small">{{ $form->created_at->format('d M Y') }}</td>
                    <td>
                        <div class="d-flex gap-1 flex-wrap">
                            <a href="{{ route('form.show', $form->slug) }}" target="_blank"
                               class="btn btn-sm btn-outline-secondary" title="Preview">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="{{ route('admin.forms.responses', $form) }}"
                               class="btn btn-sm btn-outline-info" title="Responses">
                                <i class="fas fa-table"></i>
                            </a>
                            <a href="{{ route('admin.forms.edit', $form) }}"
                               class="btn btn-sm btn-outline-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.forms.destroy', $form) }}"
                                  onsubmit="return confirm('Delete this form and all its responses?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" title="Delete">
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
</div>
@endif
@endsection
