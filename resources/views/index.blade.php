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
</head>
<body>

<!-- Header with Navbar and Offcanvas -->
<header class="bg-dark text-white">
    <nav class="navbar navbar-dark navbar-expand-lg container">
    <a class="navbar-brand" href="{{ url('index.html') }}"><img class="logo" src="{{ url('images/logo-removebg-preview.png') }}" alt=""></a>
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

<!-- Owl Carousel Banner -->
<div class="owl-carousel owl-theme banner-carousel">
    <div class="item banner-slide">
        <img src="{{ url('images/banner1.jpg') }}" alt="Banner 1" class="w-100 h-100 object-fit-cover">
        <div class="banner-overlay"></div>
        <div class="banner-text">
            <h2 class="text-uppercase">It's happening</h2>
            <button class="btn global_button mt-3 px-5 py-2 fs-5 text-uppercase rounded-1 ">Go for all events</button>
        </div>
    </div>
    <div class="item banner-slide">
        <img src="{{ url('images/banner2.jpg') }}" alt="Banner 2" class="w-100 h-100 object-fit-cover">
        <div class="banner-overlay"></div>
        <div class="banner-text">
            <h2 class="text-uppercase">It's happening</h2>
            <button class="btn global_button mt-3 px-5 py-2 fs-5 text-uppercase rounded-1 ">Go for all events</button>
        </div>
    </div>
    <div class="item banner-slide">
        <img src="{{ url('images/banner3.jpg') }}" alt="Banner 3" class="w-100 h-100 object-fit-cover">
        <div class="banner-overlay"></div>
        <div class="banner-text">
            <h2 class="text-uppercase">It's happening</h2>
            <button class="btn global_button mt-3 px-5 py-2 fs-5 text-uppercase rounded-1 ">Go for all events</button>
        </div>
    </div>
</div>

<!-- Main Content -->
<main class="container my-5">
    <!-- Open Events Section -->
<section class="container my-5">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="mb-4 fw-bold">Open Events</h2>
        <div>
            <a class="px-4 py-1 rounded-4 text-decoration-none fw-medium global_button-outline" href="{{ url('events.html') }}">See All</a>
        </div>
    </div>

<div class="row">
        @if($events->isEmpty())
            <h1 class="text-center my-5">No open events now!</h1>
        @else
            @foreach ($events as $event)
                <div class="card rounded-1 col-12 col-md-4 col-lg-3 mb-4 mx-2">
                    <a href="{{ url('#') }}" class="card-title d-block nav-link p-3 fw-bold">{{ $event->name }}</a>
                    <a class="d-block px-3" href="{{ url('#') }}">
                        <img src="{{ url($event->cover_photo) }}" class="card-img-top rounded-0 object-fit-cover" alt="Event 1 Image">
                    </a>
                    <div class="card-body p-0">
                        <p class="mb-1 px-3 pt-3"><span class="fw-semibold">Status:</span> {{ $event->status }}</p>
                        <p class="mb-1 px-3 pb-3"><span class="fw-semibold">Date:</span> {{ $event->start_time }}</p>
                        <a href="{{ route('otp.form', $event->id) }}" class="btn global_button mt-2 d-block rounded-0 rounded-bottom-1 text-uppercase">Register Now</a>
                    </div>
                </div>
            @endforeach
        @endif

</div>

</section>

<!-- Upcoming Events Section -->
<section class="container my-5">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="mb-4 fw-bold ">Upcoming Events Events</h2>
        <div>
            <a class="fw-medium text-decoration-none px-4 py-1 rounded-4 global_button-outline" href="{{ url('events.html') }}">See All</a>
        </div>
    </div>
    <div class="row">
        @if(!false)
            <h1 class="text-center my-5">No upcoming events now!</h1>
        @else
        <div class="card rounded-1 col-12 col-md-4 col-lg-3 mb-4 mx-2">
                <a href="{{ url('details.html') }}" class="card-title d-block nav-link p-3 fw-bold">Berabo Tennis</a>
                <a class="d-block px-3" href="{{ url('details.html') }}">
                        <img src="{{ url('images/card1.jpg') }}" class="card-img-top rounded-0 object-fit-cover" alt="Event 1 Image" style="height: 180px;">
                </a>
                <div class="card-body p-0">
                        <p class="mb-1 px-3 pt-3"><span class="fw-semibold">Type:</span> Tennis</p>
                        <p class="mb-1 px-3 pb-3"><span class="fw-semibold">Date:</span> July 15, 2025</p>
                </div>
        </div>
        @endif
    </div>
</section>
<!-- ended Events Section -->
<section class="container my-5">
    <div class="d-flex align-items-center justify-content-between">
        <h2 class="mb-4 fw-bold">Past Events</h2>
        <div>
            <a class="text-decoration-none fw-medium px-4 py-1 rounded-4 global_button-outline" href="{{ url('events.html') }}">See All</a>
        </div>
    </div>
    <div class="row">
        @if (!false)
            <h1 class="text-center my-5">No past events now!</h1>
        @else
        <div class="card rounded-1 col-12 col-md-4 col-lg-3 mb-4 mx-2">
            <a href="{{ url('details.html') }}" class="card-title d-block nav-link p-3 fw-bold">Berabo Tennis</a>
            <a class="d-block px-3" href="{{ url('details.html') }}">
                    <img src="{{ url('images/card1.jpg') }}" class="card-img-top rounded-0 object-fit-cover" alt="Event 1 Image" style="height: 180px;">
            </a>
            <div class="card-body p-0">
                    <p class="mb-1 px-3 pt-3"><span class="fw-semibold">Type:</span> Tennis</p>
                    <p class="mb-1 px-3 pb-3"><span class="fw-semibold">Date:</span> July 15, 2025</p>
                    <a href="{{ url('RegistrationNumber.html') }}" class="btn btn- global_button  mt-2 d-block rounded-0  rounded-bottom-1 text-uppercase">see result</a>
            </div>
        </div>
        @endif
    </div>
</section>

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




</body>
</html>
