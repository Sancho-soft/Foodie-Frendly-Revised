@extends('layouts.rider')

@section('title', 'Rider Dashboard')

@section('content')
<div class="container-fluid">
    <!-- Welcome Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h1 class="fw-bold text-dark mb-2" style="font-size: 2rem;">
                Welcome back, {{ Auth::user()->name }}!
            </h1>
            <p class="text-muted">Here's your delivery overview for today</p>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(139, 69, 19, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-bicycle text-primary" style="font-size: 1.5rem; color: #8B4513 !important;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Total Deliveries</h6>
                    </div>
                    <h2 class="mb-0 fw-bold" style="color: #8B4513;">{{ $totalDeliveries }}</h2>
                    <p class="text-muted small mb-0">Completed deliveries</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(40, 167, 69, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-wallet2 text-success" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Total Earnings</h6>
                    </div>
                    <h2 class="mb-0 fw-bold text-success">₱{{ number_format($totalEarnings, 2) }}</h2>
                    <p class="text-muted small mb-0">Total earnings to date</p>
                </div>
            </div>
        </div>

        @if($currentOrder)
        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(255, 193, 7, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-clock text-warning" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Active Order</h6>
                    </div>
                    <h2 class="mb-0 fw-bold text-warning">#{{ $currentOrder->id }}</h2>
                    <p class="text-muted small mb-0">Currently delivering</p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-3 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center mb-3">
                        <div class="stats-icon" style="background-color: rgba(0, 123, 255, 0.1); padding: 10px; border-radius: 10px;">
                            <i class="bi bi-cash-stack text-primary" style="font-size: 1.5rem;"></i>
                        </div>
                        <h6 class="card-title mb-0 ms-3 fw-bold">Order Value</h6>
                    </div>
                    @php
                        $subtotal = $currentOrder->orderItems->sum(fn($item) => $item->price * $item->quantity);
                        $deliveryFee = $currentOrder->delivery_fee ?? 50.00;
                        $currentOrderTotal = $subtotal + $deliveryFee;
                    @endphp
                    <h2 class="mb-0 fw-bold text-primary">₱{{ number_format($currentOrderTotal, 2) }}</h2>
                    <p class="text-muted small mb-0">Current order total</p>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Current Order Section -->
    @if($currentOrder)
        <div class="card shadow-sm mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-box-seam me-2"></i>Current Delivery
                </h5>
                <span class="badge bg-warning text-dark">{{ ucfirst($currentOrder->status) }}</span>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <div class="delivery-info p-3 rounded" style="background-color: rgba(139, 69, 19, 0.05);">
                            <h6 class="fw-bold mb-3">Delivery Details</h6>
                            <div class="mb-2">
                                <i class="bi bi-person text-primary me-2"></i>
                                <strong>Customer:</strong> {{ $currentOrder->user->name }}
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-geo-alt text-danger me-2"></i>
                                <strong>Address:</strong> {{ $currentOrder->delivery_address ?? 'Not specified' }}
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-credit-card text-success me-2"></i>
                                <strong>Payment:</strong> {{ $currentOrder->payment_method ?? 'Not specified' }}
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="order-summary p-3 rounded" style="background-color: rgba(139, 69, 19, 0.05);">
                            <h6 class="fw-bold mb-3">Order Summary</h6>
                            <div class="mb-2">
                                <i class="bi bi-receipt text-primary me-2"></i>
                                <strong>Order ID:</strong> #{{ $currentOrder->id }}
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-box text-warning me-2"></i>
                                <strong>Items:</strong> {{ $currentOrder->orderItems->count() }}
                            </div>
                            <div class="mb-2">
                                <i class="bi bi-cash text-success me-2"></i>
                                <strong>Total:</strong> ₱{{ number_format($currentOrderTotal, 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items Table -->
                <div class="table-responsive mt-3">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Item</th>
                                <th class="text-center">Quantity</th>
                                <th class="text-end">Price</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentOrder->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->food->image)
                                                <img src="{{ asset('storage/' . $item->food->image) }}" 
                                                     alt="{{ $item->food->name }}" 
                                                     class="me-2 rounded"
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            @endif
                                            <span>{{ $item->food->name }}</span>
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">₱{{ number_format($item->price, 2) }}</td>
                                    <td class="text-end">₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light">
                            <tr>
                                <td colspan="3" class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end">₱{{ number_format($subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Delivery Fee:</strong></td>
                                <td class="text-end">₱{{ number_format($deliveryFee, 2) }}</td>
                            </tr>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total Amount:</strong></td>
                                <td class="text-end"><strong>₱{{ number_format($currentOrderTotal, 2) }}</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($currentOrder->status === 'delivering')
                    <div class="text-end mt-4">
                        <form action="{{ route('rider.finishDelivery', $currentOrder) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-2"></i>Complete Delivery
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    @else
        <div class="card shadow-sm">
            <div class="card-body text-center py-5">
                <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">No Active Deliveries</h5>
                <p class="text-muted">Check the orders page to pick up new deliveries</p>
                <a href="{{ route('rider.orders') }}" class="btn btn-primary">
                    <i class="bi bi-list-ul me-2"></i>View Available Orders
                </a>
            </div>
        </div>
    @endif

    <!-- Available Orders Section -->
    @if(!$currentOrder)
        <div class="card shadow-sm">
            <div class="card-header">
                <h5 class="mb-0 text-white">
                    <i class="bi bi-list-ul me-2"></i>Available Orders
                </h5>
            </div>
            <div class="card-body">
                @if($pendingOrders->isEmpty())
                    <p class="text-center text-muted mb-0">No pending orders available at the moment.</p>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingOrders as $order)
                                    @php
                                        $orderSubtotal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
                                        $orderDeliveryFee = $order->delivery_fee ?? 50.00;
                                        $orderTotal = $orderSubtotal + $orderDeliveryFee;
                                    @endphp
                                    <tr>
                                        <td>#{{ $order->id }}</td>
                                        <td>{{ $order->user->name }}</td>
                                        <td>{{ $order->orderItems->count() }}</td>
                                        <td>₱{{ number_format($orderTotal, 2) }}</td>
                                        <td>{{ $order->delivery_address }}</td>
                                        <td>
                                            <form action="{{ route('rider.startDelivery', $order) }}" method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-play-circle me-1"></i>Start Delivery
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    @endif
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

    .delivery-info, .order-summary {
        height: 100%;
    }

    .alert {
        border: none;
        border-radius: 10px;
    }

    .alert-success {
        background-color: rgba(40, 167, 69, 0.1);
        color: #28a745;
    }

    .alert-danger {
        background-color: rgba(220, 53, 69, 0.1);
        color: #dc3545;
    }

    .btn-success {
        background-color: #28a745;
        border: none;
        padding: 0.5rem 1.5rem;
        font-weight: 500;
    }

    .btn-success:hover {
        background-color: #218838;
    }

    @media (max-width: 768px) {
        .stats-card {
            margin-bottom: 1rem;
        }
    }
</style>

@section('scripts')
<script>
    // Auto-dismiss alerts after 3 seconds
    document.addEventListener('DOMContentLoaded', function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(alert => {
            setTimeout(() => {
                alert.classList.remove('show');
            }, 3000);
        });
    });
</script>
@endsection
@endsection
