<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | Foodie Frenzy</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-dark: #5D4037;
            --primary-medium: #8B5A2B;
            --primary-light: #D2B48C;
            --accent: #8B4513;
            --text-dark: #3E2723;
            --text-light: #FFF8E1;
        }
        
        body {
            background: linear-gradient(135deg, var(--primary-dark), var(--primary-medium), var(--primary-light));
            color: var(--text-dark);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .content {
            max-width: 900px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
        }
        
        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary-dark);
            margin: 0 auto 25px;
            position: relative;
            display: block;
            text-align: center;
            width: fit-content;
        }
        
        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--accent), var(--primary-light));
            border-radius: 2px;
        }
        
        .highlight-box {
            background: linear-gradient(145deg, #FFF8E1, #FFECB3);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            border-left: 5px solid var(--accent);
            transition: transform 0.3s ease;
        }
        
        .highlight-box:hover {
            transform: translateY(-5px);
        }
        
        .feature-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-top: 3px solid var(--accent);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
        
        .feature-icon {
            font-size: 2rem;
            color: var(--accent);
            margin-bottom: 15px;
        }
        
        .feature-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: var(--primary-dark);
            margin-bottom: 10px;
        }
        
        .divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-light), transparent);
            margin: 40px 0;
            border: none;
        }
        
        .step-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 20px;
            background: white;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        .step-number {
            background: var(--accent);
            color: white;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            margin-right: 20px;
            flex-shrink: 0;
        }
        
        .btn-custom {
            background: var(--accent);
            color: white;
            padding: 12px 30px;
            font-size: 1.1rem;
            border: none;
            border-radius: 50px;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-top: 20px;
        }
        
        .btn-custom:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(93, 64, 55, 0.4);
        }
        
        .btn-custom i {
            margin-right: 8px;
        }
        
        @media (max-width: 768px) {
            .content {
                padding: 25px;
            }
            
            .section-title {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>

    <div class="content">
        <h1 class="section-title"> Foodie Frenzy</h1>

        <div class="highlight-box text-center">
            <p class="lead mb-0">
                Our <span class="fw-bold" style="color: var(--accent);">Order Management System</span> delivers a seamless, 
                efficient, and delightful ordering experience. Whether you prefer 
                <span class="fw-bold">dine-in</span>, <span class="fw-bold">takeout</span>, or <span class="fw-bold">delivery</span>, 
                we ensure accuracy and speed for both customers and businesses.
            </p>
        </div>

        <hr class="divider">

        <h2 class="text-center mb-4 fw-bold" style="color: var(--primary-dark);">
            <i class="fas fa-star me-2"></i>Key Features
        </h2>

        <div class="row">
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="feature-title">Easy Ordering</h3>
                    <p>Quickly add, edit, or remove food items with our intuitive interface for a hassle-free experience.</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-list"></i>
                    </div>
                    <h3 class="feature-title">Menu Categories</h3>
                    <p>Well-organized menu with dedicated sections for Grilled Items, Rice, Drinks, and more.</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-calculator"></i>
                    </div>
                    <h3 class="feature-title">Smart Calculations</h3>
                    <p>Automatic total price calculation before checkout eliminates errors and confusion.</p>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-receipt"></i>
                    </div>
                    <h3 class="feature-title">Detailed Receipts</h3>
                    <p>Receive clear and comprehensive order summaries after every transaction.</p>
                </div>
            </div>
            
            <div class="col-md-6 offset-md-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title">Admin Dashboard</h3>
                    <p>Comprehensive tools to manage orders, track sales, and control your business operations.</p>
                </div>
            </div>
        </div>

        <hr class="divider">

        <h2 class="text-center mb-4 fw-bold" style="color: var(--primary-dark);">
            <i class="fas fa-cogs me-2"></i>How It Works
        </h2>

        <div class="steps-container">
            <div class="step-item">
                <div class="step-number">1</div>
                <div>
                    <h4 class="fw-bold mb-2">Login to Your Account</h4>
                    <p class="mb-0">Sign in as an admin or customer to access the system's features.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">2</div>
                <div>
                    <h4 class="fw-bold mb-2">Browse & Select Items</h4>
                    <p class="mb-0">Explore our delicious menu categories and choose your favorites.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">3</div>
                <div>
                    <h4 class="fw-bold mb-2">Review & Confirm</h4>
                    <p class="mb-0">Check your selections and finalize your order with ease.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">4</div>
                <div>
                    <h4 class="fw-bold mb-2">Receive Your Order</h4>
                    <p class="mb-0">Get instant confirmation and detailed receipt for your records.</p>
                </div>
            </div>
            
            <div class="step-item">
                <div class="step-number">5</div>
                <div>
                    <h4 class="fw-bold mb-2">Track & Manage</h4>
                    <p class="mb-0">Access your order history and manage preferences anytime.</p>
                </div>
            </div>
        </div>

        <div class="text-center">
            <a href="{{ route('login') }}" class="btn btn-custom">
                <i class="fas fa-arrow-left me-2"></i>Back to Login
            </a>
        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>