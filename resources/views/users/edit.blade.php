<!-- filepath: g:\projects\restaurant-system\resources\views\users\edit.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="container-fluid py-4">
        <div class="row mb-4">
            <div class="col">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('users.index') }}">Users</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit User</li>
                    </ol>
                </nav>
                <h2 class="fw-bold">Edit User</h2>
                <p class="text-muted">Update user account details</p>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-4">
                        <form action="{{ route('users.update', $user) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="mb-3">
                                <label for="name" class="form-label">Full Name</label>
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
                            </div>

                            <div class="mb-3">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <label class="form-label mb-0">Password</label>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input" type="checkbox" id="changePassword">
                                        <label class="form-check-label text-muted" for="changePassword">Change
                                            password</label>
                                    </div>
                                </div>

                                <div id="passwordFields" class="d-none">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="password" class="form-label">New Password</label>
                                            <div class="input-group">
                                                <input type="password"
                                                    class="form-control @error('password') is-invalid @enderror"
                                                    id="password" name="password" disabled>
                                                <button class="btn btn-outline-secondary toggle-password" type="button">
                                                    <i class="fas fa-eye-slash"></i>
                                                </button>
                                            </div>
                                            @error('password')
                                                <div class="text-danger small mt-1">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        <div class="col-md-6 mb-3">
                                            <label for="password_confirmation" class="form-label">Confirm New
                                                Password</label>
                                            <input type="password" class="form-control" id="password_confirmation"
                                                name="password_confirmation" disabled>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            
                            <div class="mb-4">
                                <label for="role" class="form-label">User Role</label>
                                <select class="form-select @error('role') is-invalid @enderror" id="role"
                                    name="role" {{ auth()->id() == $user->id ? 'disabled' : '' }}>
                                    <option value="pending" {{ old('role', $user->role) == 'pending' ? 'selected' : '' }}>
                                        Pending Approval</option>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User
                                    </option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Administrator</option>
                                </select>
                                @if (auth()->id() == $user->id)
                                    <input type="hidden" name="role" value="{{ $user->role }}">
                                    <div class="form-text text-muted">
                                        <i class="fas fa-lock me-1"></i> You cannot change your own role.
                                    </div>
                                @endif
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <hr>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                                    <i class="fas fa-arrow-left me-2"></i> Cancel
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i> Update User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-transparent border-0 pt-4">
                        <h5 class="mb-0"><i class="fas fa-user me-2"></i> User Information</h5>
                    </div>
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="user-avatar bg-light rounded-circle text-center me-3"
                                style="width: 60px; height: 60px; line-height: 60px; font-size: 24px;">
                                <span class="fw-bold text-secondary">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                            </div>
                            <div>
                                <h6 class="mb-1">{{ $user->name }}</h6>
                                <span class="badge {{ $user->role === 'admin' ? 'bg-primary' : 'bg-secondary' }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-borderless table-sm mb-0">
                                <tr>
                                    <td class="text-muted" width="40%">Email:</td>
                                    <td>{{ $user->email }}</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Joined:</td>
                                    <td>{{ $user->created_at->format('M d, Y') }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>

                @if (auth()->id() != $user->id)
                    <div class="card border-0 shadow-sm border-danger">
                        <div class="card-header bg-transparent border-0 pt-4">
                            <h5 class="text-danger mb-0"><i class="fas fa-exclamation-triangle me-2"></i> Danger Zone</h5>
                        </div>
                        <div class="card-body">
                            <p>Permanently delete this user and all their data.</p>
                            <button type="button" class="btn btn-outline-danger w-100" data-bs-toggle="modal"
                                data-bs-target="#deleteUserModal">
                                <i class="fas fa-trash-alt me-2"></i> Delete User
                            </button>
                        </div>
                    </div>

                    <!-- Delete Confirmation Modal -->
                    <div class="modal fade" id="deleteUserModal" tabindex="-1" aria-labelledby="deleteUserModalLabel"
                        aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title text-danger" id="deleteUserModalLabel">
                                        <i class="fas fa-exclamation-triangle me-2"></i> Delete User
                                    </h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <p>Are you sure you want to delete <strong>{{ $user->name }}</strong>?</p>
                                    <p class="mb-0 text-danger"><strong>Warning:</strong> This action cannot be undone and
                                        will permanently delete all data associated with this user.</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Cancel</button>
                                    <form action="{{ route('users.destroy', $user) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Delete Permanently</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Password change toggle
                const changePasswordSwitch = document.getElementById('changePassword');
                const passwordFields = document.getElementById('passwordFields');
                const passwordInput = document.getElementById('password');
                const passwordConfirmation = document.getElementById('password_confirmation');

                changePasswordSwitch.addEventListener('change', function() {
                    if (this.checked) {
                        passwordFields.classList.remove('d-none');
                        passwordInput.disabled = false;
                        passwordConfirmation.disabled = false;
                    } else {
                        passwordFields.classList.add('d-none');
                        passwordInput.disabled = true;
                        passwordConfirmation.disabled = true;
                        passwordInput.value = '';
                        passwordConfirmation.value = '';
                    }
                });

                // Password visibility toggle
                const togglePassword = document.querySelector('.toggle-password');

                togglePassword.addEventListener('click', function() {
                    const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                    passwordInput.setAttribute('type', type);
                    this.firstElementChild.classList.toggle('fa-eye-slash');
                });
            });
        </script>
    @endpush
@endsection
