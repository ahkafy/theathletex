<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

        /* Fix pagination arrow buttons */
        .pagination {
            --bs-pagination-padding-x: 0.75rem;
            --bs-pagination-padding-y: 0.375rem;
            --bs-pagination-font-size: 0.875rem;
            --bs-pagination-color: #6c757d;
            --bs-pagination-bg: #fff;
            --bs-pagination-border-width: 1px;
            --bs-pagination-border-color: #dee2e6;
            --bs-pagination-border-radius: 0.375rem;
            --bs-pagination-hover-color: #0056b3;
            --bs-pagination-hover-bg: #e9ecef;
            --bs-pagination-hover-border-color: #dee2e6;
            --bs-pagination-focus-color: #0056b3;
            --bs-pagination-focus-bg: #e9ecef;
            --bs-pagination-focus-box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            --bs-pagination-active-color: #fff;
            --bs-pagination-active-bg: #0d6efd;
            --bs-pagination-active-border-color: #0d6efd;
            --bs-pagination-disabled-color: #6c757d;
            --bs-pagination-disabled-bg: #fff;
            --bs-pagination-disabled-border-color: #dee2e6;
        }

        .pagination .page-link {
            position: relative;
            display: block;
            padding: var(--bs-pagination-padding-y) var(--bs-pagination-padding-x);
            font-size: var(--bs-pagination-font-size);
            color: var(--bs-pagination-color);
            text-decoration: none;
            background-color: var(--bs-pagination-bg);
            border: var(--bs-pagination-border-width) solid var(--bs-pagination-border-color);
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        }

        .pagination .page-item.active .page-link {
            z-index: 3;
            color: var(--bs-pagination-active-color);
            background-color: var(--bs-pagination-active-bg);
            border-color: var(--bs-pagination-active-border-color);
        }

        .pagination .page-item .page-link:hover {
            z-index: 2;
            color: var(--bs-pagination-hover-color);
            background-color: var(--bs-pagination-hover-bg);
            border-color: var(--bs-pagination-hover-border-color);
        }

        .pagination .page-item.disabled .page-link {
            color: var(--bs-pagination-disabled-color);
            pointer-events: none;
            background-color: var(--bs-pagination-disabled-bg);
            border-color: var(--bs-pagination-disabled-border-color);
        }

        /* Fix arrow button sizing specifically */
        .pagination .page-link[aria-label="pagination.previous"],
        .pagination .page-link[aria-label="pagination.next"] {
            padding: 0.375rem 0.75rem;
            line-height: 1.5;
        }

        /* Font Awesome icons in pagination */
        .pagination .page-link i {
            font-size: 0.75rem;
            vertical-align: middle;
        }

        /* Ensure pagination is responsive and well-spaced */
        .pagination .page-item .page-link {
            min-width: 40px;
            text-align: center;
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Sidebar Drawer -->
            <nav id="sidebarDrawer" class="col-md-2 sidebar py-4 d-md-block d-none position-fixed" tabindex="-1" style="transition: left 0.3s; left: 0;">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.events.index') }}">
                            <i class="fas fa-calendar-alt me-2"></i>Events
                        </a>
                    </li>

                    <!-- Reports Section -->
                    <li class="nav-item mt-3">
                        <h6 class="text-white-50 text-uppercase px-3 mb-2">Reports</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.reports.participants') }}">
                            <i class="fas fa-users me-2"></i>Participants
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.reports.transactions') }}">
                            <i class="fas fa-credit-card me-2"></i>Transactions
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.reports.events') }}">
                            <i class="fas fa-chart-bar me-2"></i>Event Reports
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.reports.revenue') }}">
                            <i class="fas fa-dollar-sign me-2"></i>Revenue
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.reports.analytics') }}">
                            <i class="fas fa-chart-line me-2"></i>Analytics
                        </a>
                    </li>

                    <!-- Export Section -->
                    <li class="nav-item mt-3">
                        <h6 class="text-white-50 text-uppercase px-3 mb-2">Export Data</h6>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.export.participants') }}">
                            <i class="fas fa-download me-2"></i>Export Participants
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="{{ route('admin.export.transactions') }}">
                            <i class="fas fa-file-csv me-2"></i>Export Transactions
                        </a>
                    </li>

                    <!-- Settings Section -->
                    <li class="nav-item mt-3">
                        <h6 class="text-white-50 text-uppercase px-3 mb-2">Account</h6>
                    </li>
                    <li class="nav-item">
                        <form action="{{ route('admin.logout') }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="nav-link text-white bg-transparent border-0 w-100 text-start">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </button>
                        </form>
                    </li>
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

    @if(session('success') || session('warning') || session('error'))
        <div id="alert-toast"
             class="position-fixed bottom-0 end-0 p-3"
             style="z-index: 2000; min-width: 300px;">
            @if(session('success'))
                <div class="toast align-items-center text-bg-success border-0 show mb-2" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('success') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if(session('warning'))
                <div class="toast align-items-center text-bg-warning border-0 show mb-2" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('warning') }}
                        </div>
                        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
            @if(session('error'))
                <div class="toast align-items-center text-bg-danger border-0 show mb-2" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            {{ session('error') }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
        <script>
            setTimeout(function() {
                const toast = document.getElementById('alert-toast');
                if (toast) toast.remove();
            }, 7000);
            document.querySelectorAll('#alert-toast .btn-close').forEach(btn => {
                btn.addEventListener('click', function() {
                    document.getElementById('alert-toast').remove();
                });
            });
        </script>
    @endif
</body>
</html>
