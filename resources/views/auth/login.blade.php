@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="d-flex justify-content-center align-items-center vh-100 position-relative" 
    style="background: linear-gradient(to bottom, #D2B48C, #A67B5B);">
    
    <!-- Split Layout -->
    <div class="row w-100" style="margin: 0;">
        <!-- Left Section: About Us Content -->
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="min-height: 70vh; padding: 20px;">
            <div class="content position-relative" style="max-height: 70vh; overflow-y: auto; width: 1100px;">
                <!-- Close button -->
                <button type="button" class="btn-close position-absolute top-0 end-0 m-3" aria-label="Close" onclick="closeAboutUs()"></button>
                
                <h1 class="section-title">Foodie Frenzy</h1>

                <div class="highlight-box text-center">
                    <p class="lead mb-0" style="font-size: 1rem;"> <!-- Increased from 0.9rem -->
                        Our <span class="fw-bold" style="color: var(--accent);">Order Management System</span> delivers a seamless, 
                        efficient, and delightful ordering experience. Whether you prefer 
                        <span class="fw-bold">dine-in</span>, <span class="fw-bold">takeout</span>, or <span class="fw-bold">delivery</span>, 
                        we ensure accuracy and speed for both customers and businesses.
                    </p>
                </div>

                <hr class="divider">

                <h2 class="text-center mb-2 fw-bold" style="color: var(--primary-dark); font-size: 1.6rem;"> <!-- Increased font-size from 1.4rem to 1.6rem -->
                    <i class="fas fa-star me-2"></i>Key Features
                </h2>

                <div class="row">
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-bolt"></i>
                            </div>
                            <h3 class="feature-title">Easy Ordering</h3>
                            <p style="font-size: 0.85rem;">Quickly add, edit, or remove food items with our intuitive interface.</p> <!-- Increased from 0.8rem -->
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-list"></i>
                            </div>
                            <h3 class="feature-title">Menu Categories</h3>
                            <p style="font-size: 0.85rem;">Well-organized menu with dedicated sections for Grilled Items, Rice, Drinks, and more.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-calculator"></i>
                            </div>
                            <h3 class="feature-title">Smart Calculations</h3>
                            <p style="font-size: 0.85rem;">Automatic total price calculation before checkout eliminates errors.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-receipt"></i>
                            </div>
                            <h3 class="feature-title">Detailed Receipts</h3>
                            <p style="font-size: 0.85rem;">Receive clear and comprehensive order summaries after every transaction.</p>
                        </div>
                    </div>
                    
                    <div class="col-md-4 offset-md-4">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="fas fa-chart-line"></i>
                            </div>
                            <h3 class="feature-title">Admin Dashboard</h3>
                            <p style="font-size: 0.85rem;">Comprehensive tools to manage orders, track sales, and control your business.</p>
                        </div>
                    </div>
                </div>

                <hr class="divider">

                <h2 class="text-center mb-2 fw-bold" style="color: var(--primary-dark); font-size: 1.6rem;"> <!-- Increased font-size from 1.4rem to 1.6rem -->
                    <i class="fas fa-cogs me-2"></i>How It Works
                </h2>

                <div class="steps-container">
                    <div class="step-item">
                        <div class="step-number">1</div>
                        <div>
                            <h4 class="fw-bold mb-0" style="font-size: 1rem;">Login to Your Account</h4> <!-- Increased font-size from 0.9rem to 1rem -->
                            <p class="mb-0" style="font-size: 0.85rem;">Sign in as an admin or customer to access the system's features.</p> <!-- Increased from 0.8rem -->
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">2</div>
                        <div>
                            <h4 class="fw-bold mb-0" style="font-size: 1rem;">Browse & Select Items</h4>
                            <p class="mb-0" style="font-size: 0.85rem;">Explore our delicious menu categories and choose your favorites.</p>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">3</div>
                        <div>
                            <h4 class="fw-bold mb-0" style="font-size: 1rem;">Review & Confirm</h4>
                            <p class="mb-0" style="font-size: 0.85rem;">Check your selections and finalize your order with ease.</p>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">4</div>
                        <div>
                            <h4 class="fw-bold mb-0" style="font-size: 1rem;">Receive Your Order</h4>
                            <p class="mb-0" style="font-size: 0.85rem;">Get instant confirmation and detailed receipt for your records.</p>
                        </div>
                    </div>
                    
                    <div class="step-item">
                        <div class="step-number">5</div>
                        <div>
                            <h4 class="fw-bold mb-0" style="font-size: 1rem;">Track & Manage</h4>
                            <p class="mb-0" style="font-size: 0.85rem;">Access your order history and manage preferences anytime.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Section: Login Form -->
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="min-height: 70vh; padding: 20px;">
            <div class="card p-5 shadow-lg border border-black" 
                 style="width: 450px; border-radius: 15px; background: rgba(255, 255, 255, 0.1); backdrop-filter: blur(10px);">
                <h2 class="text-white text-center mb-4" style="font-size: 2rem;">Login</h2>
                
                @if(session('success'))
                    <p class="text-success text-center" style="font-size: 1.1rem;">{{ session('success') }}</p>
                @endif

                @if ($errors->any())
                    <p class="text-danger text-center" style="font-size: 1.1rem;">{{ $errors->first() }}</p>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                
                    <div class="mb-4">
                        <label class="form-label text-white" style="font-size: 1.2rem;">Email</label>
                        <input type="email" name="email" class="form-control bg-dark text-white border-0" 
                               style="font-size: 1.1rem; padding: 12px;" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label text-white" style="font-size: 1.2rem;">Password</label>
                        <input type="password" name="password" class="form-control bg-dark text-white border-0" 
                               style="font-size: 1.1rem; padding: 12px;" required>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100 mb-3" style="font-size: 1.2rem; padding: 12px;">Log in</button>
                </form>
                <div class="text-center">
                    <a href="{{ route('register') }}" class="btn register-btn w-100" 
                       style="background: #E67E22; color: white; border: none; border-radius: 8px; padding: 12px; font-weight: 600; transition: all 0.3s ease; font-size: 1.2rem;">
                        Register
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    :root {
        --primary-dark: #5D4037;
        --primary-medium: #8B5A2B;
        --primary-light: #D2B48C;
        --accent: #8B4513;
        --text-dark: #3E2723;
        --text-light: #FFF8E1;
    }
    
    .content {
        max-height: 70vh; /* Increased from 60vh */
        overflow-y: auto;
        width: 1100px; /* Increased from 950px */
        background: rgba(255, 255, 255, 0.9);
        border-radius: 20px;
        padding: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    .section-title {
        font-size: 2.2rem; /* Increased from 2rem */
        font-weight: 700;
        color: var(--primary-dark);
        margin: 0 auto 8px;
        position: relative;
        display: block;
        text-align: center;
        width: fit-content;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -5px;
        left: 0;
        width: 100%;
        height: 2px;
        background: linear-gradient(90deg, var(--accent), var(--primary-light));
        border-radius: 2px;
    }
    
    .highlight-box {
        background: linear-gradient(145deg, #FFF8E1, #FFECB3);
        padding: 6px;
        border-radius: 8px;
        margin-bottom: 8px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
        border-left: 4px solid var(--accent);
        transition: transform 0.3s ease;
    }
    
    .highlight-box:hover {
        transform: translateY(-3px);
    }
    
    .feature-card {
        background: white;
        border-radius: 8px;
        padding: 10px;
        margin-bottom: 6px;
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.1);
        border-top: 2px solid var(--accent);
        transition: all 0.3s ease;
        text-align: center;
    }
    
    .feature-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }
    
    .feature-icon {
        font-size: 1.7rem; /* Increased from 1.5rem */
        color: var(--accent);
        margin-bottom: 5px;
    }
    
    .feature-title {
        font-size: 1rem; /* Increased from 0.95rem */
        font-weight: 600;
        color: var(--primary-dark);
        margin-bottom: 3px;
    }
    
    .divider {
        height: 1px;
        background: linear-gradient(90deg, transparent, var(--primary-light), transparent);
        margin: 8px 0;
        border: none;
    }
    
    .step-item {
        display: flex;
        align-items: flex-start;
        background: white;
        padding: 5px;
        border-radius: 6px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 5px;
    }
    
    .step-number {
        background: var(--accent);
        color: white;
        width: 32px; /* Increased from 28px */
        height: 32px; /* Increased from 28px */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        margin-right: 6px;
        flex-shrink: 0;
        font-size: 0.9rem; /* Increased from 0.85rem */
    }

    .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .register-btn:hover {
        background: #D35400 !important;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    @media (max-width: 768px) {
        .content {
            padding: 8px;
            width: 100%;
            max-height: 65vh; /* Adjusted for smaller screens, slightly less than 70vh */
        }
        
        .section-title {
            font-size: 1.8rem; /* Increased from 1.5rem */
        }

        .col-md-6 {
            min-height: auto !important;
        }

        .feature-card, .step-item {
            margin-bottom: 5px;
        }

        .card {
            width: 100% !important;
            padding: 15px !important;
        }
    }
</style>

@section('scripts')
<script>
    function closeAboutUs() {
        const aboutUsSection = document.querySelector('.col-md-6');
        if (aboutUsSection) {
            aboutUsSection.style.display = 'none';
            // Make the login section take full width
            const loginSection = document.querySelector('.col-md-6:last-child');
            if (loginSection) {
                loginSection.className = 'col-12 d-flex align-items-center justify-content-center';
            }
        }
    }
</script>
@endsection
@endsection