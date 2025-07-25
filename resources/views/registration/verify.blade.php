@extends('layouts.template')

@section('content')

<!-- Main Content -->
<main class="container">
  <!-- Open Events Section -->

<div class="row text-center justify-content-center align-items-center" style="min-height: 50vh;">


  <!-- Instruction Message -->

        <div class="col-12 col-md-6" style="padding-left: 2rem; padding-right: 2rem;">

            <div class="text-center mt-5 mb-3 px-3">
                <h2 class="fw-semibold fs-2 ">Please Verify OTP</h2>
                <p class="fw-bold">Please check your phone and verify OTP.</p>
            </div>

            <div class="card w-100 mb-4">
                <div class="card-body">
                    <form method="POST" action="{{ route('otp.verify', $event->slug) }}">
                        @csrf
                        <div class="mb-3">
                            <label for="otp" class="form-label">OTP Code</label>
                            <input type="text" class="form-control form-control-lg text-center" id="otp" name="otp" placeholder="Enter the OTP sent to your mobile" maxlength="6" required>
                        </div>
                        <button type="submit" class="btn w-100" style="background-color: #6f42c1; color: #fff;">Verify</button>
                    </form>
                </div>
            </div>
        </div>

    </div>




</main>
@endsection
