@extends('admin.layouts.admin')
@section('title', 'Create New Form')

@push('styles')
<style>
    .field-card {
        background: #fff;
        border: 1px solid #dee2e6;
        border-radius: 10px;
        padding: 1rem 1.25rem;
        margin-bottom: 0.75rem;
        cursor: grab;
        transition: box-shadow 0.2s, transform 0.15s;
    }
    .field-card:active { cursor: grabbing; }
    .field-card.dragging { box-shadow: 0 8px 24px rgba(0,0,0,0.15); transform: scale(1.01); opacity: 0.85; }
    .field-card .drag-handle { color: #adb5bd; cursor: grab; font-size: 1.1rem; }
    .field-type-badge { font-size: 0.7rem; padding: 0.2rem 0.5rem; }
    .options-row { display: none; }
    .options-row.show { display: flex; }
    #fields-container { min-height: 60px; }
    #preview-panel .preview-field label { font-weight: 600; font-size: 0.9rem; }
    #preview-panel .preview-field { margin-bottom: 1rem; }
    .payment-section { transition: all 0.3s; }
    .builder-card { border: 2px dashed #dee2e6; border-radius: 14px; }
    .builder-card.drag-over { border-color: #0d6efd; background: #f0f4ff; }
    .field-type-btn { cursor: pointer; border: 1px solid #dee2e6; border-radius: 8px; padding: 0.5rem 0.75rem;
        font-size: 0.8rem; transition: all 0.2s; text-align: center; }
    .field-type-btn:hover { border-color: #0d6efd; background: #e8f0ff; color: #0d6efd; }
    .required-star { color: #dc3545; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0" style="color:#001f3f"><i class="fas fa-plus-circle me-2"></i>Create New Form</h2>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('admin.forms.index') }}">Form Builder</a></li>
            <li class="breadcrumb-item active">Create</li>
        </ol></nav>
    </div>
</div>

<form method="POST" action="{{ route('admin.forms.store') }}" id="formBuilderForm">
    @csrf
    <div class="row g-4">
        {{-- LEFT: Form Settings --}}
        <div class="col-lg-4">
            {{-- Basic Info --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-info-circle me-2"></i>Form Details
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Form Title <span class="required-star">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title') }}" placeholder="e.g. Event Registration Survey" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="3"
                                  placeholder="Brief description shown to respondents">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                               {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Form is Active</label>
                    </div>
                </div>
            </div>

            {{-- Payment Settings --}}
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-credit-card me-2"></i>Payment Settings
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="payment_required" id="paymentRequired"
                               value="1" {{ old('payment_required') ? 'checked' : '' }}
                               onchange="togglePayment(this.checked)">
                        <label class="form-check-label fw-semibold" for="paymentRequired">
                            Require Payment to Submit
                        </label>
                    </div>
                    <div id="paymentFields" style="{{ old('payment_required') ? '' : 'display:none' }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount <span class="required-star">*</span></label>
                            <div class="input-group">
                                <input type="number" name="payment_amount" id="paymentAmount"
                                       class="form-control @error('payment_amount') is-invalid @enderror"
                                       value="{{ old('payment_amount') }}" min="0" step="0.01" placeholder="0.00">
                                <select name="payment_currency" class="form-select" style="max-width:90px">
                                    <option value="BDT" {{ old('payment_currency','BDT')=='BDT'?'selected':'' }}>BDT</option>
                                    <option value="USD" {{ old('payment_currency')=='USD'?'selected':'' }}>USD</option>
                                </select>
                            </div>
                            @error('payment_amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="alert alert-info small mb-0">
                            <i class="fas fa-info-circle me-1"></i>
                            Payment is processed via <strong>SSLCommerz</strong>. Respondents will be redirected after filling the form.
                        </div>
                    </div>
                </div>
            </div>

            {{-- Field Type Picker --}}
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-plus me-2"></i>Add Field
                </div>
                <div class="card-body">
                    <p class="text-muted small mb-2">Click to add a field to your form:</p>
                    <div class="row g-2">
                        @foreach([
                            ['text','Text','fas fa-font'],
                            ['email','Email','fas fa-envelope'],
                            ['number','Number','fas fa-hashtag'],
                            ['tel','Phone','fas fa-phone'],
                            ['date','Date','fas fa-calendar'],
                            ['textarea','Textarea','fas fa-align-left'],
                            ['select','Dropdown','fas fa-caret-square-down'],
                            ['radio','Radio','fas fa-dot-circle'],
                            ['checkbox','Checkbox','fas fa-check-square'],
                        ] as [$type, $label, $icon])
                        <div class="col-6">
                            <div class="field-type-btn" onclick="addField('{{ $type }}', '{{ $label }}')">
                                <i class="{{ $icon }} me-1"></i>{{ $label }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Field Builder + Preview --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list me-2"></i>Form Fields</span>
                    <span class="badge bg-secondary" id="fieldCount">0 fields</span>
                </div>
                <div class="card-body">
                    <div id="fields-container" class="builder-card p-3">
                        <p id="emptyMsg" class="text-center text-muted py-4 mb-0">
                            <i class="fas fa-mouse-pointer me-2"></i>Click field types on the left to start building your form
                        </p>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-end gap-2">
                    <a href="{{ route('admin.forms.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Save Form
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
let fieldIndex = 0;

function togglePayment(checked) {
    document.getElementById('paymentFields').style.display = checked ? '' : 'none';
    if (!checked) document.getElementById('paymentAmount').value = '';
}

function addField(type, typeLabel) {
    const idx = fieldIndex++;
    document.getElementById('emptyMsg').style.display = 'none';

    const needsOptions = ['select', 'radio', 'checkbox'].includes(type);
    const html = `
    <div class="field-card" id="field-${idx}" draggable="true"
         ondragstart="dragStart(event)" ondragend="dragEnd(event)"
         ondragover="dragOver(event)" ondrop="dropField(event)">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="fas fa-grip-vertical drag-handle"></i>
            <span class="badge bg-primary field-type-badge text-uppercase">${typeLabel}</span>
            <input type="hidden" name="fields[${idx}][field_type]" value="${type}">
            <div class="flex-grow-1">
                <input type="text" name="fields[${idx}][label]" class="form-control form-control-sm fw-semibold"
                       placeholder="Field label" required>
            </div>
            <div class="form-check form-switch ms-2 mb-0" title="Required">
                <input class="form-check-input" type="checkbox"
                       name="fields[${idx}][is_required]" value="1" id="req-${idx}">
                <label class="form-check-label small text-muted" for="req-${idx}">Required</label>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeField(${idx})">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            ${type !== 'checkbox' && type !== 'radio' ? `
            <input type="text" name="fields[${idx}][placeholder]" class="form-control form-control-sm"
                   placeholder="Placeholder text (optional)" style="max-width:260px">` : ''}
            ${needsOptions ? `
            <div class="flex-grow-1">
                <input type="text" name="fields[${idx}][options]" class="form-control form-control-sm"
                       placeholder="Options: comma separated (e.g. Yes, No, Maybe)">
            </div>` : ''}
        </div>
    </div>`;

    document.getElementById('fields-container').insertAdjacentHTML('beforeend', html);
    updateFieldCount();
}

function removeField(idx) {
    document.getElementById('field-' + idx).remove();
    updateFieldCount();
    if (document.querySelectorAll('.field-card').length === 0) {
        document.getElementById('emptyMsg').style.display = '';
    }
}

function updateFieldCount() {
    const count = document.querySelectorAll('.field-card').length;
    document.getElementById('fieldCount').textContent = count + ' field' + (count !== 1 ? 's' : '');
}

// ── Drag & Drop reordering ──────────────────────────────────────
let dragSrc = null;
function dragStart(e) {
    dragSrc = e.currentTarget;
    e.currentTarget.classList.add('dragging');
    e.dataTransfer.effectAllowed = 'move';
}
function dragEnd(e) { e.currentTarget.classList.remove('dragging'); }
function dragOver(e) { e.preventDefault(); e.dataTransfer.dropEffect = 'move'; }
function dropField(e) {
    e.preventDefault();
    if (dragSrc && dragSrc !== e.currentTarget) {
        const parent = e.currentTarget.parentNode;
        const siblings = [...parent.children].filter(c => c.classList.contains('field-card'));
        const srcIdx = siblings.indexOf(dragSrc);
        const tgtIdx = siblings.indexOf(e.currentTarget);
        if (srcIdx < tgtIdx) {
            e.currentTarget.after(dragSrc);
        } else {
            e.currentTarget.before(dragSrc);
        }
    }
}

// Client-side validation before submit
document.getElementById('formBuilderForm').addEventListener('submit', function(e) {
    const payReq = document.getElementById('paymentRequired').checked;
    const payAmt = document.getElementById('paymentAmount').value;
    if (payReq && (!payAmt || parseFloat(payAmt) <= 0)) {
        e.preventDefault();
        alert('Please enter a valid payment amount.');
        document.getElementById('paymentAmount').focus();
    }
});
</script>
@endpush
