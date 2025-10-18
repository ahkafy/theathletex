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
                <li class="nav-item"><a class="nav-link text-white" href="{{ url('/') }}">Home</a></li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" id="eventsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Events
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="eventsDropdown">
                        <li><a class="dropdown-item" href="{{ route('events.upcoming') }}">Upcoming Events</a></li>
                        <li><a class="dropdown-item" href="{{ route('events.past') }}">Past Events</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="{{ route('events.all') }}">All Events</a></li>
                    </ul>
                </li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Contact</a></li>
            </ul>

            <!-- Right-aligned auth menu -->
            <div class="text-center mt-3 mt-lg-0">
                                    @auth
                        <div class="dropdown">
                            <a class="btn auth-btn auth-btn-outline dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}"><i class="bi bi-speedometer2 me-2"></i>Dashboard</a></li>
                                <li><a class="dropdown-item" href="{{ route('profile.show') }}"><i class="bi bi-person me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="btn auth-btn auth-btn-outline me-2">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn auth-btn auth-btn-solid">
                            <i class="bi bi-person-plus me-1"></i>Register
                        </a>
                    @endauth
            </div>
        </div>
    </div>
</nav>
</header>
