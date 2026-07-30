@extends('admin.layouts.admin')

@section('title', 'Test SMS & Email Notifications')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-vial text-primary me-2"></i>Test Notifications (SMS & Email)
            </h1>
            <p class="text-muted small mb-0">Temporary testing suite for participant payment confirmation SMS & Email.</p>
        </div>
    </div>

    @if(session('sms_success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-check-circle me-2"></i><strong>SMS Sent:</strong> {{ session('sms_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('sms_error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i><strong>SMS Error:</strong> {{ session('sms_error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('email_success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-envelope-circle-check me-2"></i><strong>Email Sent:</strong> {{ session('email_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('email_error'))
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <i class="fas fa-triangle-exclamation me-2"></i><strong>Email Error:</strong> {{ session('email_error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('last_sms_sent'))
        <div class="card mb-4 border-info">
            <div class="card-header bg-info text-white">
                <i class="fas fa-comment-dots me-2"></i>Exact SMS Body Dispatched
            </div>
            <div class="card-body">
                <code class="fs-6 text-dark d-block bg-light p-3 rounded">{{ session('last_sms_sent') }}</code>
            </div>
        </div>
    @endif

    @if(count($latestTransactions) > 0)
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-body bg-light rounded">
                <label for="transaction_picker" class="form-label fw-bold text-secondary">
                    <i class="fas fa-magic me-1"></i> Auto-fill test fields using an existing transaction:
                </label>
                <select id="transaction_picker" class="form-select form-select-sm">
                    <option value="">-- Choose a recent completed transaction --</option>
                    @foreach($latestTransactions as $trx)
                        <option value="{{ $trx->id }}"
                                data-name="{{ $trx->participant->name ?? '' }}"
                                data-email="{{ $trx->participant->email ?? '' }}"
                                data-phone="{{ $trx->participant->phone ?? '' }}"
                                data-event="{{ $trx->event->name ?? '' }}"
                                data-order="{{ $trx->participant->participant_id ?? $trx->id }}"
                                data-category="{{ $trx->participant->category ?? '' }}"
                                data-tshirt="{{ $trx->participant->tshirt_size ?? '' }}"
                                data-gender="{{ $trx->participant->gender ?? '' }}">
                            Trx #{{ $trx->id }} - {{ $trx->participant->name ?? 'N/A' }} ({{ $trx->event->name ?? 'N/A' }}) - {{ $trx->amount }} BDT
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
    @endif

    <div class="row">
        <!-- TEST SMS PANEL -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-sms me-2"></i>Test Confirmation SMS</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.test-notifications.send-sms') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-bold">Target Mobile Number *</label>
                            <input type="text" name="phone" id="sms_phone" class="form-control" placeholder="e.g. 01711000000" value="{{ old('phone', '01711000000') }}" required>
                            <div class="form-text">Will send via BulksmsBD API</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Participant Name</label>
                            <input type="text" name="name" id="sms_name" class="form-control" value="{{ old('name', 'K M Yeasir Arafat Omi') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Event Name</label>
                            <input type="text" name="event_name" id="sms_event_name" class="form-control" value="{{ old('event_name', 'BYLC Running with Purpose 2026') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Order / Participant ID</label>
                                <input type="text" name="order_id" id="sms_order_id" class="form-control" value="{{ old('order_id', '15K00304') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <input type="text" name="category" id="sms_category" class="form-control" value="{{ old('category', '15KM') }}" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label text-muted small fw-bold">Live SMS Preview:</label>
                            <div class="p-3 bg-light rounded border border-dashed" id="sms_preview" style="font-family: monospace; font-size: 0.9rem; min-height: 80px;">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="fas fa-paper-plane me-2"></i>Send Test SMS
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- TEST EMAIL PANEL -->
        <div class="col-lg-6 mb-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-header bg-dark text-white py-3">
                    <h5 class="card-title mb-0"><i class="fas fa-envelope me-2"></i>Test Confirmation Email</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.test-notifications.send-email') }}" method="POST">
                        @csrf
                        <input type="hidden" name="transaction_id" id="email_transaction_id" value="{{ old('transaction_id') }}">

                        <div class="mb-3">
                            <label class="form-label fw-bold">Target Recipient Email *</label>
                            <input type="email" name="email" id="email_recipient" class="form-control" placeholder="e.g. yourname@gmail.com" value="{{ old('email') }}" required>
                            <div class="form-text">Will trigger Mail::to()->send(new PaymentConfirmation)</div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Participant Name</label>
                            <input type="text" name="name" id="email_name" class="form-control" value="{{ old('name', 'K M Yeasir Arafat Omi') }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Event Name</label>
                            <input type="text" name="event_name" id="email_event_name" class="form-control" value="{{ old('event_name', 'BYLC Running with Purpose 2026') }}" required>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Order / Participant ID</label>
                                <input type="text" name="order_id" id="email_order_id" class="form-control" value="{{ old('order_id', '15K00304') }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Category</label>
                                <input type="text" name="category" id="email_category" class="form-control" value="{{ old('category', '15KM') }}" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">T-Shirt Size</label>
                                <input type="text" name="tshirt_size" id="email_tshirt_size" class="form-control" value="{{ old('tshirt_size', 'XL (C-44, L-30)') }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Gender</label>
                                <input type="text" name="gender" id="email_gender" class="form-control" value="{{ old('gender', 'Male') }}">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-2">
                            <i class="fas fa-paper-plane me-2"></i>Send Test Email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nameInput = document.getElementById('sms_name');
    const eventInput = document.getElementById('sms_event_name');
    const orderInput = document.getElementById('sms_order_id');
    const categoryInput = document.getElementById('sms_category');
    const previewDiv = document.getElementById('sms_preview');

    function updateSmsPreview() {
        const name = nameInput.value.trim();
        const eventName = eventInput.value.trim();
        const orderId = orderInput.value.trim();
        const category = categoryInput.value.trim();
        
        previewDiv.textContent = `Dear ${name} your registration for ${eventName} is confirmed! Order # ${orderId} Category: ${category}. Best regards, The Athlete X`;
    }

    [nameInput, eventInput, orderInput, categoryInput].forEach(el => {
        el.addEventListener('input', updateSmsPreview);
    });
    updateSmsPreview();

    // Auto-fill from transaction selector
    const picker = document.getElementById('transaction_picker');
    if (picker) {
        picker.addEventListener('change', function () {
            const opt = this.options[this.selectedIndex];
            if (!opt.value) return;

            document.getElementById('email_transaction_id').value = opt.value;
            
            if (opt.dataset.phone) document.getElementById('sms_phone').value = opt.dataset.phone;
            if (opt.dataset.name) {
                document.getElementById('sms_name').value = opt.dataset.name;
                document.getElementById('email_name').value = opt.dataset.name;
            }
            if (opt.dataset.email) document.getElementById('email_recipient').value = opt.dataset.email;
            if (opt.dataset.event) {
                document.getElementById('sms_event_name').value = opt.dataset.event;
                document.getElementById('email_event_name').value = opt.dataset.event;
            }
            if (opt.dataset.order) {
                document.getElementById('sms_order_id').value = opt.dataset.order;
                document.getElementById('email_order_id').value = opt.dataset.order;
            }
            if (opt.dataset.category) {
                document.getElementById('sms_category').value = opt.dataset.category;
                document.getElementById('email_category').value = opt.dataset.category;
            }
            if (opt.dataset.tshirt) document.getElementById('email_tshirt_size').value = opt.dataset.tshirt;
            if (opt.dataset.gender) document.getElementById('email_gender').value = opt.dataset.gender;

            updateSmsPreview();
        });
    }
});
</script>
@endpush
@endsection
