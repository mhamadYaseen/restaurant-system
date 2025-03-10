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

        <style>
            :root {
                --bs-primary: #D94E4E;
                --bs-secondary: #2C3E50;
                --bs-success: #27AE60;
                --bs-warning: #F39C12;
                --bs-light: #F5F5F5;
                --bs-dark: #333333;
            }

            html,
            body {
                height: 100%;
            }

            body {
                font-family: 'Poppins', sans-serif;
                background-color: var(--bs-light);
                padding-left: 250px;
                /* Make space for the sidebar */
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
            }

            .sidebar-logo {
                padding: 1.5rem;
                background-color: rgba(0, 0, 0, 0.1);
                display: flex;
                align-items: center;
            }

            .sidebar-logo .navbar-brand {
                font-family: 'Playfair Display', serif;
                font-weight: 700;
                font-size: 1.5rem;
                color: white;
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
                background-color: rgba(0, 0, 0, 0.1);
            }

            /* Toggle button for mobile */
            .sidebar-toggle {
                position: fixed;
                top: 10px;
                left: 10px;
                z-index: 1001;
                display: none;
            }

            /* Custom card styling with Bootstrap base */
            .card {
                border-radius: 0.75rem;
                transition: transform 0.3s ease, box-shadow 0.3s ease;
            }

            .card:hover {
                transform: translateY(-5px);
                box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
            }

            /* Food menu styling */
            .menu-item {
                border-left: 3px solid var(--bs-warning);
                padding-left: 15px;
                margin-bottom: 1.5rem;
            }

            .menu-item-price {
                color: var(--bs-primary);
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
                color: white;
                padding: 2rem 0;
                width: calc(100% - 250px);
                margin-left: 0;
            }

            /* Add this style to fix the container inside the footer */
            .footer .container {
                max-width: 100%;
                padding-left: 2rem;
                padding-right: 2rem;
            }

            .footer a.text-white:hover {
                color: var(--bs-primary) !important;
                text-decoration: none;
                transition: color 0.3s ease;
            }

            /* Responsive styles */
            @media (max-width: 991.98px) {
                /* Your other responsive styles... */

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

            .footer {
                width: 100%;
            }
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
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('reservations') ? 'active' : '' }}"
                            href="{{ url('/reservations') }}">
                            <i class="fas fa-calendar-alt"></i> Reservations
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ url('/about') }}">
                            <i class="fas fa-info-circle"></i> About
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->is('contact') ? 'active' : '' }}"
                            href="{{ url('/contact') }}">
                            <i class="fas fa-envelope"></i> Contact
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
                            @if (Auth::user()->isAdmin())
                                <a class="nav-link {{ request()->is('admin/dashboard') ? 'active' : '' }}"
                                    href="{{ url('/admin/dashboard') }}">
                                    <i class="fas fa-tachometer-alt"></i> Dashboard
                                </a>
                            @endif
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('profile') ? 'active' : '' }}"
                                href="{{ url('/profile') }}">
                                <i class="fas fa-user"></i> Profile
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('orders') ? 'active' : '' }}" href="{{ url('/orders') }}">
                                <i class="fas fa-receipt"></i> Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
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
                    <a href="#" class="text-white mx-2"><i class="fab fa-tripadvisor"></i></a>
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
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h4 class="text-warning mb-4">{{ config('app.name', 'Restaurant System') }}</h4>
                        <p>Experience the finest dining with our carefully crafted dishes prepared by expert chefs using
                            only the freshest ingredients.</p>
                    </div>
                    <div class="col-md-4 mb-4 mb-md-0">
                        <h5 class="mb-3">Hours</h5>
                        <ul class="list-unstyled">
                            <li><i class="far fa-clock me-2"></i> Monday - Friday: 11:00 AM - 10:00 PM</li>
                            <li><i class="far fa-clock me-2"></i> Saturday - Sunday: 10:00 AM - 11:00 PM</li>
                        </ul>
                        <h5 class="mb-3 mt-4">Contact</h5>
                        <ul class="list-unstyled">
                            <li><i class="fas fa-map-marker-alt me-2"></i> 123 Restaurant Street, City</li>
                            <li><i class="fas fa-phone me-2"></i> (123) 456-7890</li>
                            <li><i class="fas fa-envelope me-2"></i> info@restaurant.com</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <h5 class="mb-3">Quick Links</h5>
                        <ul class="list-unstyled">
                            <li><a href="{{ url('/privacy-policy') }}"
                                    class="text-white text-decoration-none mb-2 d-block"><i
                                        class="fas fa-chevron-right me-2"></i>Privacy Policy</a></li>
                            <li><a href="{{ url('/terms') }}" class="text-white text-decoration-none mb-2 d-block"><i
                                        class="fas fa-chevron-right me-2"></i>Terms & Conditions</a></li>
                        </ul>
                    </div>
                </div>
                <hr class="mt-4 mb-4" style="border-color: rgba(255,255,255,0.1);">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name', 'Restaurant System') }}.
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
