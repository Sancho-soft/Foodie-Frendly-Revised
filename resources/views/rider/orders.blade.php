@extends('layouts.rider')

@section('title', 'Rider Dashboard')

@section('content')
<div class="container-fluid mt-2 px-4">
    <!-- Flash Messages -->
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

    <!-- Current Order -->
    @if($currentOrder)
        <div class="mb-4">
            <h5 class="fw-bold text-dark mb-3" style="font-size: 1.5rem; background-color: #c8a879; padding: 10px; border-radius: 8px;">
                <i class="bi bi-receipt me-1"></i> Current Order - #{{ $currentOrder->id }}
            </h5>
            <div style="background-color: #fefaf3; padding: 20px; border-radius: 8px;">
                <p style="font-size: 1.2rem;"><strong>Customer:</strong> {{ $currentOrder->user->name }}</p>
                <p style="font-size: 1.2rem;"><strong>Delivery Address:</strong> {{ $currentOrder->delivery_address ?? 'Not specified' }}</p>
                <p style="font-size: 1.2rem;"><strong>Payment Method:</strong> {{ $currentOrder->payment_method ?? 'Not specified' }}</p>
                <p style="font-size: 1.2rem;"><strong>Status:</strong> 
                    <span class="badge rounded-pill badge-{{ $currentOrder->status }}">
                        {{ ucfirst($currentOrder->status) }}
                    </span>
                </p>
                <h5 style="font-size: 1.3rem;">Order Items:</h5>
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="text-white" style="background-color: #3e3e3e;">
                            <tr>
                                <th>Food</th>
                                <th>Quantity</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($currentOrder->orderItems as $item)
                                <tr>
                                    <td>{{ $item->food->name }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>₱{{ number_format($item->price, 2) }}</td>
                                    <td>₱{{ number_format($item->price * $item->quantity, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    @php
                        $currentOrderSubtotal = $currentOrder->orderItems->sum(fn($item) => $item->price * $item->quantity);
                        $currentOrderDeliveryFee = $currentOrder->delivery_fee ?? 50.00;
                        $currentOrderTotal = $currentOrderSubtotal + $currentOrderDeliveryFee;
                    @endphp
                    <div class="d-flex justify-content-between" style="font-size: 1.1rem;">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($currentOrderSubtotal, 2) }}</span>
                    </div>
                    <div class="d-flex justify-content-between" style="font-size: 1.1rem;">
                        <span>Delivery Fee</span>
                        <span>₱{{ number_format($currentOrderDeliveryFee, 2) }}</span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between fw-bold" style="font-size: 1.2rem;">
                        <span>Total</span>
                        <span style="color: #5D3A00;">₱{{ number_format($currentOrderTotal, 2) }}</span>
                    </div>
                </div>
                <div class="mt-3 text-end">
                    @if($currentOrder->status === 'delivering')
                        <form action="{{ route('rider.finishDelivery', $currentOrder) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn text-white" style="background-color: #8b5e3c;">
                                <i class="bi bi-check-circle me-1"></i> Finish Delivery
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Available Orders -->
    <div class="mt-4">
        <h5 class="fw-bold text-dark mb-3" style="font-size: 1.5rem; background-color: #c8a879; padding: 10px; border-radius: 8px;">
            <i class="bi bi-list-ul me-1"></i> Available Orders
        </h5>
        <div style="background-color: #fefaf3; padding: 20px; border-radius: 8px;">
            @if($currentOrder)
                <p class="text-center" style="font-size: 1.1rem;">You are currently handling an order. Complete it to select another.</p>
            @elseif($pendingOrders->isEmpty())
                <p class="text-center" style="font-size: 1.1rem;">No pending orders available at the moment.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="text-white" style="background-color: #3e3e3e;">
                            <tr>
                                <th>#</th>
                                <th>Customer</th>
                                <th>Delivery Address</th>
                                <th>Payment Method</th>
                                <th>Total Price</th>
                                <th>Ordered At</th>
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
                                    <td>{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->delivery_address ?? 'Not specified' }}</td>
                                    <td>{{ $order->payment_method ?? 'Not specified' }}</td>
                                    <td>₱{{ number_format($orderTotal, 2) }}</td>
                                    <td>{{ $order->order_date->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <form action="{{ route('rider.startDelivery', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="bi bi-play-circle me-1"></i> Start Delivery
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
</style>

@endsection