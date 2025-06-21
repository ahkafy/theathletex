<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    @stack('styles')
    <style>
        body, html {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        .sidebar {
            background-color: #001f3f !important;
            min-height: 100vh;
            position: fixed;
            top: 0px; /* Height of navbar */
            left: 0;
            width: 16.666667%; /* col-md-2 */
            z-index: 1000;
            overflow-y: auto;
        }
        .content-panel {
            margin-left: 16.666667%;
            padding: 2rem 2rem 2rem 2rem;
            height: 100vh; /* 56px is navbar height */
            overflow-y: auto;
        }
        .navbar-custom {
            background-color: #001f3f !important;
        }
        @media (max-width: 767.98px) {
            .sidebar {
                position: static;
                width: 100%;
                min-height: auto;
                top: 0;
            }
            .content-panel {
                margin-left: 0;
                height: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Drawer -->
            <nav id="sidebarDrawer" class="col-md-2 sidebar py-4 d-md-block d-none position-fixed" tabindex="-1" style="transition: left 0.3s; left: 0;">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">Contests</a>
                    </li>
                    <!-- Add more sidebar links as needed -->
                </ul>
            </nav>
            <!-- Sidebar Toggle Button (Mobile) -->
            <button class="btn btn-primary d-md-none position-fixed rounded-circle shadow"
                style="bottom: 1.25rem; right: 1.25rem; z-index: 1100; width: 48px; height: 48px; display: flex; align-items: center; justify-content: center; padding: 0;"
                type="button" id="sidebarToggle" aria-label="Toggle sidebar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="white" viewBox="0 0 16 16">
                    <rect width="14" height="2" x="1" y="3" rx="1"/>
                    <rect width="14" height="2" x="1" y="7" rx="1"/>
                    <rect width="14" height="2" x="1" y="11" rx="1"/>
                </svg>
            </button>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const sidebar = document.getElementById('sidebarDrawer');
                    const toggleBtn = document.getElementById('sidebarToggle');
                    let open = false;

                    function openSidebar() {
                        sidebar.classList.remove('d-none');
                        sidebar.style.left = '0';
                        sidebar.style.width = '70vw';
                        sidebar.style.maxWidth = '300px';
                        sidebar.style.background = '#001f3f';
                        sidebar.style.height = '100vh';
                        sidebar.style.zIndex = '1050';
                        sidebar.style.boxShadow = '2px 0 8px rgba(0,0,0,0.2)';
                        open = true;
                    }

                    function closeSidebar() {
                        sidebar.style.left = '-100vw';
                        setTimeout(() => sidebar.classList.add('d-none'), 300);
                        open = false;
                    }

                    toggleBtn.addEventListener('click', function () {
                        if (!open) {
                            sidebar.classList.remove('d-none');
                            setTimeout(openSidebar, 10);
                        } else {
                            closeSidebar();
                        }
                    });

                    // Hide sidebar on mobile by default
                    function handleResize() {
                        if (window.innerWidth < 768) {
                            sidebar.classList.add('d-none');
                            sidebar.style.left = '-100vw';
                            open = false;
                        } else {
                            sidebar.classList.remove('d-none');
                            sidebar.style.left = '0';
                            sidebar.style.width = '';
                            sidebar.style.maxWidth = '';
                            sidebar.style.background = '';
                            sidebar.style.height = '';
                            sidebar.style.zIndex = '';
                            sidebar.style.boxShadow = '';
                            open = false;
                        }
                    }
                    window.addEventListener('resize', handleResize);
                    handleResize();

                    // Close sidebar when clicking outside (mobile)
                    document.addEventListener('click', function (e) {
                        if (open && window.innerWidth < 768 && !sidebar.contains(e.target) && e.target !== toggleBtn) {
                            closeSidebar();
                        }
                    });
                });
            </script>
            <div class="col-md-10 offset-md-2 content-panel">
                @yield('content')
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>
