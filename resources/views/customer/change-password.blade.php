@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <!-- Card Header with Gradient Background -->
                <div class="card-header py-3" style="background: linear-gradient(to right, #8B4513, #A67B5B);">
                    <h3 class="mb-0 text-white">
                        <i class="bi bi-lock-fill me-2"></i>Change Password
                    </h3>
                </div>

                <!-- Card Body -->
                <div class="card-body" style="background-color: #F5F5DC;">
                    <!-- Success/Error Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="mt-2">
                                <a href="{{ route('profile') }}" class="btn btn-brown btn-sm">Back to Profile</a>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Password Change Form -->
                    <form action="{{ route('profile.update-password') }}" method="POST">
                        @csrf

                        <!-- Current Password -->
                        <div class="row g-3">
                            <div class="col-md-12">
                                <div class="form-floating">
                                    <input type="password" class="form-control bg-light-brown border-brown @error('current_password') is-invalid @enderror" 
                                           id="current_password" name="current_password" required>
                                    <label for="current_password" class="text-brown">
                                        <i class="bi bi-lock-fill me-2"></i>Current Password
                                    </label>
                                    @error('current_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- New Password -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control bg-light-brown border-brown @error('new_password') is-invalid @enderror" 
                                           id="new_password" name="new_password" required>
                                    <label for="new_password" class="text-brown">
                                        <i class="bi bi-lock-fill me-2"></i>New Password
                                    </label>
                                    @error('new_password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Confirm New Password -->
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="password" class="form-control bg-light-brown border-brown" 
                                           id="new_password_confirmation" name="new_password_confirmation" required>
                                    <label for="new_password_confirmation" class="text-brown">
                                        <i class="bi bi-lock-fill me-2"></i>Confirm New Password
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top border-brown">
                            <a href="{{ route('profile') }}" class="btn btn-outline-brown">Cancel</a>
                            <button type="submit" class="btn btn-brown">
                                <i class="bi bi-save-fill me-2"></i>Change Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Custom Brown Theme Colors */
    .bg-brown { background-color: #8B4513; }
    .bg-light-brown { background-color: #F5DEB3; }
    .border-brown { border-color: #A67B5B !important; }
    .text-brown { color: #5A3E36; }
    .btn-brown { 
        background-color: #A67B5B; 
        border-color: #8B4513;
        color: white;
    }
    .btn-brown:hover {
        background-color: #8B4513;
        border-color: #5A3E36;
        color: white;
    }
    .btn-outline-brown {
        border-color: #A67B5B;
        color: #5A3E36;
    }
    .btn-outline-brown:hover {
        background-color: #F5DEB3;
        border-color: #8B4513;
    }
</style>
@endsection