<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Foodie Friendly')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary-brown: #8B4513;
            --secondary-brown: #A67B5B;
            --light-brown: #f8f1e9;
            --hover-brown: #6b4423;
            --text-light: #ffffff;
        }

        body { 
            background-color: var(--light-brown);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .bg-brown { 
            background: linear-gradient(to bottom, var(--primary-brown), var(--secondary-brown));
        }

        .text-brown { 
            color: var(--primary-brown);
        }

        .btn-brown {
            background-color: var(--secondary-brown);
            border-color: var(--primary-brown);
            color: var(--text-light);
            transition: all 0.3s ease;
        }

        .btn-brown:hover {
            background-color: var(--hover-brown);
            color: var(--text-light);
            transform: translateY(-2px);
        }

        .sidebar {
            min-height: 100vh;
            width: 240px;
            position: fixed;
            top: 0;
            left: 0;
            z-index: 1030;
            transition: all 0.3s ease;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
        }

        .main-content {
            margin-left: 240px;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .nav-link {
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 8px;
            transition: all 0.3s ease;
            color: var(--text-light) !important;
            opacity: 0.85;
        }

        .nav-link:hover {
            background-color: rgba(255, 255, 255, 0.1);
            opacity: 1;
            transform: translateX(5px);
        }

        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.2) !important;
            opacity: 1;
            font-weight: 600;
        }

        .navbar-brand {
            padding: 15px 0;
            margin-bottom: 30px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .navbar-brand:hover {
            color: var(--text-light);
            opacity: 0.9;
        }

        .logout-btn {
            background: transparent;
            border: none;
            width: 100%;
            text-align: left;
            padding: 12px 15px;
            border-radius: 8px;
            color: var(--text-light);
            opacity: 0.85;
            transition: all 0.3s ease;
            margin-top: 20px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .logout-btn:hover {
            background-color: rgba(255, 255, 255, 0.1);
            opacity: 1;
            transform: translateX(5px);
        }

        .mobile-nav-toggle {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1031;
            background: var(--secondary-brown);
            border: none;
            padding: 10px;
            border-radius: 5px;
            color: var(--text-light);
        }

        @media (max-width: 768px) {
            .mobile-nav-toggle {
                display: block;
            }

            .sidebar {
                transform: translateX(-100%);
                position: fixed;
            }

            .sidebar.active {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding-top: 70px;
            }

            .nav-link {
                padding: 10px 15px;
            }

            .navbar-brand {
                margin-bottom: 20px;
                padding: 10px 0;
            }
        }
    </style>
</head>
<body>
    <!-- Mobile Navigation Toggle -->
    <button class="mobile-nav-toggle" onclick="toggleSidebar()">
        <i class="bi bi-list"></i>
    </button>

    <div class="d-flex flex-column flex-md-row">
        <!-- Sidebar -->
        <nav class="sidebar bg-brown p-3" id="sidebar">
            <a class="navbar-brand text-white fs-4 d-block" href="{{ route('home') }}">
                <i class="bi bi-shop me-2"></i>Foodie Friendly
            </a>
            <ul class="nav nav-pills flex-column">
                @auth
                    @if (Auth::user()->role === 'admin')
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                                <i class="bi bi-speedometer2 me-2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.order_categories') }}" class="nav-link {{ Request::routeIs('admin.order_categories') ? 'active' : '' }}">
                                <i class="bi bi-basket me-2"></i>Manage Food
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.order_menu') }}" class="nav-link {{ Request::routeIs('admin.order_menu') ? 'active' : '' }}">
                                <i class="bi bi-receipt-cutoff me-2"></i>Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.riders.index') }}" class="nav-link {{ Request::routeIs('admin.riders.*') ? 'active' : '' }}">
                                <i class="bi bi-bicycle me-2"></i>Riders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.sales_report') }}" class="nav-link {{ Request::routeIs('admin.sales_report') ? 'active' : '' }}">
                                <i class="bi bi-graph-up-arrow me-2"></i>Sales Report
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('home') }}" class="nav-link {{ Request::routeIs('home') ? 'active' : '' }}">
                                <i class="bi bi-card-list me-2"></i>Food Menu
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('cart') }}" class="nav-link {{ Request::routeIs('cart') ? 'active' : '' }}">
                                <i class="bi bi-cart me-2"></i>Cart
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('order-history') }}" class="nav-link {{ Request::routeIs('order-history') ? 'active' : '' }}">
                                <i class="bi bi-clock-history me-2"></i>My Orders
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('profile') }}" class="nav-link {{ Request::routeIs('profile') ? 'active' : '' }}">
                                <i class="bi bi-person-circle me-2"></i>Profile
                            </a>
                        </li>
                    @endif
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link {{ Request::routeIs('login') ? 'active' : '' }}">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Login
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link {{ Request::routeIs('register') ? 'active' : '' }}">
                            <i class="bi bi-pencil-square me-2"></i>Register
                        </a>
                    </li>
                @endauth

                @auth
                    <li class="nav-item">
                        <form action="{{ route('logout') }}" method="POST" class="w-100">
                            @csrf
                            <button type="submit" class="logout-btn">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                @endauth
            </ul>
        </nav>

        <!-- Main Content -->
        <div class="main-content flex-grow-1">
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-nav-toggle');
            
            if (window.innerWidth <= 768) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
    @yield('scripts')
</body>
</html>