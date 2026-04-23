@extends('admin.layouts.admin')
@section('title', 'Edit Form — ' . $form->title)

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
    #fields-container { min-height: 60px; }
    .builder-card { border: 2px dashed #dee2e6; border-radius: 14px; }
    .field-type-btn { cursor: pointer; border: 1px solid #dee2e6; border-radius: 8px; padding: 0.5rem 0.75rem;
        font-size: 0.8rem; transition: all 0.2s; text-align: center; }
    .field-type-btn:hover { border-color: #0d6efd; background: #e8f0ff; color: #0d6efd; }
    .required-star { color: #dc3545; }
</style>
@endpush

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0" style="color:#001f3f"><i class="fas fa-edit me-2"></i>Edit Form</h2>
        <nav aria-label="breadcrumb"><ol class="breadcrumb mb-0 small">
            <li class="breadcrumb-item"><a href="{{ route('admin.forms.index') }}">Form Builder</a></li>
            <li class="breadcrumb-item active">{{ $form->title }}</li>
        </ol></nav>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.forms.responses', $form) }}" class="btn btn-outline-info">
            <i class="fas fa-table me-1"></i> View Responses
        </a>
        <a href="{{ route('form.show', $form->slug) }}" target="_blank" class="btn btn-outline-secondary">
            <i class="fas fa-eye me-1"></i> Preview
        </a>
    </div>
</div>

<form method="POST" action="{{ route('admin.forms.update', $form) }}" id="formBuilderForm" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div class="row g-4">
        {{-- LEFT --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-dark text-white">
                    <i class="fas fa-info-circle me-2"></i>Form Details
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Form Title <span class="required-star">*</span></label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror"
                               value="{{ old('title', $form->title) }}" required>
                        @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" class="form-control" rows="2">{{ old('description', $form->description) }}</textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Cover Photo</label>
                        @if($form->cover_photo)
                            <div class="mb-2 position-relative">
                                <img src="{{ asset('storage/' . $form->cover_photo) }}" class="img-fluid rounded border" style="max-height: 100px">
                            </div>
                        @endif
                        <input type="file" name="cover_photo" class="form-control @error('cover_photo') is-invalid @enderror" accept="image/*">
                        <div class="form-text small text-muted">Leave empty to keep current. Max 2MB.</div>
                        @error('cover_photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-2">
                        <label class="form-label fw-semibold small text-muted">Public URL</label>
                        <div class="input-group input-group-sm">
                            <span class="input-group-text">/forms/</span>
                            <input type="text" class="form-control" value="{{ $form->slug }}" readonly>
                        </div>
                    </div>
                    <div class="form-check form-switch mt-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="isActive" value="1"
                               {{ old('is_active', $form->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Form is Active</label>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-success text-white">
                    <i class="fas fa-credit-card me-2"></i>Payment Settings
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" name="payment_required" id="paymentRequired"
                               value="1" {{ old('payment_required', $form->payment_required) ? 'checked' : '' }}
                               onchange="togglePayment(this.checked)">
                        <label class="form-check-label fw-semibold" for="paymentRequired">
                            Require Payment to Submit
                        </label>
                    </div>
                    <div id="paymentFields" style="{{ old('payment_required', $form->payment_required) ? '' : 'display:none' }}">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Amount <span class="required-star">*</span></label>
                            <div class="input-group">
                                <input type="number" name="payment_amount" id="paymentAmount"
                                       class="form-control @error('payment_amount') is-invalid @enderror"
                                       value="{{ old('payment_amount', $form->payment_amount) }}"
                                       min="0" step="0.01">
                                <select name="payment_currency" class="form-select" style="max-width:90px">
                                    <option value="BDT" {{ old('payment_currency', $form->payment_currency) == 'BDT' ? 'selected' : '' }}>BDT</option>
                                    <option value="USD" {{ old('payment_currency', $form->payment_currency) == 'USD' ? 'selected' : '' }}>USD</option>
                                </select>
                            </div>
                            @error('payment_amount')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                        </div>
                        <div class="alert alert-info small mb-0">
                            <i class="fas fa-info-circle me-1"></i> Payment via <strong>SSLCommerz</strong>.
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="fas fa-plus me-2"></i>Add Field
                </div>
                <div class="card-body">
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
                            ['image','Image Upload','fas fa-image'],
                            ['file','File Upload','fas fa-file-upload'],
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

        {{-- RIGHT --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-list me-2"></i>Form Fields</span>
                    <span class="badge bg-secondary" id="fieldCount">0 fields</span>
                </div>
                <div class="card-body">
                    <div id="fields-container" class="builder-card p-3">
                        <p id="emptyMsg" class="text-center text-muted py-4 mb-0" style="display:none">
                            <i class="fas fa-mouse-pointer me-2"></i>Click field types on the left to add fields
                        </p>
                    </div>
                </div>
                <div class="card-footer bg-white d-flex justify-content-between gap-2">
                    <a href="{{ route('admin.forms.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-save me-1"></i> Update Form
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

// Existing fields from the server
const existingFields = @json($form->fields);

window.addEventListener('DOMContentLoaded', () => {
    existingFields.forEach(f => {
        addFieldFromData(f.field_type, f.label, f.placeholder, f.options, f.is_required, f.validation_rules);
    });
    if (existingFields.length === 0) {
        document.getElementById('emptyMsg').style.display = '';
    }
});

function togglePayment(checked) {
    document.getElementById('paymentFields').style.display = checked ? '' : 'none';
    if (!checked) document.getElementById('paymentAmount').value = '';
}

function addFieldFromData(type, label, placeholder, options, required, validationRules) {
    const idx = fieldIndex++;
    const typeLabel = { text:'Text', email:'Email', number:'Number', tel:'Phone',
        date:'Date', textarea:'Textarea', select:'Dropdown', radio:'Radio', checkbox:'Checkbox',
        image: 'Image Upload', file: 'File Upload' }[type] || type;
    
    const needsOptions = ['select', 'radio', 'checkbox'].includes(type);
    const isFile = ['file', 'image'].includes(type);
    const optionsValue = options ? (Array.isArray(options) ? options.join(', ') : options) : '';

    const html = buildFieldHtml(idx, type, typeLabel, label, placeholder, optionsValue, required, validationRules);
    document.getElementById('fields-container').insertAdjacentHTML('beforeend', html);
    updateFieldCount();
}

function addField(type, typeLabel) {
    document.getElementById('emptyMsg').style.display = 'none';
    addFieldFromData(type, '', '', null, false, '');
}

function buildFieldHtml(idx, type, typeLabel, label, placeholder, optionsValue, isRequired, validationRules) {
    const needsOptions = ['select', 'radio', 'checkbox'].includes(type);
    const isFile = ['file', 'image'].includes(type);
    return `
    <div class="field-card" id="field-${idx}" draggable="true"
         ondragstart="dragStart(event)" ondragend="dragEnd(event)"
         ondragover="dragOver(event)" ondrop="dropField(event)">
        <div class="d-flex align-items-center gap-2 mb-2">
            <i class="fas fa-grip-vertical drag-handle"></i>
            <span class="badge bg-primary field-type-badge text-uppercase">${typeLabel}</span>
            <input type="hidden" name="fields[${idx}][field_type]" value="${type}">
            <div class="flex-grow-1">
                <input type="text" name="fields[${idx}][label]" class="form-control form-control-sm fw-semibold"
                       placeholder="Field label" value="${label || ''}" required>
            </div>
            <div class="form-check form-switch ms-2 mb-0" title="Required">
                <input class="form-check-input" type="checkbox"
                       name="fields[${idx}][is_required]" value="1" id="req-${idx}" ${isRequired ? 'checked' : ''}>
                <label class="form-check-label small text-muted" for="req-${idx}">Required</label>
            </div>
            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeField(${idx})">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="d-flex gap-2 align-items-center flex-wrap">
            ${type !== 'checkbox' && type !== 'radio' ? `
            <input type="text" name="fields[${idx}][placeholder]" class="form-control form-control-sm"
                   placeholder="Placeholder (optional)" style="max-width:260px" value="${placeholder || ''}">` : ''}
            ${needsOptions ? `
            <div class="flex-grow-1">
                <input type="text" name="fields[${idx}][options]" class="form-control form-control-sm"
                       placeholder="Options: comma separated" value="${optionsValue}">
            </div>` : ''}
            ${isFile ? `
            <div class="flex-grow-1">
                <input type="text" name="fields[${idx}][validation_rules]" class="form-control form-control-sm"
                       placeholder="Validation: e.g. mimes:pdf|max:5120" value="${validationRules || ''}">
            </div>` : ''}
        </div>
    </div>`;
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

let dragSrc = null;
function dragStart(e) { dragSrc = e.currentTarget; e.currentTarget.classList.add('dragging'); e.dataTransfer.effectAllowed = 'move'; }
function dragEnd(e) { e.currentTarget.classList.remove('dragging'); }
function dragOver(e) { e.preventDefault(); e.dataTransfer.dropEffect = 'move'; }
function dropField(e) {
    e.preventDefault();
    if (dragSrc && dragSrc !== e.currentTarget) {
        const parent = e.currentTarget.parentNode;
        const siblings = [...parent.children].filter(c => c.classList.contains('field-card'));
        const srcIdx = siblings.indexOf(dragSrc);
        const tgtIdx = siblings.indexOf(e.currentTarget);
        if (srcIdx < tgtIdx) e.currentTarget.after(dragSrc);
        else e.currentTarget.before(dragSrc);
    }
}

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
