@extends('layouts.rider')

@section('title', 'Edit Profile')

@section('content')
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg" style="border-radius: 15px; overflow: hidden;">
                <div class="card-header py-3" style="background: linear-gradient(to right, #8B4513, #A67B5B);">
                    <h3 class="mb-0 text-white">
                        <i class="bi bi-person-circle me-2"></i>Edit Profile
                    </h3>
                </div>
                <div class="card-body" style="background-color: #F5F5DC;">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            <div class="mt-2">
                                <a href="{{ route('rider.profile') }}" class="btn btn-brown btn-sm">Back to Profile</a>
                            </div>
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    <form action="{{ route('rider.profile.update') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" class="form-control bg-light-brown border-brown @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    <label for="name" class="text-brown">
                                        <i class="bi bi-person-fill me-2"></i>Full Name
                                    </label>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="email" class="form-control bg-light-brown border-brown @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    <label for="email" class="text-brown">
                                        <i class="bi bi-envelope-fill me-2"></i>Email Address
                                    </label>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" class="form-control bg-light-brown border-brown @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $user->rider->phone_number ?? '') }}">
                                    <label for="phone" class="text-brown">
                                        <i class="bi bi-telephone-fill me-2"></i>Phone Number
                                    </label>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top border-brown">
                            <a href="{{ route('rider.profile') }}" class="btn btn-outline-brown">Cancel</a>
                            <button type="submit" class="btn btn-brown">
                                <i class="bi bi-save-fill me-2"></i>Save Changes
                            </button>
                        </div>
                    </form>
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