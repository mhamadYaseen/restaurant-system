<!-- filepath: g:\projects\restaurant-system\resources\views\layouts\app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

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

        <!-- filepath: g:\projects\restaurant-system\resources\views\layouts\app.blade.php -->
        <style>
            :root {
                /* Sandy/Earthy Color Palette */
                --bs-primary: #C8A27D;
                /* Warm sand/caramel */
                --bs-secondary: #5D4B35;
                /* Dark cedar brown */
                --bs-success: #7FA053;
                /* Olive green */
                --bs-warning: #E6B35A;
                /* Golden sand */
                --bs-light: #F7F3EB;
                /* Off-white/cream */
                --bs-dark: #3D3325;
                /* Deep brown */
                --bs-accent: #D99559;
                /* Terracotta/clay */
            }

            html,
            body {
                height: 100%;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background-color: var(--bs-light);
                padding-left: 250px;
                transition: padding-left 0.3s ease;
                display: flex;
                flex-direction: column;
            }

            h1,
            h2,
            h3,
            h4,
            h5,
            h6 {
                font-family: 'Playfair Display', serif;
                color: black;
            }

            /* Main content wrapper */
            .content-wrapper {
                flex: 1 0 auto;
            }

            /* Sidebar navigation */
            .sidebar {
                height: 100%;
                width: 250px;
                position: fixed;
                top: 0;
                left: 0;
                z-index: 1000;
                background-color: var(--bs-secondary);
                overflow-y: auto;
                transition: all 0.3s ease;
                box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
                background-image: linear-gradient(to bottom, var(--bs-secondary), #4A3C2A);
            }

            .sidebar-logo {
                padding: 1.5rem;
                background-color: rgba(0, 0, 0, 0.15);
                display: flex;
                align-items: center;
            }

            .sidebar-logo .navbar-brand {
                font-family: 'Playfair Display', serif;
                font-weight: 700;
                font-size: 1.5rem;
                color: var(--bs-warning);
            }

            .sidebar .nav-link {
                color: rgba(255, 255, 255, 0.8);
                padding: 1rem 1.5rem;
                border-left: 3px solid transparent;
                transition: all 0.3s ease;
                font-weight: 500;
            }

            .sidebar .nav-link:hover {
                color: white;
                background-color: rgba(255, 255, 255, 0.1);
                border-left: 3px solid var(--bs-warning);
            }

            .sidebar .nav-link.active {
                color: white;
                background-color: rgba(255, 255, 255, 0.1);
                border-left: 3px solid var(--bs-primary);
                font-weight: 600;
            }

            .sidebar .nav-link i {
                margin-right: 10px;
                width: 20px;
                text-align: center;
            }

            .sidebar-footer {
                padding: 1rem;
                position: absolute;
                bottom: 0;
                width: 100%;
                background-color: rgba(0, 0, 0, 0.15);
            }

            /* Toggle button for mobile */
            .sidebar-toggle {
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 1001;
                display: none;
                background-color: var(--bs-primary);
                border-color: var(--bs-primary);
            }

            .sidebar-toggle:hover {
                background-color: var(--bs-accent);
                border-color: var(--bs-accent);
            }

            /* Custom card styling with Bootstrap base */
            .card {
                border-radius: 0.75rem;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
                border-color: rgba(93, 75, 53, 0.1);
                /* Light version of secondary */
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(93, 75, 53, 0.15);
            }

            .card .card-header {
                background-color: var(--bs-light);
                border-bottom: 1px solid rgba(93, 75, 53, 0.1);
            }

            /* Food menu styling */
            .menu-item {
                border-left: 3px solid var(--bs-warning);
                padding-left: 15px;
                margin-bottom: 1.5rem;
            }

            .menu-item-price {
                color: var(--bs-accent);
                font-weight: bold;
                font-size: 1.2rem;
            }

            /* Custom badges */
            .badge-special {
                background-color: var(--bs-warning);
            }

            .badge-veg {
                background-color: var(--bs-success);
            }

            /* Footer styling */
            .footer {
                flex-shrink: 0;
                background-color: var(--bs-secondary);
                color: var(--bs-light);
                padding: 2rem 0;
                width: 100%;
                margin-left: 0;
                background-image: linear-gradient(to bottom, #4A3C2A, var(--bs-secondary));
            }

            /* Add this style to fix the container inside the footer */
            .footer .container {
                max-width: 100%;
                padding-left: 2rem;
                padding-right: 2rem;
            }

            .footer a.text-white:hover {
                color: var(--bs-warning) !important;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            /* Button styling */
            .btn-primary {
                background-color: var(--bs-primary);
                border-color: var(--bs-primary);
            }

            .btn-primary:hover {
                background-color: var(--bs-accent);
                border-color: var(--bs-accent);
            }

            .btn-outline-primary {
                border-color: var(--bs-primary);
                color: var(--bs-primary);
            }

            .btn-outline-primary:hover {
                background-color: var(--bs-primary);
                color: white;
            }

            /* Responsive styles */
            @media (max-width: 991.98px) {
                body {
                    padding-left: 0;
                }

                .sidebar {
                    left: -250px;
                }

                .sidebar.show {
                    left: 0;
                }

                .sidebar-toggle {
                    display: block;
                }

                .footer {
                    width: 100%;
                }

                .footer .container {
                    max-width: 960px;
                    margin: 0 auto;
                }
            }

            .overlay {
                display: none;
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
            }

            .overlay.show {
                display: block;
            }
        </style>

        @stack('styles')
    </head>

    <body>
        <!-- Sidebar Toggle Button (visible on mobile only) -->
        <button class="btn btn-primary rounded-circle sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- Overlay for mobile -->
        <div class="overlay" id="overlay"></div>
        
        <!-- Sidebar Navigation -->
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
                        <a class="nav-link {{ request()->is('menu') ? 'active' : '' }}" href="{{ url('/menu') }}">
                            <i class="fas fa-book-open"></i> Menu
                        </a>
                    </li>

                    {{-- <hr class="my-3 bg-light opacity-25"> --}}

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

                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="fas fa-sign-in-alt"></i> Login
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                    @endguest
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

        <!-- Main content wrapper -->
        <div class="content-wrapper">
            <main class="py-4">
                @yield('content')
            </main>
        </div>

        <!-- Footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-0">&copy; {{ date('Y') }}
                                {{ config('app.name', 'Restaurant System') }}.
                                All rights reserved.</p>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-0">Designed with <i class="fas fa-heart text-danger"></i></p>
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

                sidebarToggle.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                    overlay.classList.toggle('show');
                });

                overlay.addEventListener('click', function() {
                    sidebar.classList.remove('show');
                    overlay.classList.remove('show');
                });
            });
        </script>

        @stack('scripts')
    </body>

</html>
