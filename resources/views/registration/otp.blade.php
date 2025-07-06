@extends('layouts.template')

@section('content')

<!-- Main Content -->
<main class="container col-md-6 col-lg-5">
  <!-- Open Events Section -->

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


</main>
@endsection
