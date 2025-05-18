@extends('layouts.app')

@section('title', 'My Orders')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 text-brown fw-bold">My Orders</h1>
        <a href="{{ route('home') }}" class="btn btn-outline-brown">
            <i class="bi bi-shop me-2"></i>Browse Menu
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Orders List -->
    @forelse($orders as $order)
        <div class="card shadow-sm border-0 mb-4 order-card" style="background-color: #fff;" data-order-id="{{ $order->id }}">
            <div class="card-header d-flex justify-content-between align-items-center bg-brown text-white py-3">
                <div>
                    <h5 class="fw-bold mb-0">Order #{{ $order->id }}</h5>
                    <small>{{ $order->order_date->format('F d, Y h:i A') }}</small>
                </div>
                <span class="badge rounded-pill status-badge status-{{ $order->status }}">
                    {{ ucfirst($order->status) }}
                </span>
            </div>
            <div class="card-body" style="background-color: #fefaf3;">
                <!-- Order Items -->
                <div class="order-items mb-4">
                    <h6 class="fw-bold mb-3">Items Ordered:</h6>
                    <div class="row">
                        @foreach($order->orderItems as $item)
                            <div class="col-md-6 mb-3">
                                <div class="d-flex align-items-center p-2 rounded" style="background-color: #fff;">
                                    @if($item->food->image)
                                        <img src="{{ asset('storage/' . $item->food->image) }}" 
                                             alt="{{ $item->food->name }}" 
                                             class="rounded me-3"
                                             style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="placeholder-image rounded me-3"></div>
                                    @endif
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->food->name }}</h6>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <span class="text-muted">Qty: {{ $item->quantity }}</span>
                                            <span class="fw-bold">₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Order Details -->
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="order-info p-3 rounded" style="background-color: #fff;">
                            <h6 class="fw-bold mb-3">Order Information</h6>
                            <div class="mb-2">
                                <strong>Total Amount:</strong> 
                                <span class="float-end">₱{{ number_format($order->total_amount, 2) }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Payment Method:</strong> 
                                <span class="float-end">{{ $order->payment_method }}</span>
                            </div>
                            <div class="mb-2">
                                <strong>Payment Status:</strong> 
                                <span class="float-end">{{ ucfirst($order->payment_status) }}</span>
                            </div>
                            <div>
                                <strong>Delivery Address:</strong>
                                <p class="mb-0 mt-1 text-muted">{{ $order->delivery_address }}</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="delivery-info p-3 rounded" style="background-color: #fff;">
                            <h6 class="fw-bold mb-3">Delivery Status</h6>
                            <div class="progress mb-3" style="height: 20px;">
                                <div class="progress-bar progress-bar-{{ $order->status }}" 
                                     id="status-progress-{{ $order->id }}" 
                                     role="progressbar" 
                                     style="width: 0%;" 
                                     aria-valuenow="0">
                                    {{ ucfirst($order->status) }}
                                </div>
                            </div>
                            @if($order->rider)
                                <div class="rider-info">
                                    <strong>Rider:</strong> {{ $order->rider->user->name }}
                                    @if($order->rider->phone_number)
                                        <br>
                                        <strong>Contact:</strong> {{ $order->rider->phone_number }}
                                    @endif
                                </div>
                            @else
                                <p class="rider-info mb-0">Rider not yet assigned</p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="text-end mt-4">
                    @if($order->status === 'pending')
                        <form action="{{ route('order.cancel', $order->id) }}" method="POST" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger me-2" 
                                    onclick="return confirm('Are you sure you want to cancel this order?')">
                                <i class="bi bi-x-circle me-2"></i>Cancel Order
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('order.view', $order->id) }}" class="btn btn-brown">
                        <i class="bi bi-eye me-2"></i>View Details
                    </a>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-clock-history" style="font-size: 4rem; color: var(--secondary-brown);"></i>
            </div>
            <h3 class="text-brown mb-4">No Orders Yet</h3>
            <p class="text-muted mb-4">You haven't placed any orders yet. Start ordering your favorite food!</p>
            <a href="{{ route('home') }}" class="btn btn-brown btn-lg">
                <i class="bi bi-shop me-2"></i>Browse Menu
            </a>
        </div>
    @endforelse

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-4">
        {{ $orders->links('pagination::bootstrap-4') }}
    </div>
</div>

<style>
    .order-card {
        transition: all 0.3s ease;
        border-radius: 12px;
        overflow: hidden;
    }
    .order-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1) !important;
    }
    .order-card.selected {
        border-left: 4px solid var(--primary-brown) !important;
    }
    .status-badge {
        font-size: 0.85rem;
        padding: 0.5em 1em;
    }
    .status-pending {
        background-color: #ff9800;
    }
    .status-delivering {
        background-color: #007bff;
    }
    .status-delivered {
        background-color: #28a745;
    }
    .status-cancelled {
        background-color: #dc3545;
    }
    .progress {
        background-color: #e9ecef;
        border-radius: 10px;
        overflow: hidden;
    }
    .progress-bar {
        transition: width 0.5s ease-in-out;
        text-align: center;
        line-height: 20px;
        font-size: 0.85rem;
        font-weight: 600;
    }
    .progress-bar-pending {
        background-color: #ff9800;
    }
    .progress-bar-delivering {
        background-color: #007bff;
    }
    .progress-bar-delivered {
        background-color: #28a745;
    }
    .progress-bar-cancelled {
        background-color: #dc3545;
    }
    .placeholder-image {
        width: 60px;
        height: 60px;
        background-color: #ddd;
    }
    .order-info, .delivery-info {
        height: 100%;
        border: 1px solid rgba(0,0,0,0.05);
    }
    @media (max-width: 768px) {
        .card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }
        .status-badge {
            align-self: flex-start;
        }
        .order-items .col-md-6 {
            padding: 0;
        }
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Auto-dismiss alerts
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => successAlert.classList.remove('show'), 3000);
        }

        // Handle order selection
        const cards = document.querySelectorAll('.order-card');
        cards.forEach(card => {
            card.addEventListener('click', function(e) {
                // Don't select if clicking on a button or link
                if (e.target.tagName === 'BUTTON' || e.target.tagName === 'A' || 
                    e.target.closest('button') || e.target.closest('a')) {
                    return;
                }
                cards.forEach(c => c.classList.remove('selected'));
                this.classList.add('selected');
            });
        });

        // Initialize progress bars
        @foreach($orders as $order)
            const progressElement{{ $order->id }} = document.getElementById('status-progress-{{ $order->id }}');
            if (progressElement{{ $order->id }}) {
                let progressValue = 0;
                if ('{{ $order->status }}' === 'pending') progressValue = 0;
                else if ('{{ $order->status }}' === 'delivering') progressValue = 50;
                else if ('{{ $order->status }}' === 'delivered' || '{{ $order->status }}' === 'cancelled') progressValue = 100;
                progressElement{{ $order->id }}.style.width = progressValue + '%';
                progressElement{{ $order->id }}.setAttribute('aria-valuenow', progressValue);
            }
        @endforeach

        // Check for selected order in URL
        const urlParams = new URLSearchParams(window.location.search);
        const selectedOrderId = urlParams.get('selected');
        if (selectedOrderId) {
            const selectedCard = document.querySelector(`[data-order-id="${selectedOrderId}"]`);
            if (selectedCard) {
                selectedCard.classList.add('selected');
                selectedCard.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
</script>
@endsection