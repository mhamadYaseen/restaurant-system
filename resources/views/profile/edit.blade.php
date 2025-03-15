<!-- filepath: g:\projects\restaurant-system\resources\views\profile\edit.blade.php -->
@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col">
            <h2 class="fw-bold">My Profile</h2>
            <p class="text-muted">Manage your account details and preferences</p>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <!-- Profile Information -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0"><i class="fas fa-user me-2"></i> Profile Information</h5>
                </div>
                <div class="card-body p-4">
                    @if(session('status') === 'profile-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> Profile updated successfully
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="post" action="{{ route('profile.update') }}" id="profile-form">
                        @csrf
                        @method('patch')

                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                <div class="alert alert-warning mt-2">
                                    <div class="d-flex">
                                        <div class="me-2"><i class="fas fa-exclamation-triangle"></i></div>
                                        <div>
                                            <div>Your email address is unverified.</div>
                                            <form method="POST" action="{{ route('verification.send') }}">
                                                @csrf
                                                <button type="submit" class="btn btn-link p-0">
                                                    Click here to re-send the verification email
                                                </button>
                                            </form>
                                            
                                            @if (session('status') === 'verification-link-sent')
                                                <div class="text-success mt-1">
                                                    A new verification link has been sent to your email address.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Account Type</label>
                            <div class="form-control bg-light">{{ ucfirst($user->role) }} Account</div>
                            <div class="form-text text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Account type can only be changed by an administrator.
                            </div>
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Save Changes
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update Password -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0">
                    <h5 class="mb-0"><i class="fas fa-lock me-2"></i> Update Password</h5>
                </div>
                <div class="card-body p-4">
                    @if(session('status') === 'password-updated')
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> Password updated successfully
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form method="post" action="{{ route('password.update') }}" id="password-form">
                        @csrf
                        @method('put')

                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" 
                                id="current_password" name="current_password" required>
                            @error('current_password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" 
                                id="password" name="password" required>
                            @error('password', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control @error('password_confirmation', 'updatePassword') is-invalid @enderror" 
                                id="password_confirmation" name="password_confirmation" required>
                            @error('password_confirmation', 'updatePassword')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-key me-2"></i> Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Profile Summary Card -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="mb-0"><i class="fas fa-user-circle me-2"></i> Your Account</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="user-avatar bg-primary bg-opacity-10 rounded-circle text-center me-3" 
                             style="width: 80px; height: 80px; line-height: 80px; font-size: 2rem;">
                            <span class="fw-bold text-primary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        </div>
                        <div>
                            <h5 class="mb-1">{{ $user->name }}</h5>
                            <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : ($user->role === 'user' ? 'bg-success' : 'bg-warning') }}">
                                {{ ucfirst($user->role) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h6 class="text-muted small text-uppercase">Account Details</h6>
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-light rounded p-1 me-2">
                                        <i class="fas fa-envelope text-secondary"></i>
                                    </div>
                                    <div class="small">{{ $user->email }}</div>
                                </div>
                            </li>
                            <li class="mb-2">
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-light rounded p-1 me-2">
                                        <i class="fas fa-calendar text-secondary"></i>
                                    </div>
                                    <div class="small">Joined {{ $user->created_at->format('M d, Y') }}</div>
                                </div>
                            </li>
                            @if($user->email_verified_at)
                            <li>
                                <div class="d-flex align-items-center">
                                    <div class="icon-box bg-light rounded p-1 me-2">
                                        <i class="fas fa-check-circle text-success"></i>
                                    </div>
                                    <div class="small">Email verified on {{ $user->email_verified_at->format('M d, Y') }}</div>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Delete Account Card -->
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-header bg-transparent border-0 pt-4">
                    <h5 class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Danger Zone</h5>
                </div>
                <div class="card-body">
                    <p>Permanently delete your account and all associated data.</p>
                    <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteAccountModal">
                        <i class="fas fa-trash-alt me-2"></i> Delete My Account
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Account Modal -->
<div class="modal fade" id="deleteAccountModal" tabindex="-1" aria-labelledby="deleteAccountModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger" id="deleteAccountModalLabel">
                    <i class="fas fa-exclamation-triangle me-2"></i> Confirm Delete
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete your account?</p>
                <p class="text-danger mb-2"><strong>Warning:</strong> This action cannot be undone and will permanently delete your account and all associated data.</p>
                
                <form method="post" action="{{ route('profile.destroy') }}" id="delete-form">
                    @csrf
                    @method('delete')
                    
                    <div class="mb-3">
                        <label for="delete_password" class="form-label">Please enter your password to confirm</label>
                        <input type="password" class="form-control @error('password', 'userDeletion') is-invalid @enderror" 
                            id="delete_password" name="password" required>
                        @error('password', 'userDeletion')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="submit" class="btn btn-danger" form="delete-form">
                    <i class="fas fa-trash-alt me-2"></i> Delete Account
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Auto-hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush