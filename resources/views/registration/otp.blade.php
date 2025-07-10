@extends('layouts.template')

@section('content')

<!-- Main Content -->
<main class="container">
  <!-- Open Events Section -->

    <div class="row">
        <div class="col-12 col-lg-6 col-md-6 mb-3 px-3 mt-5" style="border-right: 1px solid #e0e0e0;">
            <img src="{{ asset($event->cover_photo) }}" alt="">
            <h2 class="fw-semibold fs-2">{{ $event->name }}</h2>
            {!! $event->description !!}
        </div>

        <div class="col-12 col-lg-6 col-md-6" style="padding-left: 2rem; padding-right: 2rem;">

            <div class="otp-container d-flex flex-column justify-content-center align-items-center">
            <!-- Instruction Message -->
            <div class="text-center mt-5 mb-3 px-3">
                <h4 class="fw-semibold fs-2 ">Register for {{ $event->name }}</h4>
                <p class="fw-bold">Please enter your mobile number to receive an OTP for registration.</p>
            </div>

            <div class="card w-100 mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('otp.send', $event->id) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="phone" class="form-label">Mobile Number</label>
                            <input type="tel" class="form-control" id="phone" name="phone" placeholder="Enter your mobile number" required>
                        </div>
                        <button type="submit" class="btn w-100" style="background-color: #6f42c1; color: #fff;">Send</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</main>
@endsection
