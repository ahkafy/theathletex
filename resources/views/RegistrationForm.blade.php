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
<main class="  my-5">
  <!-- Open Events Section -->

  <!-- OTP Section -->

  <!-- Registration Form -->
   <div class="otp-container d-flex justify-content-center align-items-center">
        <div class="w-100 border shadow rounded-2 d-flex justify-content-center">

            <div class="container  p-5 mt-5" id="registrationForm">
              <h2 class="fw-bold">Complete Registration</h2>
              <form>
                <div class="mb-3">
                  <label class="form-label">Full Name</label>
                  <input type="text" class="form-control" placeholder="Enter your name">
                </div>
                <div class="mb-3">
                  <label class="form-label">Email</label>
                  <input type="email" class="form-control" placeholder="Enter your email">
                </div>
                <div class="mb-3">
                  <label class="form-label">Phone Number</label>
                  <input type="tel" class="form-control" placeholder="Enter your phone">
                </div>
                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn global_button rounded-2 px-4">Register</button>
                </div>
              </form>
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






</body>
</html>
