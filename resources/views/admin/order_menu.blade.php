@extends('layouts.welcome_admin')

@section('content')

<!-- Bootstrap Icons CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container py-4" style="background-color: #f4ece3; border-radius: 15px;">
    <h2 class="mb-4 text-center" style="color: #5D3A00;">🛒 Order Management</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        @endif

    <div class="card shadow-sm" style="background-color: #fff7f0;">
        <div class="card-header fw-bold" style="background-color: #d2b48c; color: #3e2600;">
            📋 Order List
        </div>
        <div class="card-body">
            <a href="{{ route('admin.set_delivery_fee') }}" class="btn btn-info mb-3" data-bs-toggle="tooltip" title="Configure delivery charges">
                <i class="bi bi-gear"></i> Set Delivery Fee
            </a>
            @if($orders->isEmpty())
                <p class="text-center">No orders available.</p>
            @else
                <table class="table table-bordered table-hover table-light">
                    <thead class="table-dark" style="background-color: #a97c50; color: white;">
                        <tr>
                            <th>#</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Delivery Fee</th>
                            <th>Status</th>
                            <th>Ordered At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            @php
                                $totalAmount = $order->total_amount + ($order->delivery_fee ?? 0.00);
                            @endphp
                            <tr>
                                <td>{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                    ₱{{ number_format($totalAmount, 2) }}
                                    @if($order->payment_method === 'GCash' || $order->payment_method === 'PayMaya' || ($order->payment_method === 'Cash on Delivery' && $order->status === 'delivered'))
                                        <span class="badge badge-paid">Paid</span>
                                    @endif
                                </td>
                                <td>₱{{ number_format($order->delivery_fee ?? 0.00, 2) }}</td>
                                <td>
                                    <span class="badge 
                                        @if($order->status === 'pending') badge-pending
                                        @elseif($order->status === 'delivering') badge-delivering
                                        @elseif($order->status === 'delivered') badge-delivered
                                        @else badge-cancelled @endif">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-primary action-btn" data-bs-toggle="tooltip" title="View order details">
                                            <i class="bi bi-eye"></i> 
                                        </a>
                                        
                                        @if($order->status === 'pending')
                                            <form action="{{ route('admin.orders.start_delivery', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-warning action-btn" data-bs-toggle="tooltip" title="Mark as delivering">
                                                    <i class="bi bi-truck"></i> 
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.orders.cancel', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-danger action-btn" data-bs-toggle="tooltip" title="Cancel this order">
                                                    <i class="bi bi-x-circle"></i> 
                                                </button>
                                            </form>
                                        @elseif($order->status === 'delivering')
                                            <form action="{{ route('admin.orders.complete_delivery', $order) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-success action-btn" data-bs-toggle="tooltip" title="Mark as delivered">
                                                    <i class="bi bi-check-circle"></i> 
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="d-flex justify-content-center mt-4">
                {{ $orders->appends([
                    'search' => request('search'),
                    'role_filter' => request('role_filter')
                ])->links('pagination::bootstrap-4') }}
                </div>

            @endif
        </div>
    </div>
</div>
<style>
    .badge-pending {
        background-color: #d2b48c; 
        color: #3e2600;
    }

    .badge-delivering {
        background-color: #ffc107; 
        color: #3e2600;
    }

    .badge-delivered {
        background-color: #28a745; 
        color: #fff;
    }

    .badge-cancelled {
        background-color: #dc3545;
        color: #fff;
    }

    .badge-paid {
        background-color: #28a745;
        color: #fff;
        padding: 4px 8px;
        border-radius: 5px;
    }

    body {
        background-color: #eaddcf;
    }

    /* Action Button Hover Effects */
    .action-btn {
        transition: all 0.2s ease;
        transform: scale(1);
        opacity: 0.9;
        box-shadow: none;
    }

    .action-btn:hover {
        transform: scale(1.1);
        opacity: 1;
        box-shadow: 0 0 8px rgba(0,0,0,0.15);
    }

    .btn-primary.action-btn:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }

    .btn-warning.action-btn:hover {
        background-color: #ffca2c;
        border-color: #ffc720;
    }

    .btn-danger.action-btn:hover {
        background-color: #dc3545;
        border-color: #bb2d3b;
    }

    .btn-success.action-btn:hover {
        background-color: #198754;
        border-color: #157347;
    }

    /* Form and button spacing */
    form {
        margin: 0;
    }

    .d-flex.gap-2 {
        gap: 0.5rem;
    }

    /* Tooltip Styles */
    .tooltip {
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    .tooltip-inner {
        background-color: #5A3E36;
        padding: 5px 10px;
        font-size: 0.85rem;
        max-width: 200px;
    }

    .bs-tooltip-auto[data-popper-placement^=top] .tooltip-arrow::before,
    .bs-tooltip-top .tooltip-arrow::before {
        border-top-color: #5A3E36;
    }
</style>

@section('scripts')
<script>
    // Initialize tooltips when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                trigger: 'hover focus',
                delay: {show: 100, hide: 50}
            });
        });
    });
</script>
@endsection

@endsection