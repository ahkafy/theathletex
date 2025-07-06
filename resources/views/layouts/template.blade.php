<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>The Athlete X Limited</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css"/>
    <link rel="stylesheet" href="{{ url('css/style.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto Condensed', Arial, sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body>

<!-- Header with Navbar and Offcanvas -->
<header class="bg-dark text-white">
    <nav class="navbar navbar-dark navbar-expand-lg container">
    <a class="navbar-brand" href="{{ url('/') }}"><img class="logo" src="{{ url('images/logo-removebg-preview.png') }}" alt=""></a>
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
                <a href="{{ url('organizer.html') }}" class="btn global_button px-4">For Organizer</a>
            </div>
        </div>
    </div>
</nav>
</header>


@yield('content')


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
                    <li><i class="bi bi-envelope me-2"></i> info@theathletex.net</li>
                    <li><i class="bi bi-telephone me-2"></i> +880 1234 567890</li>
                    <li><i class="bi bi-geo-alt me-2"></i> Mirpur, Dhaka, Bangladesh</li>
                </ul>
            </div>
        </div>

        <hr class="border-light">
        <div class="text-center small">
            © 2025 The Athlete X Limited. All rights reserved.
        </div>
    </div>
</footer>

@if(session('success') || session('warning') || session('error'))
    <div id="alert-popup" class="position-fixed top-0 end-0 p-3" style="z-index: 1080; width: 350px; height: 120px;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show h-100 d-flex align-items-center" role="alert">
                <div>{{ session('success') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @elseif(session('warning'))
            <div class="alert alert-warning alert-dismissible fade show h-100 d-flex align-items-center" role="alert">
                <div>{{ session('warning') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @elseif(session('error'))
            <div class="alert alert-danger alert-dismissible fade show h-100 d-flex align-items-center" role="alert">
                <div>{{ session('error') }}</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        @endif
    </div>
    <script>
        setTimeout(function() {
            var alertPopup = document.getElementById('alert-popup');
            if(alertPopup){
                alertPopup.style.display = 'none';
            }
        }, 7000);
    </script>
@endif

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
    $(document).ready(function () {
        // Banner carousel
        $(".banner-carousel").owlCarousel({
            items: 1,
            loop: true,
            autoplay: true,
            autoplayTimeout: 5000,
            autoplayHoverPause: true,
            dots: false,
            smartSpeed: 800
        });

        // Open Events - 5 seconds
        $(".open-events").owlCarousel({
            loop: true,
            margin: 15,
            autoplay: true,
            autoplayTimeout: 5000, // 3 seconds
            autoplayHoverPause: true,
            smartSpeed: 800,
            responsive: {
                0: {
                    items: 2,
                    slideBy: 1
                },
                768: {
                    items: 4,
                    slideBy: 1
                }
            }
        });

        // Upcoming Events - 3 seconds
        $(".upcoming-events").owlCarousel({
            loop: true,
            margin: 15,
            autoplay: true,
            autoplayTimeout: 3000, // 3 seconds
            autoplayHoverPause: true,
            smartSpeed: 800,
            responsive: {
                0: {
                    items: 2,
                    slideBy: 1
                },
                768: {
                    items: 4,
                    slideBy: 1
                }
            }
        });
        // ended Events - 3 seconds
        $(".end-events").owlCarousel({
            loop: true,
            margin: 15,
            autoplay: true,
            autoplayTimeout: 4000, // 3 seconds
            autoplayHoverPause: true,
            smartSpeed: 800,
            responsive: {
                0: {
                    items: 2,
                    slideBy: 1
                },
                768: {
                    items: 4,
                    slideBy: 1
                }
            }
        });
    });
</script>

@stack('scripts')


</body>
</html>
