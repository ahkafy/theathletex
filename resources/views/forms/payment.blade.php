@extends('layouts.app')
@section('title', 'Complete Payment — ' . $form->title)

@push('styles')
<style>
    body { background: linear-gradient(135deg, #001f3f 0%, #003366 100%); min-height: 100vh; }
    .payment-wrapper { max-width: 560px; margin: 0 auto; padding: 3rem 1rem; }
    .payment-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        overflow: hidden;
    }
    .payment-header {
        background: linear-gradient(135deg, #001f3f, #0055a5);
        color: white;
        padding: 2rem;
        text-align: center;
    }
    .payment-header .amount-badge {
        display: inline-block;
        background: rgba(255,255,255,0.15);
        border: 2px solid rgba(255,255,255,0.4);
        border-radius: 50px;
        padding: 0.75rem 2rem;
        font-size: 2rem;
        font-weight: 800;
        letter-spacing: 1px;
        margin-top: 0.5rem;
    }
    .payment-body { padding: 2rem; }
    .info-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.65rem 0;
        border-bottom: 1px solid #f0f0f0;
        font-size: 0.9rem;
    }
    .info-row:last-child { border-bottom: none; }
    .info-row .label { color: #6c757d; }
    .info-row .value { font-weight: 600; color: #222; }
    .btn-pay {
        background: linear-gradient(135deg, #28a745, #20c997);
        border: none;
        color: white;
        padding: 1rem 2rem;
        border-radius: 50px;
        font-weight: 700;
        font-size: 1.1rem;
        width: 100%;
        margin-top: 1.5rem;
        transition: all 0.3s;
        cursor: pointer;
        letter-spacing: 0.5px;
    }
    .btn-pay:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(40,167,69,0.35); }
    .btn-pay:disabled { opacity: 0.7; cursor: not-allowed; transform: none; box-shadow: none; }
    .ssl-badge {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 0.78rem;
        color: #6c757d;
        justify-content: center;
        margin-top: 1rem;
    }
    .status-badge {
        padding: 0.3rem 0.8rem;
        border-radius: 50px;
        font-size: 0.78rem;
        font-weight: 600;
    }
    .spinner-border-sm { width: 1rem; height: 1rem; }
</style>
@endpush

@section('content')
<div class="payment-wrapper">

    {{-- Error / cancellation notice --}}
    @if(session('error'))
    <div class="alert alert-danger rounded-3 mb-3 d-flex align-items-center gap-2">
        <i class="fas fa-exclamation-circle"></i>
        {{ session('error') }}
    </div>
    @endif

    <div class="payment-card">
        {{-- Header --}}
        <div class="payment-header">
            <p class="mb-1 opacity-75 small text-uppercase letter-spacing-1">Payment Required</p>
            <h2 class="fw-bold h4 mb-1">{{ $form->title }}</h2>
            <div class="amount-badge">
                {{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}
            </div>
            <p class="mt-2 mb-0 opacity-75 small">
                <i class="fas fa-shield-alt me-1"></i>Secured by SSLCommerz
            </p>
        </div>

        <div class="payment-body">
            {{-- Payment Status --}}
            @if($transaction->status === 'failed')
            <div class="alert alert-warning rounded-3 mb-3">
                <i class="fas fa-redo me-2"></i>
                <strong>Previous attempt failed.</strong> Please try again below.
            </div>
            @elseif($transaction->status === 'cancelled')
            <div class="alert alert-info rounded-3 mb-3">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Payment was cancelled.</strong> You can try again below.
            </div>
            @endif

            {{-- Summary --}}
            <h6 class="fw-bold mb-3" style="color:#001f3f">
                <i class="fas fa-receipt me-2"></i>Payment Summary
            </h6>
            <div class="mb-4">
                <div class="info-row">
                    <span class="label">Form</span>
                    <span class="value">{{ $form->title }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Respondent</span>
                    <span class="value">{{ $response->respondent_name }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Email</span>
                    <span class="value">{{ $response->respondent_email }}</span>
                </div>
                @if($response->respondent_phone)
                <div class="info-row">
                    <span class="label">Phone</span>
                    <span class="value">{{ $response->respondent_phone }}</span>
                </div>
                @endif
                <div class="info-row">
                    <span class="label">Amount</span>
                    <span class="value text-success fw-bold">
                        {{ $transaction->currency }} {{ number_format($transaction->amount, 2) }}
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Status</span>
                    <span>
                        @if($transaction->status === 'pending')
                            <span class="status-badge bg-warning text-dark">Pending</span>
                        @elseif($transaction->status === 'failed')
                            <span class="status-badge bg-danger text-white">Failed</span>
                        @elseif($transaction->status === 'cancelled')
                            <span class="status-badge bg-secondary text-white">Cancelled</span>
                        @else
                            <span class="status-badge bg-secondary text-white">{{ ucfirst($transaction->status) }}</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="label">Reference</span>
                    <span class="value font-monospace small text-muted">{{ $transaction->ssl_tran_id }}</span>
                </div>
            </div>

            {{-- Pay button POSTs to initiate which calls makePayment() and redirects to SSL gateway --}}
            <form method="POST" action="{{ route('form.payment.initiate') }}" id="paymentForm">
                @csrf
                <input type="hidden" name="transaction_id" value="{{ $transaction->id }}">

                <button type="submit" class="btn-pay" id="payBtn">
                    <i class="fas fa-lock me-2"></i>
                    Pay {{ $transaction->currency }} {{ number_format($transaction->amount, 2) }} via SSLCommerz
                </button>

                <div class="ssl-badge">
                    <i class="fas fa-shield-alt text-success"></i>
                    Your payment is 100% secure and encrypted
                </div>
            </form>

            <hr class="my-3">
            <div class="text-center">
                <a href="{{ route('form.show', $form->slug) }}" class="text-muted small text-decoration-none">
                    <i class="fas fa-arrow-left me-1"></i>Back to form
                </a>
            </div>
        </div>
    </div>

    <p class="text-center text-white-50 small mt-3">
        <i class="fas fa-lock me-1"></i>
        Your payment is processed securely by SSLCommerz. We never store your card details.
    </p>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('paymentForm').addEventListener('submit', function() {
    const btn = document.getElementById('payBtn');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Redirecting to payment gateway…';
});
</script>
@endpush
