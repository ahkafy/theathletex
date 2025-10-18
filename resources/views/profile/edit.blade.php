<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Edit Profile - The Athlete X Limited</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
</head>
<body>

@include('partials.navbar')

<!-- Hero Section -->
<section class="hero-section text-white py-4">
    <div class="container text-center">
        <h1 class="display-5 fw-bold mb-2">Edit Profile</h1>
        <p class="lead">Update your athlete profile information</p>
    </div>
</section>

<!-- Main Content -->
<main class="container my-5">
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('password_success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('password_success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <!-- Profile Form -->
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Profile Information</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <!-- Profile Photo -->
                        <div class="mb-4 text-center">
                            <div class="mb-3">
                                @if($user->profile_photo)
                                    <img src="{{ asset('storage/' . $user->profile_photo) }}" alt="Profile Photo" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;" id="profile-preview">
                                @else
                                    <div class="bg-primary rounded-circle d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;" id="profile-preview">
                                        <i class="bi bi-person-fill text-white" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <input type="file" class="form-control" id="profile_photo" name="profile_photo" accept="image/*" onchange="previewImage(this)">
                            <small class="text-muted">Upload a profile photo (max 2MB)</small>
                        </div>

                        <div class="row">
                            <!-- Basic Information -->
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email Address *</label>
                                <div class="input-group">
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @if($user->email_verified_at)
                                        <span class="input-group-text text-success">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </span>
                                    @else
                                        <span class="input-group-text text-warning">
                                            <i class="bi bi-exclamation-circle-fill"></i>
                                        </span>
                                    @endif
                                </div>
                                @if(!$user->email_verified_at)
                                    <small class="text-warning">Please verify your email address</small>
                                @endif
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @if($user->hasVerifiedPhone())
                                        <span class="input-group-text text-success">
                                            <i class="bi bi-check-circle-fill"></i>
                                        </span>
                                    @else
                                        <button type="button" class="btn btn-outline-primary" id="verify-phone-btn" {{ !$user->phone ? 'disabled' : '' }}>
                                            Verify
                                        </button>
                                    @endif
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="emergency_phone" class="form-label">Emergency Contact</label>
                                <input type="text" class="form-control" id="emergency_phone" name="emergency_phone" value="{{ old('emergency_phone', $user->emergency_phone) }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="gender" class="form-label">Gender</label>
                                <select class="form-select" id="gender" name="gender">
                                    <option value="">Select Gender</option>
                                    <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ old('gender', $user->gender) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="dob" class="form-label">Date of Birth</label>
                                <input type="date" class="form-control" id="dob" name="dob" value="{{ old('dob', $user->dob ? $user->dob->format('Y-m-d') : '') }}">
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="tshirt_size" class="form-label">T-Shirt Size</label>
                                <select class="form-select" id="tshirt_size" name="tshirt_size">
                                    <option value="">Select Size</option>
                                    <option value="XS" {{ old('tshirt_size', $user->tshirt_size) == 'XS' ? 'selected' : '' }}>XS</option>
                                    <option value="S" {{ old('tshirt_size', $user->tshirt_size) == 'S' ? 'selected' : '' }}>S</option>
                                    <option value="M" {{ old('tshirt_size', $user->tshirt_size) == 'M' ? 'selected' : '' }}>M</option>
                                    <option value="L" {{ old('tshirt_size', $user->tshirt_size) == 'L' ? 'selected' : '' }}>L</option>
                                    <option value="XL" {{ old('tshirt_size', $user->tshirt_size) == 'XL' ? 'selected' : '' }}>XL</option>
                                    <option value="XXL" {{ old('tshirt_size', $user->tshirt_size) == 'XXL' ? 'selected' : '' }}>XXL</option>
                                </select>
                            </div>

                            <!-- Address Information -->
                            <div class="col-12 mb-3">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" name="address" rows="2">{{ old('address', $user->address) }}</textarea>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="district" class="form-label">District</label>
                                <input type="text" class="form-control" id="district" name="district" value="{{ old('district', $user->district) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="thana" class="form-label">Thana/Upazila</label>
                                <input type="text" class="form-control" id="thana" name="thana" value="{{ old('thana', $user->thana) }}">
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="nationality" class="form-label">Nationality</label>
                                <input type="text" class="form-control" id="nationality" name="nationality" value="{{ old('nationality', $user->nationality) }}">
                            </div>

                            <!-- Sports Interests -->
                            <div class="col-12 mb-3">
                                <label class="form-label">Sports Interests</label>
                                <div class="row">
                                    @php
                                        $sports = ['Football', 'Cricket', 'Tennis', 'Basketball', 'Badminton', 'Swimming', 'Running', 'Cycling', 'Volleyball', 'Table Tennis'];
                                        $userSports = old('sports_interests', $user->sports_interests ?? []);
                                    @endphp
                                    @foreach($sports as $sport)
                                        <div class="col-md-3 col-6">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="sports_interests[]" value="{{ $sport }}" id="sport_{{ $loop->index }}" {{ in_array($sport, $userSports) ? 'checked' : '' }}>
                                                <label class="form-check-label" for="sport_{{ $loop->index }}">
                                                    {{ $sport }}
                                                </label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <!-- Bio -->
                            <div class="col-12 mb-3">
                                <label for="bio" class="form-label">Bio</label>
                                <textarea class="form-control" id="bio" name="bio" rows="3" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                <small class="text-muted">Maximum 1000 characters</small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn global_button">
                                <i class="bi bi-check2"></i> Update Profile
                            </button>
                            <a href="{{ route('profile.index') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Back to Profile
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Password Change & Verification -->
        <div class="col-lg-4">
            <!-- Password Change -->
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0">Change Password</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <button type="submit" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-shield-lock"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>

            <!-- Phone Verification -->
            @if($user->phone && !$user->hasVerifiedPhone())
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">Phone Verification</h6>
                </div>
                <div class="card-body">
                    <p class="small text-muted">Verify your phone number: {{ $user->phone }}</p>

                    <div id="phone-verification-form" style="display: none;">
                        <div class="mb-3">
                            <label for="verification_code" class="form-label">Verification Code</label>
                            <input type="text" class="form-control" id="verification_code" maxlength="6" placeholder="Enter 6-digit code">
                        </div>
                        <button type="button" class="btn btn-success btn-sm w-100" onclick="verifyPhone()">
                            <i class="bi bi-check2"></i> Verify Code
                        </button>
                    </div>

                    <button type="button" class="btn btn-outline-primary btn-sm w-100" id="send-code-btn" onclick="sendVerificationCode()">
                        <i class="bi bi-phone"></i> Send Verification Code
                    </button>
                </div>
            </div>
            @endif
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="bg-dark text-white py-5 mt-5">
    <div class="container">
        <div class="row g-md-5 g-2 px-3 px-md-0">
            <!-- Logo & Details -->
            <div class="col-md-3 mb-4">
                <img src="{{ url('images/logo-removebg-preview.png') }}" alt="Sports Zone Logo" style="max-width: 150px;" class="mb-2">
                <p class="small">
                    Sports Zone brings you closer to your favorite sports. Discover, register, and enjoy events across the country.
                </p>
            </div>

            <!-- About -->
            <div class="col-md-3 mb-4">
                <h5>About</h5>
                <p class="small">We're a dedicated platform connecting athletes and organizers through technology-driven sports events.</p>
            </div>

            <!-- Policies -->
            <div class="col-md-3 mb-4">
                <h5>Policies</h5>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('about') }}" class="text-white text-decoration-none">About Us</a></li>
                    <li><a href="{{ route('privacy') }}" class="text-white text-decoration-none">Privacy Policy</a></li>
                    <li><a href="{{ route('delivery') }}" class="text-white text-decoration-none">Delivery Policy</a></li>
                    <li><a href="{{ route('return') }}" class="text-white text-decoration-none">Return Policy</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div class="col-md-3 mb-4">
                <h5>Contact</h5>
                <ul class="list-unstyled small">
                    <li><i class="bi bi-geo-alt me-2"></i>18/4, Pallabi Road, Dhaka 1216</li>
                    <li><i class="bi bi-telephone me-2"></i>+880 1775 269643</li>
                    <li><i class="bi bi-envelope me-2"></i>info@theathletex.net</li>
                    <li><i class="bi bi-card-text me-2"></i>Trade License: TRAD/DSCC/088847/2024</li>
                </ul>
            </div>
        </div>

        <!-- Payment Methods -->
        <div class="row mt-4 pt-4 border-top border-secondary">
            <div class="col-12 text-center">
                <h6>Secure Payment Methods</h6>
                <img src="{{ url('/payment_banner.png') }}" alt="Payment Methods" class="img-fluid" style="max-height: 260px;">
            </div>
        </div>

        <hr class="border-light">
        <div class="text-center small">
            © 2025 The Athlete X Limited. All rights reserved. | Built in Bangladesh
        </div>
    </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('profile-preview').innerHTML = `<img src="${e.target.result}" alt="Profile Photo" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover;">`;
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function sendVerificationCode() {
    const btn = document.getElementById('send-code-btn');
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split"></i> Sending...';

    fetch('{{ route("profile.send-phone-verification") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('phone-verification-form').style.display = 'block';
            btn.style.display = 'none';
            alert('Verification code sent! Code: ' + data.code); // Remove in production
        } else {
            alert(data.error || 'Failed to send verification code');
            btn.disabled = false;
            btn.innerHTML = '<i class="bi bi-phone"></i> Send Verification Code';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to send verification code');
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-phone"></i> Send Verification Code';
    });
}

function verifyPhone() {
    const code = document.getElementById('verification_code').value;
    if (!code || code.length !== 6) {
        alert('Please enter a valid 6-digit code');
        return;
    }

    fetch('{{ route("profile.verify-phone") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            verification_code: code
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Phone verified successfully!');
            location.reload();
        } else {
            alert(data.error || 'Invalid verification code');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Failed to verify phone');
    });
}

// Update verify phone button state based on phone input
document.getElementById('phone').addEventListener('input', function() {
    const verifyBtn = document.getElementById('verify-phone-btn');
    if (verifyBtn) {
        verifyBtn.disabled = !this.value;
    }
});
</script>

</body>
</html>
