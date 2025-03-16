<!-- filepath: g:\projects\restaurant-system\resources\views\welcome.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid p-0">
        <!-- Hero Section -->
        <section class="hero ">
            <div class="hero-content">
                <h1>Exquisite Dining Experience</h1>
                <p>Indulge in a culinary journey with our finest selections of dishes prepared by world-class chefs.</p>
                <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">Explore Our Menu</a>
            </div>
        </section>

        <!-- Feature Section -->
        <section id="about" class="feature-section">
            <div class="container">
                <div class="row mb-5 text-center">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title text-center mb-4">Why Choose Us</h2>
                        <p class="lead text-muted">Experience the perfect blend of exquisite flavors, exceptional service,
                            and a warm ambiance that makes us stand out.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 fade-in">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-utensils"></i>
                            </div>
                            <h3>Quality Food</h3>
                            <p>Made with premium ingredients and prepared by expert chefs to ensure every bite is
                                exceptional.</p>
                        </div>
                    </div>

                    <div class="col-md-4 fade-in" style="animation-delay: 0.2s;">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-clock"></i>
                            </div>
                            <h3>Fast Service</h3>
                            <p>Our dedicated staff ensures prompt service without compromising on quality or attention to
                                detail.</p>
                        </div>
                    </div>

                    <div class="col-md-4 fade-in" style="animation-delay: 0.4s;">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-star"></i>
                            </div>
                            <h3>5-Star Experience</h3>
                            <p>Enjoy a premium dining experience with an ambiance that complements our exceptional cuisine.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Menu Preview Section -->
        <section id="menu" class="menu-preview">
            <div class="container">
                <div class="row mb-5 text-center">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title text-center mb-4">Our Menu Highlights</h2>
                        <p class="lead text-muted">Explore a selection of our most popular dishes that have customers coming
                            back for more.</p>
                    </div>
                </div>

                <div class="row">
                    @php
                        // Get some featured menu items
                        $featuredItems = App\Models\Item::where('available', true)->inRandomOrder()->take(6)->get();
                    @endphp

                    @if ($featuredItems->count() > 0)
                        @foreach ($featuredItems as $item)
                            <div class="col-lg-4 col-md-6 fade-in" style="animation-delay: {{ $loop->index * 0.15 }}s;">
                                <div class="menu-item">
                                    <div class="menu-image">
                                        @if ($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}">
                                        @else
                                            <img src="https://via.placeholder.com/300x200?text=No+Image"
                                                alt="{{ $item->name }}">
                                        @endif
                                    </div>
                                    <div class="menu-info">
                                        <h4 class="menu-title">{{ $item->name }}</h4>
                                        <div class="menu-price">${{ number_format($item->price, 2) }}</div>
                                        <p class="menu-description">{{ Str::limit($item->description, 100) }}</p>
                                        <a href="{{ route('menu') }}" class="btn btn-outline-primary btn-sm">View
                                            Details</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center">
                            <p>No menu items available at the moment. Please check back later!</p>
                            <a href="{{ route('menu') }}" class="btn btn-primary mt-3">View Full Menu</a>
                        </div>
                    @endif
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('menu') }}" class="btn btn-primary btn-lg">View Complete Menu</a>
                </div>
            </div>
        </section>

        <!-- Testimonials Section -->
        <section id="testimonials" class="testimonials">
            <div class="container">
                <div class="row mb-5 text-center">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title text-center mb-4 text-white">What Our Customers Say</h2>
                        <p class="lead">Don't just take our word for it - here's what our customers think about their
                            dining experience.</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 fade-in">
                        <div class="testimonial-card">
                            <div class="testimonial-text">
                                The ambiance and food quality are absolutely stellar. Their signature dish is something I
                                crave weekly!
                            </div>
                            <div class="testimonial-author">
                                <div class="author-image">
                                    <img src="https://randomuser.me/api/portraits/women/11.jpg" alt="Sarah Johnson">
                                </div>
                                <div>
                                    <h5 class="mb-0">Sarah Johnson</h5>
                                    <small>Food Critic</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 fade-in" style="animation-delay: 0.2s;">
                        <div class="testimonial-card">
                            <div class="testimonial-text">
                                I've been coming here for years and have never been disappointed. The customer service is
                                phenomenal!
                            </div>
                            <div class="testimonial-author">
                                <div class="author-image">
                                    <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Robert Chen">
                                </div>
                                <div>
                                    <h5 class="mb-0">Robert Chen</h5>
                                    <small>Regular Customer</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4 fade-in" style="animation-delay: 0.4s;">
                        <div class="testimonial-card">
                            <div class="testimonial-text">
                                The vegetarian options here are incredible. As someone with dietary restrictions, I always
                                feel catered to.
                            </div>
                            <div class="testimonial-author">
                                <div class="author-image">
                                    <img src="https://randomuser.me/api/portraits/women/68.jpg" alt="Emma Wilson">
                                </div>
                                <div>
                                    <h5 class="mb-0">Emma Wilson</h5>
                                    <small>Food Blogger</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="contact-section">
            <div class="container">
                <div class="row mb-5 text-center">
                    <div class="col-lg-8 mx-auto">
                        <h2 class="section-title text-center mb-4">Get In Touch</h2>
                        <p class="lead text-muted">Have questions or want to make a reservation? Contact us today!</p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 fade-in">
                        <div class="contact-info">
                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-map-marker-alt"></i>
                                </div>
                                <div>
                                    <h5>Location</h5>
                                    <p>63WG+WM8, Erbil, Erbil Governorate</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-phone-alt"></i>
                                </div>
                                <div>
                                    <h5>Phone</h5>
                                    <p>(123) 456-7890</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-envelope"></i>
                                </div>
                                <div>
                                    <h5>Email</h5>
                                    <p>info@yourrestaurant.com</p>
                                </div>
                            </div>

                            <div class="contact-item">
                                <div class="contact-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div>
                                    <h5>Hours</h5>
                                    <p>Monday - Friday: 11:00 AM - 10:00 PM<br>
                                        Saturday - Sunday: 10:00 AM - 11:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 fade-in" style="animation-delay: 0.3s;">
                        <div class="map-container">
                            <iframe
                                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d920.368575970919!2d44.07686661314782!3d36.24720843164963!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x40072017da2ff1db%3A0xc24cd7c19b21b4ee!2sKurdistan%20Private%20Institute%20for%20Computer%20Sciences!5e0!3m2!1sen!2siq!4v1741953220649!5m2!1sen!2siq"
                                width="100%" height="400" style="border:0; border-radius: 10px;" allowfullscreen=""
                                loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Section -->
        <section class="cta-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 mx-auto">
                        <h2>Ready to Experience Culinary Excellence?</h2>
                        <p class="mb-4">Join us for an unforgettable dining experience or explore our menu online.</p>
                        @guest
                        <a href="{{ route('login') }}" class="btn btn-light btn-lg me-2">Sign In</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light btn-lg">Register</a>
                        @endguest
                        <a href="{{ route('menu') }}" class="btn btn-outline-light btn-lg">Browse Menu</a>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@push('styles')
    <style>
        /* Hero Section */
        .hero {
            background: url('https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80') no-repeat center center/cover;
            height: 100vh;
            position: relative;
            color: white;
            display: flex;
            align-items: center;
            text-align: center;
            /* REMOVE THIS LINE that's causing the problem */
            margin-top: -70px; 
            padding-top: 60px;
            /* Add some padding instead */
        }

        /* Make sure the z-index doesn't interfere with navigation */
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
            /* Lower z-index */
        }

        .hero-content {
            position: relative;
            z-index: 2;
            /* Higher than the overlay but lower than navbar */
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Add these to ensure navbar visibility */
        .navbar {
            position: relative;
            z-index: 10;
            /* Higher z-index to ensure it's above hero */
        }

        .hero h1 {
            font-family: var(--accent-font, 'Playfair Display', serif);
            font-size: 4rem;
            margin-bottom: 1rem;
            font-weight: 700;
            color: white !important;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            font-weight: 300;
        }

        /* Feature Sections */
        .feature-section {
            padding: 6rem 0;
        }

        .section-title {
            font-family: var(--accent-font, 'Playfair Display', serif);
            font-size: 2.5rem;
            margin-bottom: 1rem;
            position: relative;
            display: inline-block;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 60px;
            height: 3px;
            background-color: var(--bs-primary);
        }

        .feature-card {
            text-align: center;
            padding: 1.5rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .feature-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
            color: var(--bs-primary);
        }

        /* Menu Preview Section */
        .menu-preview {
            background-color: #f8f9fa;
            padding: 6rem 0;
        }

        .menu-item {
            background-color: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
            transition: all 0.3s ease;
        }

        .menu-item:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        }

        .menu-image {
            height: 200px;
            overflow: hidden;
        }

        .menu-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.5s ease;
        }

        .menu-item:hover .menu-image img {
            transform: scale(1.1);
        }

        .menu-info {
            padding: 1.5rem;
        }

        .menu-title {
            font-weight: 600;
            margin-bottom: 0.5rem;
        }

        .menu-price {
            color: var(--bs-primary);
            font-weight: 700;
            font-size: 1.2rem;
            margin-bottom: 0.5rem;
        }

        .menu-description {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 1rem;
        }

        /* Testimonials Section */
        .testimonials {
            padding: 6rem 0;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                url('https://images.unsplash.com/photo-1555396273-367ea4eb4db5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1474&q=80') no-repeat center center/cover;
            color: white;
        }

        .testimonial-card {
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 2rem;
            backdrop-filter: blur(5px);
            margin-bottom: 1.5rem;
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 1rem;
            position: relative;
        }

        .testimonial-text::before {
            content: '\201C';
            font-size: 4rem;
            font-family: Georgia, serif;
            position: absolute;
            top: -20px;
            left: -20px;
            opacity: 0.2;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
        }

        .author-image {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
            margin-right: 1rem;
        }

        .author-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Contact Section */
        .contact-section {
            padding: 6rem 0;
        }

        .contact-info {
            margin-bottom: 2rem;
        }

        .contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .contact-icon {
            font-size: 1.5rem;
            color: var(--bs-primary);
            margin-right: 1rem;
        }

        .map-container {
            height: 400px;
            border-radius: 10px;
            overflow: hidden;
        }

        /* CTA Section */
        .cta-section {
            background-color: var(--bs-primary);
            padding: 4rem 0;
            color: white;
            text-align: center;
        }

        .cta-section h2 {
            font-family: var(--accent-font, 'Playfair Display', serif);
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        /* Animation */
        .fade-in {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.6s ease-out, transform 0.6s ease-out;
        }

        .fade-in.active {
            opacity: 1;
            transform: translateY(0);
        }

        /* Responsive styles for the sidebar layout */
        @media (max-width: 991.98px) {
            .hero h1 {
                font-size: 3rem;
            }

            .container-fluid {
                padding-left: 0;
                padding-right: 0;
            }
        }

        @media (max-width: 767.98px) {
            .hero h1 {
                font-size: 2.5rem;
            }
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animation on Scroll
            const fadeElements = document.querySelectorAll('.fade-in');

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('active');
                    }
                });
            }, {
                threshold: 0.1
            });

            fadeElements.forEach(element => {
                observer.observe(element);
            });
        });
    </script>
@endpush
