<!-- filepath: g:\projects\restaurant-system\resources\views\auth\forgot-password.blade.php -->
@extends('layouts.guest-custom')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                <div class="card login-card border-0 shadow-lg">
                    <div class="card-header text-center bg-transparent border-0 pt-4">
                        <div class="logo-wrapper mb-4">
                            <i class="fas fa-utensils restaurant-logo"></i>
                        </div>
                        <h2 class="fw-bold">{{ __('Reset Password') }}</h2>
                        <p class="text-muted">Enter your email to receive a password reset link</p>
                    </div>

                    <div class="card-body p-4 p-lg-5">
                        @if (session('status'))
                            <div class="alert alert-success mb-4">
                                <i class="fas fa-check-circle me-2"></i>{{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf

                            <div class="mb-4">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="fas fa-envelope text-muted"></i>
                                    </span>
                                    <input id="email" type="email"
                                        class="form-control border-start-0 @error('email') is-invalid @enderror"
                                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                </div>
                                @error('email')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-0">
                                <button type="submit" class="btn btn-primary w-100 py-2">
                                    <i class="fas fa-paper-plane me-2"></i>{{ __('Send Reset Link') }}
                                </button>
                            </div>
                        </form>
                    </div>

                    <div class="card-footer bg-transparent text-center border-0 pb-4">
                        <p class="mb-2">Remember your password? <a href="{{ route('login') }}"
                                class="text-decoration-none">Return to login</a></p>
                        <p class="text-muted small">&copy; {{ date('Y') }} {{ config('app.name', 'Restaurant') }}. All
                            rights reserved.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <style>
        html,
        body {
            overflow: hidden;
            height: 100%;
        }

        .container {
            max-height: 100vh;
            overflow-y: auto;
        }

        body {
            background: url('/images/restaurant-bg.jpg') center/cover no-repeat fixed;
            position: relative;
        }

        body::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: -1;
        }

        .login-card {
            margin-top: 5rem;
            margin-bottom: 5rem;
            border-radius: 15px;
            overflow: hidden;
            background-color: rgba(255, 255, 255, 0.98);
        }

        .restaurant-logo {
            font-size: 3rem;
            color: var(--bs-primary);
            background-color: rgba(200, 162, 125, 0.1);
            width: 90px;
            height: 90px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            margin: 0 auto;
        }

        .logo-wrapper {
            position: relative;
        }

        .logo-wrapper:before,
        .logo-wrapper:after {
            content: "";
            position: absolute;
            height: 2px;
            width: 30%;
            top: 50%;
            background: linear-gradient(to right, transparent, var(--bs-primary), transparent);
        }

        .logo-wrapper:before {
            left: 0;
        }

        .logo-wrapper:after {
            right: 0;
        }

        .input-group-text {
            border-color: #ced4da;
        }

        .btn-primary {
            background-color: var(--bs-primary);
            border-color: var(--bs-primary);
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--bs-accent);
            border-color: var(--bs-accent);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
    </style>
@endpush