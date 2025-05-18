@extends('layouts.rider')

@section('title', 'Rider Earnings')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-dark mb-2" style="font-size: 2rem;">
                <i class="bi bi-wallet2 me-2"></i>Earnings Dashboard
            </h1>
            <p class="text-muted">Track your delivery earnings and performance</p>
        </div>
    </div>

    <!-- Stats Cards Row -->
    <div class="row mb-4">
        <!-- Total Deliveries -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(139, 69, 19, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-bicycle text-primary" style="font-size: 1.5rem; color: #8B4513 !important;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Total Deliveries</h6>
                    </div>
                    <h2 class="mb-0 fw-bold" style="color: #8B4513;">{{ $summary['total_deliveries'] }}</h2>
                    <p class="text-muted small mb-0">Completed deliveries</p>
                </div>
            </div>
        </div>

        <!-- Total Distance -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(0, 123, 255, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-map text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Total Distance</h6>
                    </div>
                    <h2 class="mb-0 fw-bold text-primary">{{ $summary['total_distance'] }} km</h2>
                    <p class="text-muted small mb-0">Total kilometers traveled</p>
                </div>
            </div>
        </div>

        <!-- Current Rate -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(40, 167, 69, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-currency-exchange text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Current Rate</h6>
                    </div>
                    <h2 class="mb-0 fw-bold text-success">₱{{ number_format($summary['fare_per_km'], 2) }}</h2>
                    <p class="text-muted small mb-0">Per kilometer</p>
                </div>
            </div>
        </div>

        <!-- Tax Rate -->
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(255, 193, 7, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-percent text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Tax Rate</h6>
                    </div>
                    <h2 class="mb-0 fw-bold text-warning">{{ $summary['tax_rate'] }}%</h2>
                    <p class="text-muted small mb-0">Current tax rate</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings Summary Cards -->
    <div class="row mb-4">
        <!-- Base Fare -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(40, 167, 69, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-cash text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Base Fare</h6>
                    </div>
                    <h2 class="mb-0 fw-bold text-success">₱{{ number_format($summary['total_base_fare'], 2) }}</h2>
                    <p class="text-muted small mb-0">Total base earnings</p>
                </div>
            </div>
        </div>

        <!-- Tax Amount -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(220, 53, 69, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-receipt text-danger" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Tax Amount</h6>
                    </div>
                    <h2 class="mb-0 fw-bold text-danger">₱{{ number_format($summary['total_tax'], 2) }}</h2>
                    <p class="text-muted small mb-0">Total tax deducted</p>
                </div>
            </div>
        </div>

        <!-- Total Earnings -->
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(139, 69, 19, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-wallet2 text-primary" style="font-size: 1.5rem; color: #8B4513 !important;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Total Earnings</h6>
                    </div>
                    <h2 class="mb-0 fw-bold" style="color: #8B4513;">₱{{ number_format($summary['total_earnings'], 2) }}</h2>
                    <p class="text-muted small mb-0">Net earnings after tax</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Earnings History -->
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0 text-white">
                <i class="bi bi-clock-history me-2"></i>Earnings History
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Order ID</th>
                            <th>Date & Time</th>
                            <th>Distance</th>
                            <th class="text-end">Base Fare</th>
                            <th class="text-end">Tax</th>
                            <th class="text-end">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($earnings as $earning)
                            <tr>
                                <td>
                                    <span class="fw-bold">#{{ $earning['order_id'] }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-calendar-event text-primary me-2"></i>
                                        {{ $earning['date']->format('M d, Y H:i') }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-geo-alt text-danger me-2"></i>
                                        {{ $earning['distance'] }} km
                                    </div>
                                </td>
                                <td class="text-end">₱{{ number_format($earning['base_fare'], 2) }}</td>
                                <td class="text-end text-danger">₱{{ number_format($earning['tax'], 2) }}</td>
                                <td class="text-end fw-bold">₱{{ number_format($earning['total_fare'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .stats-icon i {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .table {
        font-size: 0.95rem;
    }

    .table thead th {
        font-weight: 600;
        text-transform: uppercase;
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    .card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        background-color: white;
        transition: all 0.3s ease;
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
        .stats-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection