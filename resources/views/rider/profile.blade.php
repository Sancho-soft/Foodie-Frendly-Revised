@extends('layouts.rider')

@section('title', 'My Profile')

@section('content')
<div class="container-fluid mt-4"> {{-- Changed to fluid for full-width responsiveness --}}
    <div class="row justify-content-center">
        <div class="col-lg-10 col-xl-9"> {{-- Increased column width --}}
            <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden; background-color: #F5F5DC;">
                <div class="card-header py-3" style="background: linear-gradient(to right, #8B4513, #A67B5B);">
                    <h3 class="mb-0 text-white">
                        <i class="bi bi-person-circle me-2"></i>My Profile
                    </h3>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <div class="row g-3">
                        <div class="col-md-6">
                            <p><strong>Full Name:</strong> {{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Email:</strong> {{ $user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Phone Number:</strong> {{ $user->rider->phone_number ?? 'Not set' }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Role:</strong> {{ ucfirst($user->role) }}</p>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between mt-4 pt-3 border-top border-brown">
                        <a href="{{ route('rider.index') }}" class="btn btn-outline-brown">Back to Dashboard</a>
                        <div>
                            <a href="{{ route('rider.profile.change-password') }}" class="btn btn-outline-brown me-2">
                                <i class="bi bi-lock-fill me-2"></i>Change Password
                            </a>
                            <a href="{{ route('rider.profile.edit') }}" class="btn btn-brown">
                                <i class="bi bi-pencil-fill me-2"></i>Edit Profile
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .bg-brown { background-color: #8B4513; }
    .bg-light-brown { background-color: #F5DEB3; }
    .border-brown { border-color: #A67B5B !important; }
    .text-brown { color: #5A3E36; }
    .btn-brown { background-color: #A67B5B; border-color: #8B4513; color: white; }
    .btn-brown:hover { background-color: #8B4513; border-color: #5A3E36; color: white; }
    .btn-outline-brown { border-color: #A67B5B; color: #5A3E36; }
    .btn-outline-brown:hover { background-color: #F5DEB3; border-color: #8B4513; }
</style>
@endsection
