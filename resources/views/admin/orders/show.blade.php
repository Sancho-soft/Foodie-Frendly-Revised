@extends('layouts.welcome_admin')

@section('content')

<!-- Bootstrap Icons CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container py-4" style="background-color: #f4ece3; border-radius: 15px;">
    <h2 class="mb-4 text-center" style="color: #5D3A00;">📦 Order Details</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-sm" style="background-color: #fff7f0;">
        <div class="card-header fw-bold" style="background-color: #d2b48c; color: #3e2600;">
            Order #{{ $order->id }}
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Customer:</strong> {{ $order->user->name }}</p>
                    <p><strong>Delivery Address:</strong> {{ $order->delivery_address ?? 'Not specified' }}</p>
                    <p><strong>Payment Method:</strong> {{ $order->payment_method ?? 'Not specified' }}</p>
                    <p><strong>Delivery Fee:</strong> ₱{{ number_format($order->delivery_fee ?? 0.00, 2) }}</p>
                    @php
                        $subtotal = $order->orderItems->sum(function ($item) {
                            return $item->price * $item->quantity;
                        });
                        $totalAmount = $subtotal + ($order->delivery_fee ?? 0.00);
                    @endphp
                    <p><strong>Total Amount:</strong> ₱{{ number_format($totalAmount, 2) }}
                        @if($order->payment_method === 'GCash' || $order->payment_method === 'PayMaya' || ($order->payment_method === 'Cash on Delivery' && $order->status === 'delivered'))
                            <span class="badge badge-paid" style="background-color: #28a745; color: #fff; margin-left: 5px;">Paid</span>
                        @endif
                    </p>
                    <p><strong>Status:</strong>
                        <span class="badge 
                            @if($order->status === 'pending') badge-pending
                            @elseif($order->status === 'delivering') badge-delivering
                            @elseif($order->status === 'delivered') badge-delivered
                            @else badge-cancelled @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </p>
                    <p><strong>Ordered At:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                </div>
                <div class="col-md-6">
                    @if($order->rider)
                        <p><strong>Rider:</strong> {{ $order->rider->user->name }}</p>
                    @else
                        <p><strong>Rider:</strong> Not assigned</p>
                    @endif
                </div>
            </div>

            <h5 class="mt-4" style="color: #5D3A00;">Order Items</h5>
            @if($order->orderItems->isEmpty())
                <p>No items in this order.</p>
            @else
                <table class="table table-bordered table-hover table-light">
                    <thead class="table-dark" style="background-color: #a97c50; color: white;">
                        <tr>
                            <th>Food Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->food->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>₱{{ number_format($item->price, 2) }}</td>
                                <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Subtotal:</td>
                            <td>₱{{ number_format($subtotal, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Delivery Fee:</td>
                            <td>₱{{ number_format($order->delivery_fee ?? 0.00, 2) }}</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-bold">Total:</td>
                            <td>₱{{ number_format($totalAmount, 2) }}</td>
                        </tr>
                    </tbody>
                </table>
            @endif

            <div class="mt-3">
                <a href="{{ route('admin.order_menu') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Back to Order List
                </a>
            </div>
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
</style>

@endsection