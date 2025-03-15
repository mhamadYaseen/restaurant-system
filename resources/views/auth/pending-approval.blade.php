<!-- filepath: g:\projects\restaurant-system\resources\views\auth\pending-approval.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5 text-center">
                    <div class="mb-4">
                        <i class="fas fa-user-clock text-warning" style="font-size: 4rem;"></i>
                    </div>
                    <h2 class="mb-3">Account Pending Approval</h2>
                    <p class="mb-4 text-muted">
                        Thank you for registering! Your account is currently pending approval from an administrator. 
                        You'll be able to access all features once your account has been approved.
                    </p>
                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('menu') }}" class="btn btn-outline-primary">
                            <i class="fas fa-utensils me-2"></i> Browse Menu
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-secondary">
                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-body p-4">
                    <h5 class="card-title"><i class="fas fa-question-circle me-2"></i> Frequently Asked Questions</h5>
                    
                    <div class="mt-3">
                        <h6 class="fw-bold">How long does approval take?</h6>
                        <p class="text-muted">Typically, approvals are processed within 24-48 hours.</p>
                    </div>
                    
                    <div class="mt-3">
                        <h6 class="fw-bold">What can I do while waiting for approval?</h6>
                        <p class="text-muted">You can browse our menu and explore our website in the meantime.</p>
                    </div>
                    
                    <div class="mt-3">
                        <h6 class="fw-bold">Need help?</h6>
                        <p class="text-muted">Contact us at support@restaurant.com for any questions.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection