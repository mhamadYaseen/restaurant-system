<!-- filepath: g:\projects\restaurant-system\resources\views\layouts\app.blade.php -->
<!DOCTYPE html>
<body class="{{ Auth::check() && Auth::user()->role == 'admin' ? 'with-sidebar h-100' : 'no-sidebar h-100' }} d-flex flex-column">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Restaurant System') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&display=swap"
            rel="stylesheet">

        <!-- Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

        {{-- css Links --}}
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/menu.css') }}">
        <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

        @stack('styles')
    </head>

    <body class="{{ Auth::check() && Auth::user()->role == 'admin' ? 'with-sidebar' : 'no-sidebar' }}">

        @if (Auth::check() && Auth::user()->role == 'admin')
            <!-- Sidebar Toggle Button (visible on mobile only) -->
            <button class="btn btn-primary rounded-circle sidebar-toggle" id="sidebarToggle">
                <i class="fas fa-bars"></i>
            </button>

            <!-- Overlay for mobile -->
            <div class="overlay" id="overlay"></div>

            <!-- Sidebar Navigation for Admins -->
            <div class="sidebar" id="sidebar">
                <div class="sidebar-logo">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        <i class="fas fa-utensils me-2"></i>{{ config('app.name', 'Restaurant') }}
                    </a>
                </div>

                <!-- Management Section -->
                <li class="nav-item">
                    <div class="px-3 py-2">
                        <small class="text-uppercase text-light opacity-50">Management</small>
                    </div>
                </li>
                <div class="mt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('/') ? 'active' : '' }}" href="{{ url('/') }}">
                                <i class="fas fa-home"></i> Home
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dashboard') ? 'active' : '' }}"
                                href="{{ url('/dashboard') }}">
                                <i class="fas fa-tachometer-alt"></i> Dashboard
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('menu') ? 'active' : '' }}"
                                href="{{ url('/menu') }}">
                                <i class="fas fa-book-open"></i> Menu
                            </a>
                        </li>

                        <hr class="my-3 bg-light opacity-25">

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('categories*') ? 'active' : '' }}"
                                href="{{ route('categories.index') }}">
                                <i class="fas fa-tags"></i> Categories
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('items*') ? 'active' : '' }}"
                                href="{{ route('items.index') }}">
                                <i class="fas fa-utensils"></i> Items
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('orders*') ? 'active' : '' }}"
                                href="{{ route('orders.index') }}">
                                <i class="fas fa-clipboard-list"></i> Orders
                            </a>
                        </li>
                        <hr class="my-3 bg-light opacity-25">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}"
                                href="{{ route('profile.edit') }}">
                                <i class="fas fa-user-circle"></i> My Profile
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}"
                                href="{{ route('users.index') }}">
                                <i class="fas fa-users"></i> Users
                            </a>
                        </li>

                        <li class="nav-item">
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            <a class="nav-link" href="#"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Social media icons in sidebar footer -->
                <div class="sidebar-footer text-center">
                    <div>
                        <a href="#" class="text-white mx-2"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white mx-2"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
        @else
            <!-- Top Navigation Bar for Guests and Non-Admin Users -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
                <div class="container">
                    <a class="navbar-brand fw-bold" href="{{ url('/') }}">
                        <i class="fas fa-utensils me-2 text-primary"></i>{{ config('app.name', 'Restaurant') }}
                    </a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav ms-auto">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('/') ? 'active' : '' }}"
                                    href="{{ url('/') }}">
                                    <i class="fas fa-home"></i> Home
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->is('menu') ? 'active' : '' }}"
                                    href="{{ url('/menu') }}">
                                    <i class="fas fa-book-open"></i> Menu
                                </a>
                            </li>
                            @auth
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->is('profile*') ? 'active' : '' }}"
                                        href="{{ route('profile.edit') }}">
                                        <i class="fas fa-user-circle"></i> My Profile
                                    </a>
                                </li>
                                @if (Auth::user()->role === 'pending')
                                    <li class="nav-item">
                                        <a class="nav-link {{ request()->is('pending-approval') ? 'active' : '' }}"
                                            href="{{ route('pending-approval') }}">
                                            <i class="fas fa-clock"></i> Approval Status
                                        </a>
                                    </li>
                                @endif
                                <li class="nav-item">
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                        class="d-none">
                                        @csrf
                                    </form>
                                    <a class="nav-link" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        <i class="fas fa-sign-out-alt"></i> Logout
                                    </a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt"></i> Login
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus"></i> Register
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>
        @endif

        <!-- Main content wrapper -->
        <div class="content-wrapper flex-grow-1">
            <main class="py-4">
                @yield('content')
            </main>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }}
                            {{ config('app.name', 'Restaurant System') }}.
                            All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-0">Designed with <b>Laravel</b></p>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS Bundle with Popper -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <!-- Sidebar Toggle Script -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebarToggle = document.getElementById('sidebarToggle');
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');

                // Only execute if sidebar exists (for admin users)
                if (sidebar) {
                    // Function to check viewport width
                    function isMobile() {
                        return window.innerWidth < 992;
                    }

                    // Initialize sidebar state based on screen size
                    function initSidebarState() {
                        if (isMobile()) {
                            sidebar.classList.remove('show');
                            console.log('Mobile detected - sidebar hidden');
                        } else {
                            sidebar.classList.add('show');
                            console.log('Desktop detected - sidebar visible');
                        }
                    }

                    // Call once when page loads
                    initSidebarState();

                    // Toggle sidebar on button click
                    if (sidebarToggle) {
                        sidebarToggle.addEventListener('click', function() {
                            console.log('Toggle clicked');
                            sidebar.classList.toggle('show');
                            overlay.classList.toggle('show');
                        });
                    }

                    // Hide sidebar when clicking overlay
                    if (overlay) {
                        overlay.addEventListener('click', function() {
                            console.log('Overlay clicked');
                            sidebar.classList.remove('show');
                            overlay.classList.remove('show');
                        });
                    }

                    // Update sidebar state when window is resized
                    window.addEventListener('resize', function() {
                        console.log('Window resized');
                        initSidebarState();
                        if (!isMobile()) {
                            // When switching to desktop view, hide overlay
                            if (overlay) {
                                overlay.classList.remove('show');
                            }
                        }
                    });
                }
            });
        </script>

        <!-- Include menu.js script -->
        <script src="{{ asset('js/menu.js') }}"></script>
        @stack('scripts')

    </body>

</html>
