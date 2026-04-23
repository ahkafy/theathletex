@extends('layouts.app')
@section('title', $form->title)

@push('styles')
<style>
    body { background: linear-gradient(135deg, #001f3f 0%, #003366 100%); min-height: 100vh; }
    .form-wrapper { max-width: 720px; margin: 0 auto; padding: 2rem 1rem; }
    .form-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0,0,0,0.25);
        overflow: hidden;
    }
    .form-header {
        background: linear-gradient(135deg, #001f3f, #0055a5);
        color: white;
        padding: 2.5rem 2rem 2rem;
    }
    .cover-container {
        width: 100%;
        max-height: 250px;
        overflow: hidden;
        border-bottom: 5px solid #ffc107;
    }
    .cover-container img {
        width: 100%;
        height: 250px;
        object-fit: cover;
    }
    .form-body { padding: 2rem; }
    .form-field-group { margin-bottom: 1.5rem; }
    .form-field-group label { font-weight: 600; margin-bottom: 0.4rem; color: #222; }
    .required-star { color: #dc3545; }
    .form-control:focus, .form-select:focus {
        border-color: #0055a5;
        box-shadow: 0 0 0 0.2rem rgba(0,85,165,0.15);
    }
    .payment-notice {
        background: linear-gradient(135deg, #fff3cd, #ffeaa7);
        border: 2px solid #ffc107;
        border-radius: 12px;
        padding: 1.25rem 1.5rem;
        margin-bottom: 1.5rem;
    }
    .btn-submit {
        background: linear-gradient(135deg, #001f3f, #0055a5);
        border: none;
        color: white;
        padding: 0.85rem 2.5rem;
        border-radius: 50px;
        font-weight: 600;
        font-size: 1rem;
        letter-spacing: 0.5px;
        transition: all 0.3s;
        width: 100%;
    }
    .btn-submit:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(0,31,63,0.3); color: white; }
    .divider { border: none; border-top: 2px dashed #dee2e6; margin: 1.5rem 0; }
    .respondent-section { background: #f8f9ff; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
    .respondent-section h6 { color: #001f3f; font-weight: 700; margin-bottom: 1rem; }
    .form-check-input:checked { background-color: #0055a5; border-color: #0055a5; }
</style>
@endpush

@section('content')
<div class="form-wrapper">
    <div class="form-card">
        {{-- Cover Photo --}}
        @if($form->cover_photo)
            <div class="cover-container">
                <img src="{{ asset('storage/' . $form->cover_photo) }}" alt="{{ $form->title }}">
            </div>
        @endif

        {{-- Header --}}
        <div class="form-header">
            <h1 class="h3 fw-bold mb-2">{{ $form->title }}</h1>
            @if($form->description)
            <p class="mb-0 opacity-85" style="line-height:1.6">{{ $form->description }}</p>
            @endif
        </div>

        <div class="form-body">
            @if(session('error'))
            <div class="alert alert-danger rounded-3 mb-4">
                <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger rounded-3 mb-4">
                <strong><i class="fas fa-exclamation-triangle me-2"></i>Please fix the following:</strong>
                <ul class="mb-0 mt-2 ps-3">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- Payment Notice --}}
            @if($form->payment_required)
            <div class="payment-notice">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-credit-card fa-lg text-warning"></i>
                    <div>
                        <strong>Payment Required</strong><br>
                        <span class="text-muted small">
                            After submitting, you'll be redirected to pay
                            <strong>{{ number_format($form->payment_amount, 2) }} {{ $form->payment_currency }}</strong>
                            via SSLCommerz to complete your submission.
                        </span>
                    </div>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('form.submit', $form->slug) }}" id="publicForm" enctype="multipart/form-data">
                @csrf

                {{-- Always-collected respondent info --}}
                <div class="respondent-section">
                    <h6><i class="fas fa-user me-2"></i>Your Information</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-field-group mb-0">
                                <label>Full Name <span class="required-star">*</span></label>
                                <input type="text" name="respondent_name" class="form-control @error('respondent_name') is-invalid @enderror"
                                       value="{{ old('respondent_name') }}" placeholder="Your full name" required>
                                @error('respondent_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-field-group mb-0">
                                <label>Email Address <span class="required-star">*</span></label>
                                <input type="email" name="respondent_email" class="form-control @error('respondent_email') is-invalid @enderror"
                                       value="{{ old('respondent_email') }}" placeholder="your@email.com" required>
                                @error('respondent_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-field-group mb-0">
                                <label>Phone Number {!! $form->payment_required ? '<span class="required-star">*</span>' : '' !!}</label>
                                <input type="tel" name="respondent_phone" class="form-control @error('respondent_phone') is-invalid @enderror"
                                       value="{{ old('respondent_phone') }}" placeholder="+880…"
                                       {{ $form->payment_required ? 'required' : '' }}>
                                @error('respondent_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Dynamic Fields --}}
                @if($form->fields->count())
                <hr class="divider">
                <h6 class="fw-bold mb-3" style="color:#001f3f">
                    <i class="fas fa-list me-2"></i>Form Questions
                </h6>
                @foreach($form->fields as $field)
                <div class="form-field-group">
                    <label for="field_{{ $field->id }}">
                        {{ $field->label }}
                        @if($field->is_required)<span class="required-star">*</span>@endif
                    </label>

                    @if($field->field_type === 'textarea')
                        <textarea name="field_{{ $field->id }}" id="field_{{ $field->id }}"
                                  class="form-control @error('field_'.$field->id) is-invalid @enderror"
                                  rows="4"
                                  placeholder="{{ $field->placeholder ?? '' }}"
                                  {{ $field->is_required ? 'required' : '' }}>{{ old('field_'.$field->id) }}</textarea>

                    @elseif($field->field_type === 'select')
                        <select name="field_{{ $field->id }}" id="field_{{ $field->id }}"
                                class="form-select @error('field_'.$field->id) is-invalid @enderror"
                                {{ $field->is_required ? 'required' : '' }}>
                            <option value="">— Select —</option>
                            @foreach($field->options ?? [] as $opt)
                            <option value="{{ $opt }}" {{ old('field_'.$field->id) == $opt ? 'selected' : '' }}>
                                {{ $opt }}
                            </option>
                            @endforeach
                        </select>

                    @elseif($field->field_type === 'radio')
                        <div class="mt-1">
                            @foreach($field->options ?? [] as $opt)
                            <div class="form-check">
                                <input class="form-check-input" type="radio"
                                       name="field_{{ $field->id }}" id="field_{{ $field->id }}_{{ $loop->index }}"
                                       value="{{ $opt }}"
                                       {{ old('field_'.$field->id) == $opt ? 'checked' : '' }}
                                       {{ $field->is_required && $loop->first ? 'required' : '' }}>
                                <label class="form-check-label fw-normal" for="field_{{ $field->id }}_{{ $loop->index }}">
                                    {{ $opt }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                    @elseif($field->field_type === 'checkbox')
                        <div class="mt-1">
                            @foreach($field->options ?? [] as $opt)
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox"
                                       name="field_{{ $field->id }}[]" id="field_{{ $field->id }}_{{ $loop->index }}"
                                       value="{{ $opt }}"
                                       {{ is_array(old('field_'.$field->id)) && in_array($opt, old('field_'.$field->id, [])) ? 'checked' : '' }}>
                                <label class="form-check-label fw-normal" for="field_{{ $field->id }}_{{ $loop->index }}">
                                    {{ $opt }}
                                </label>
                            </div>
                            @endforeach
                        </div>

                    @elseif($field->field_type === 'file' || $field->field_type === 'image')
                        <input type="file" name="field_{{ $field->id }}" id="field_{{ $field->id }}"
                               class="form-control @error('field_'.$field->id) is-invalid @enderror"
                               {{ $field->field_type === 'image' ? 'accept=image/*' : '' }}
                               {{ $field->is_required ? 'required' : '' }}>
                        @if($field->placeholder)
                            <div class="form-text small opacity-75">{{ $field->placeholder }}</div>
                        @endif

                    @else
                        <input type="{{ $field->field_type }}" name="field_{{ $field->id }}" id="field_{{ $field->id }}"
                               class="form-control @error('field_'.$field->id) is-invalid @enderror"
                               value="{{ old('field_'.$field->id) }}"
                               placeholder="{{ $field->placeholder ?? '' }}"
                               {{ $field->is_required ? 'required' : '' }}>
                    @endif

                    @error('field_'.$field->id)
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                @endforeach
                @endif

                <hr class="divider">

                <button type="submit" class="btn-submit btn">
                    @if($form->payment_required)
                        <i class="fas fa-credit-card me-2"></i>Submit & Pay
                        {{ number_format($form->payment_amount, 2) }} {{ $form->payment_currency }}
                    @else
                        <i class="fas fa-paper-plane me-2"></i>Submit Response
                    @endif
                </button>
            </form>
        </div>
    </div>

    <p class="text-center text-white-50 small mt-3">
        <i class="fas fa-lock me-1"></i> Your responses are secure and private.
    </p>
</div>
@endsection
