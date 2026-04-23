@extends('layouts.app')
@section('title', 'Thank You — ' . $form->title)

@push('styles')
<style>
    body { background: linear-gradient(135deg, #001f3f 0%, #003366 100%); min-height: 100vh; }
    .thankyou-wrapper { max-width: 560px; margin: 0 auto; padding: 4rem 1rem; text-align: center; }
    .thankyou-card {
        background: #fff;
        border-radius: 24px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        padding: 3rem 2rem;
    }
    .check-circle {
        width: 90px; height: 90px;
        background: linear-gradient(135deg, #28a745, #20c997);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(40,167,69,0.3);
        animation: popIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    @keyframes popIn {
        from { transform: scale(0); opacity: 0; }
        to   { transform: scale(1); opacity: 1; }
    }
    .btn-home {
        background: linear-gradient(135deg, #001f3f, #0055a5);
        color: white; border: none;
        padding: 0.75rem 2rem;
        border-radius: 50px;
        font-weight: 600;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }
    .btn-home:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,31,63,0.3); color: white; }
</style>
@endpush

@section('content')
<div class="thankyou-wrapper">
    <div class="thankyou-card">
        <div class="check-circle">
            <i class="fas fa-check fa-2x text-white"></i>
        </div>

        @if(session('success'))
        <h2 class="fw-bold mb-2" style="color:#001f3f">
            {{ str_contains(session('success'), 'Payment') ? 'Payment Complete!' : 'Thank You!' }}
        </h2>
        <p class="text-muted mb-4">{{ session('success') }}</p>
        @else
        <h2 class="fw-bold mb-2" style="color:#001f3f">Thank You!</h2>
        <p class="text-muted mb-4">Your response has been successfully submitted.</p>
        @endif

        <div class="bg-light rounded-3 p-3 mb-4 text-start">
            <div class="d-flex align-items-center gap-2">
                <i class="fas fa-file-alt text-primary"></i>
                <span class="fw-semibold">{{ $form->title }}</span>
            </div>
        </div>

        <a href="{{ url('/') }}" class="btn-home">
            <i class="fas fa-home me-2"></i>Back to Home
        </a>

        <div class="mt-3">
            <a href="{{ route('form.show', $form->slug) }}" class="text-muted small text-decoration-none">
                <i class="fas fa-plus me-1"></i>Submit another response
            </a>
        </div>
    </div>
</div>
@endsection
