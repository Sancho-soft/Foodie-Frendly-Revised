@extends('layouts.auth')

@section('title', 'Register')
@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 position-relative" 
    style="background: linear-gradient(to bottom, #D2B48C, #A67B5B);">
    
    <div class="card p-4 shadow-lg border border-black position-relative" 
        style="width: 350px; border-radius: 15px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px); z-index: 1;">
        
        <!-- Heading moved inside the card -->
        <h1 class="h3 fw-bold text-white text-center mb-4" style="text-transform: uppercase;">Sign Up</h1>

        @if(session('error'))
            <p class="text-danger text-center">{{ session('error') }}</p>
        @endif
        
        <form method="POST" action="{{ route('register') }}">
            @csrf
        
            <input type="hidden" name="role" value="customer">
        
            <div class="mb-3">
                <label class="form-label text-white">Name</label>
                <input type="text" name="name" class="form-control bg-dark text-white border-0" required>
            </div>
        
            <div class="mb-3">
                <label class="form-label text-white">Email</label>
                <input type="email" name="email" class="form-control bg-dark text-white border-0" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label text-white">Phone Number</label>
                <input type="tel" name="phone" class="form-control bg-dark text-white border-0 @error('phone') is-invalid @enderror" required value="{{ old('phone') }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label class="form-label text-white">Password</label>
                <input type="password" name="password" class="form-control bg-dark text-white border-0" required>
            </div>
        
            <div class="mb-3">
                <label class="form-label text-white">Confirm Password</label>
                <input type="password" name="password_confirmation" class="form-control bg-dark text-white border-0" required>
            </div>
        
            <button type="submit" class="btn btn-success w-100">Create an Account</button>
        </form>
        
        <div class="text-center mt-3">
            <a href="{{ route('login') }}" class="text-white">Already have an account? Log in</a>
        </div>
    </div>
</div>
@endsection