<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Rider Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary-color: #8B4513;
            --secondary-color: #A67B5B;
            --accent-color: #D2B48C;
            --text-light: #ffffff;
            --text-dark: #333333;
            --bg-light: #fefaf3;
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', sans-serif;
            background-color: var(--bg-light);
            min-height: 100vh;
        }

        .side-navbar {
            height: 100vh;
            width: 280px;
            background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
            padding: 1.5rem;
            position: fixed;
            top: 0;
            left: 0;
            overflow-y: auto;
            box-shadow: 4px 0 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            transition: var(--transition);
        }

        .brand-section {
            padding: 1rem 0;
            margin-bottom: 2rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .brand-title {
            color: var(--text-light);
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .nav-link {
            color: var(--text-light) !important;
            padding: 0.8rem 1rem;
            margin-bottom: 0.5rem;
            border-radius: 8px;
            transition: var(--transition);
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .nav-link i {
            margin-right: 10px;
            font-size: 1.2rem;
        }

        .main-content {
            margin-left: 280px;
            padding: 2rem;
            min-height: 100vh;
            background-color: var(--bg-light);
            transition: var(--transition);
        }

        .logout-btn {
            background: rgba(220, 53, 69, 0.1);
            color: var(--text-light) !important;
            border: none;
            width: 100%;
            text-align: left;
            padding: 0.8rem 1rem;
            border-radius: 8px;
            margin-top: 2rem;
            transition: var(--transition);
        }

        .logout-btn:hover {
            background: rgba(220, 53, 69, 0.2);
            transform: translateX(5px);
        }

        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            background-color: white;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
        }

        .card-header {
            background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
            color: var(--text-light);
            border-radius: 15px 15px 0 0 !important;
            padding: 1rem 1.5rem;
        }

        @media (max-width: 768px) {
            .side-navbar {
                transform: translateX(-100%);
                position: fixed;
            }

            .side-navbar.show {
                transform: translateX(0);
            }

            .main-content {
                margin-left: 0;
                padding: 1rem;
            }

            .toggle-menu {
                display: block !important;
                position: fixed;
                top: 1rem;
                left: 1rem;
                z-index: 1001;
                background-color: var(--primary-color);
                color: var(--text-light);
                border: none;
                border-radius: 8px;
                padding: 0.5rem;
            }
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--accent-color);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--secondary-color);
        }
    </style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="toggle-menu d-none btn">
        <i class="bi bi-list"></i>
    </button>

    <!-- Sidebar -->
    <div class="side-navbar">
        <div class="brand-section">
            <h1 class="brand-title">
                <i class="bi bi-bicycle me-2"></i>
                Rider Panel
            </h1>
        </div>
        
        <nav class="nav flex-column">
            <a href="{{ route('rider.index') }}" class="nav-link {{ request()->routeIs('rider.index') ? 'active' : '' }}">
                <i class="bi bi-house-door"></i> Dashboard
            </a>
            <a href="{{ route('rider.orders') }}" class="nav-link {{ request()->routeIs('rider.orders') ? 'active' : '' }}">
                <i class="bi bi-list-ul"></i> Orders
            </a>
            <a href="{{ route('rider.my-deliveries') }}" class="nav-link {{ request()->routeIs('rider.my-deliveries') ? 'active' : '' }}">
                <i class="bi bi-truck"></i> My Deliveries
            </a>
            <a href="{{ route('rider.earnings') }}" class="nav-link {{ request()->routeIs('rider.earnings') ? 'active' : '' }}">
                <i class="bi bi-wallet2"></i> Earnings
            </a>
            <a href="{{ route('rider.profile') }}" class="nav-link {{ request()->routeIs('rider.profile') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i> Profile
            </a>
            
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </button>
            </form>
        </nav>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mobile menu toggle
            const toggleBtn = document.querySelector('.toggle-menu');
            const sidebar = document.querySelector('.side-navbar');
            const mainContent = document.querySelector('.main-content');

            if (toggleBtn) {
                toggleBtn.addEventListener('click', function() {
                    sidebar.classList.toggle('show');
                });
            }

            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth <= 768) {
                    if (!sidebar.contains(event.target) && !toggleBtn.contains(event.target)) {
                        sidebar.classList.remove('show');
                    }
                }
            });

            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    sidebar.classList.remove('show');
                }
            });
        });
    </script>
    @yield('scripts')
</body>
</html>