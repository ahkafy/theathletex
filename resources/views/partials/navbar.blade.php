<!-- Header with Navbar and Offcanvas -->
<header class="main-header sticky-top">
    <nav class="navbar navbar-dark navbar-expand-lg container">
        <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
            <img class="logo" src="{{ url('images/logo-removebg-preview.png') }}" alt="TheAthleteX Logo">
        </a>
        <button class="navbar-toggler border-0 shadow-none" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="offcanvas offcanvas-end text-bg-dark" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
            <div class="offcanvas-header border-bottom border-secondary">
                <h5 class="offcanvas-title" id="offcanvasMenuLabel">Menu</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body d-flex flex-column flex-lg-row justify-content-between align-items-center w-100">
                <!-- Centered menu items -->
                <ul class="navbar-nav mx-auto text-center header-nav-links">
                    <li class="nav-item"><a class="nav-link text-white px-3" href="{{ url('/') }}">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-white px-3" href="#" id="eventsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Events
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="eventsDropdown">
                            <li><a class="dropdown-item" href="{{ route('events.upcoming') }}"><i class="bi bi-calendar-event me-2"></i>Upcoming Events</a></li>
                            <li><a class="dropdown-item" href="{{ route('events.past') }}"><i class="bi bi-calendar-check me-2"></i>Past Events</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('events.all') }}"><i class="bi bi-grid-3x3-gap me-2"></i>All Events</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link text-white px-3" href="#">Contact</a></li>
                </ul>

                <!-- Right-aligned auth menu -->
                <div class="text-center mt-3 mt-lg-0 navbar-auth-actions">
                    @auth
                        <div class="dropdown">
                            <a class="btn auth-btn auth-btn-outline dropdown-toggle d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle fs-5"></i>
                                <span>{{ Auth::user()->name }}</span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.index') }}"><i class="bi bi-person me-2"></i>My Profile</a></li>
                                <li><a class="dropdown-item" href="{{ route('events.all') }}"><i class="bi bi-calendar-event me-2"></i>Browse Events</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <div class="d-flex align-items-center gap-2 flex-column flex-lg-row">
                            <a href="{{ route('login') }}" class="btn auth-btn auth-btn-outline">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                            <a href="{{ route('register') }}" class="btn auth-btn auth-btn-solid">
                                <i class="bi bi-person-plus me-1"></i>Register
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>
</header>
