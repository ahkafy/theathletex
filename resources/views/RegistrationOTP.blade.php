<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>The Athlete x</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
  <link rel="stylesheet" href="css/style.css">
</head>
<body>

<!-- Header with Navbar and Offcanvas -->
<header class="bg-dark text-white">
  <nav class="navbar navbar-dark navbar-expand-lg container">
  <a class="navbar-brand" href="index.html"><img class="logo" src="images/logo-removebg-preview.png" alt=""></a>
  <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasMenu">
    <div class="offcanvas-header">
      <h5 class="offcanvas-title">Menu</h5>
      <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button>
    </div>
    <div class="offcanvas-body d-flex flex-column flex-lg-row justify-content-between align-items-center w-100">
      
      <!-- Centered menu items -->
      <ul class="navbar-nav mx-auto text-center">
        <li class="nav-item"><a class="nav-link text-white" href="#">Home</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Events</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Register</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="#">Contact</a></li>
      </ul>

      <!-- "For Organizer" button -->
      <div class="text-center mt-3 mt-lg-0">
        <a href="organizer.html" class="btn global_button px-4">For Organizer</a>
      </div>
    </div>
  </div>
</nav>
</header>



<!-- Main Content -->
<main class="">
  <!-- Open Events Section -->

<div class="otp-container d-flex flex-column justify-content-center align-items-center">
  <!-- Instruction Message -->
  <div class="text-center mt-5 mb-3 px-3">
    <h4 class="fw-semibold fs-2 ">Register for an Marathon Event</h4>
    <p class="text mb-0 text-danger">
      Please enter your <strong>mobile number</strong> to receive a One-Time Password (OTP).<br />
      After receiving the OTP, submit it to complete your registration process.
    </p>
  </div>

  <!-- OTP Input Card -->
  <div class="border p-5 rounded-2 shadow w-100 w-md-75 w-lg-50 d-flex justify-content-center">
    <div class="container " id="otpSection">
      <h5 class="my-3">Enter Mobile Number <span id="selectedEventName" class="text-primary"></span></h5>
      <input type="text" class="form-control mb-2 rounded-1 my-3" placeholder="Enter mobile number" id="otpInput">
      <div class="d-flex justify-content-end">
        <button class="btn global_button rounded-1 my-3 text-uppercase px-4" id="openOtpModal">Send</button>
      </div>
    </div>
  </div>

</div>
  <div class="modal fade" id="otpModal" tabindex="-1" aria-labelledby="otpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content p-4">
      <div class="modal-header border-0">
        <h5 class="modal-title" id="otpModalLabel">Enter OTP</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <p class="text-muted mb-3">A 6-digit OTP has been sent to your number.</p>
        
        <!-- OTP Input Boxes -->
        <div class="d-flex justify-content-center gap-2 mb-3">
          <input type="text" class="otp-input form-control text-center" maxlength="1" />
          <input type="text" class="otp-input form-control text-center" maxlength="1" />
          <input type="text" class="otp-input form-control text-center" maxlength="1" />
          <input type="text" class="otp-input form-control text-center" maxlength="1" />
          <input type="text" class="otp-input form-control text-center" maxlength="1" />
          <input type="text" class="otp-input form-control text-center" maxlength="1" />
        </div>

        <!-- Countdown & Resend -->
        <div>
          <span id="otp-timer" class="text-danger fw-semibold">02:00</span>
          <button id="resendBtn" class="btn btn-link text-decoration-none ms-2 px-4" disabled>Resend OTP</button>
        </div>
      </div>

      <div class="modal-footer border-0 justify-content-center">
        <a href="RegistrationForm.html" class="btn global_button rounded-1 px-4">Submit</a>
        <!-- <button class="btn global_button rounded-1" onclick="submitOTP()">Submit</button> -->
      </div>
    </div>
  </div>
</div>


</main>

<!-- Footer -->
<footer class="bg-dark text-white py-5 mt-5">
  <div class="container">
    <div class="row g-md-5 g-2 px-3 px-md-0">
      <!-- Logo & Details -->
      <div class="col-md-3 mb-4">
        <img src="images/logo-removebg-preview.png" alt="Sports Zone Logo" style="max-width: 150px;" class="mb-2">
        <p class="small">
          Sports Zone brings you closer to your favorite sports. Discover, register, and enjoy events across the country.
        </p>
      </div>

      <!-- About -->
      <div class="col-md-3 mb-4">
        <h5>About</h5>
        <p class="small">We’re a dedicated platform connecting athletes and organizers through technology-driven sports events.</p>
      </div>

      <!-- Legal -->
      <div class="col-md-3 mb-4">
        <h5>Legal</h5>
        <ul class="list-unstyled small">
          <li><a href="#" class="text-white text-decoration-none">Privacy Policy</a></li>
          <li><a href="#" class="text-white text-decoration-none">Terms & Conditions</a></li>
          <li><a href="#" class="text-white text-decoration-none">Cookie Policy</a></li>
        </ul>
      </div>

      <!-- Contact -->
      <div class="col-md-3 mb-4">
        <h5>Contact</h5>
        <ul class="list-unstyled small">
          <li><i class="bi bi-envelope me-2"></i> info@sportszone.com</li>
          <li><i class="bi bi-telephone me-2"></i> +880 1234 567890</li>
          <li><i class="bi bi-geo-alt me-2"></i> Dhaka, Bangladesh</li>
        </ul>
      </div>
    </div>

    <hr class="border-light">
    <div class="text-center small">
      © 2025 Sports Zone. All rights reserved.
    </div>
  </div>
</footer>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
  let countdown;
  const timerDuration = 120;

  function startTimer() {
    let timeLeft = timerDuration;
    const timerEl = document.getElementById("otp-timer");
    const resendBtn = document.getElementById("resendBtn");

    resendBtn.disabled = true;
    resendBtn.classList.add("disabled");

    countdown = setInterval(() => {
      const minutes = String(Math.floor(timeLeft / 60)).padStart(2, "0");
      const seconds = String(timeLeft % 60).padStart(2, "0");
      timerEl.textContent = `${minutes}:${seconds}`;
      timeLeft--;

      if (timeLeft < 0) {
        clearInterval(countdown);
        resendBtn.disabled = false;
        resendBtn.classList.remove("disabled");
        timerEl.textContent = "Expired";
      }
    }, 1000);
  }

  document.getElementById("openOtpModal").addEventListener("click", () => {
    const modal = new bootstrap.Modal(document.getElementById("otpModal"));
    modal.show();
    startTimer();

    // Clear and focus inputs
    document.querySelectorAll('.otp-input').forEach(input => input.value = '');
    document.querySelector('.otp-input').focus();
  });

  // Input navigation
  document.querySelectorAll('.otp-input').forEach((input, index, inputs) => {
    input.addEventListener('input', () => {
      if (input.value.length === 1 && index < inputs.length - 1) {
        inputs[index + 1].focus();
      }
    });
  });

  function submitOTP() {
    let otp = '';
    document.querySelectorAll('.otp-input').forEach(input => otp += input.value);
    if (otp.length === 6) {
      window.location.href = "RegistrationForm.html";
    } else {
      alert("Please enter all 6 digits of the OTP.");
    }
  }

  document.getElementById("resendBtn").addEventListener("click", () => {
    alert("A new OTP has been sent!");
    startTimer();
  });
</script>




</body>
</html>
