@extends('layouts.rider')

@section('title', 'Rider Dashboard')

@section('content')
<div class="container-fluid mt-4">
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

    <!-- Stats Overview -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Deliveries</h5>
                    <h2 class="mb-0">{{ $totalDeliveries }}</h2>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Total Earnings</h5>
                    <h2 class="mb-0">₱{{ number_format($totalEarnings, 2) }}</h2>
                </div>
            </div>
        </div>
    </div>

    <!-- Current Order Section -->
    @if($currentOrder)
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-brown text-white">
                <h5 class="mb-0">Current Delivery</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Order Details</h6>
                        <p><strong>Order #:</strong> {{ $currentOrder->id }}</p>
                        <p><strong>Customer:</strong> {{ $currentOrder->user->name }}</p>
                        <p><strong>Address:</strong> {{ $currentOrder->delivery_address }}</p>
                        <p><strong>Payment Method:</strong> {{ $currentOrder->payment_method }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Items</h6>
                        <ul class="list-unstyled">
                            @foreach($currentOrder->orderItems as $item)
                                <li>{{ $item->food->name }} x {{ $item->quantity }}</li>
                            @endforeach
                        </ul>
                        <p><strong>Total Amount:</strong> ₱{{ number_format($currentOrderTotal, 2) }}</p>
                    </div>
                </div>
                <div class="text-end mt-3">
                    <form action="{{ route('rider.finishDelivery', $currentOrder) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success" onclick="return confirm('Are you sure you want to complete this delivery?')">
                            <i class="bi bi-check-circle me-2"></i>Complete Delivery
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endif

    <!-- Available Orders Section -->
    @if(!$currentOrder && $pendingOrders->isNotEmpty())
        <div class="card shadow-sm">
            <div class="card-header bg-brown text-white">
                <h5 class="mb-0">Available Orders</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Items</th>
                                <th>Total</th>
                                <th>Address</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingOrders as $order)
                                <tr>
                                    <td>#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->orderItems->count() }}</td>
                                    <td>₱{{ number_format($pendingOrderTotals[$order->id], 2) }}</td>
                                    <td>{{ $order->delivery_address }}</td>
                                    <td>
                                        <form action="{{ route('rider.startDelivery', $order) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-bicycle me-1"></i>Accept Order
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @elseif(!$currentOrder)
        <div class="alert alert-info">
            <i class="bi bi-info-circle me-2"></i>No pending orders available at the moment.
        </div>
    @endif
</div>

<style>
    .card {
        border: none;
        border-radius: 10px;
        margin-bottom: 20px;
    }
    .card-header {
        border-radius: 10px 10px 0 0;
    }
    .bg-brown {
        background-color: #8B4513;
    }
    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }
    .btn-success:hover {
        background-color: #218838;
        border-color: #1e7e34;
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
