@extends('layouts.app')

@section('title', 'Order Tracker')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Order Tracker</h1>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <!-- Orders List -->
    @forelse($orders as $order)
        <div class="card shadow-sm border-0 mb-4" style="background-color: #fefaf3;">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #A67B5B;">
                <h5 class="fw-bold text-white mb-0">Order #{{ $order->id }} - <span class="order-status">{{ ucfirst($order->status) }}</span></h5>
                <span class="text-white">Placed on {{ $order->order_date->format('Y-m-d H:i') }}</span>
            </div>
            <div class="card-body">
                <!-- Order Details -->
                <div class="row">
                    <div class="col-md-6">
                        <h6 class="fw-bold">Items:</h6>
                        <ul class="list-group mb-3">
                            @foreach($order->orderItems as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: #fffaf2;">
                                    <span>{{ $item->food->name }} (x{{ $item->quantity }})</span>
                                    <span>₱{{ number_format($item->price * $item->quantity, 2) }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <p><strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
                        <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
                        <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status) }}</p>
                        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
                    </div>
                    <div class="col-md-6">
                        <h6 class="fw-bold">Status Updates:</h6>
                        <div class="status-timeline">
                            <p>Status: <span class="badge rounded-pill {{ $order->status === 'pending' ? 'bg-warning' : ($order->status === 'delivering' ? 'bg-primary' : ($order->status === 'delivered' ? 'bg-success' : 'bg-danger')) }} order-status">{{ ucfirst($order->status) }}</span></p>
                            @if($order->status !== 'delivered' && $order->status !== 'cancelled')
                                <p>Estimated Delivery: {{ $order->order_date->addMinutes(30)->format('Y-m-d H:i') }} (approx. 30 mins)</p>
                            @endif
                            @if($order->rider)
                                <p class="rider-info">Rider: {{ $order->rider->name }} (Phone: {{ $order->rider->phone }})</p>
                            @else
                                <p class="rider-info">Rider: Not assigned yet</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="text-center">
            <p>No orders found.</p>
            <a href="{{ route('home') }}" class="btn btn-brown">Browse Menu</a>
        </div>
    @endforelse

    {{ $orders->links() }}
</div>

<!-- JavaScript for Real-Time Updates -->
@section('scripts')
    <script>
        function updateOrderStatus(orderId, cardElement) {
            fetch('{{ url('/order') }}/' + orderId + '/status')
                .then(response => response.json())
                .then(data => {
                    const statusElement = cardElement.querySelector('.order-status');
                    const riderElement = cardElement.querySelector('.rider-info');
                    const currentStatus = statusElement.textContent.toLowerCase();

                    // Update status
                    if (currentStatus !== data.status) {
                        statusElement.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                        statusElement.classList.remove('bg-warning', 'bg-primary', 'bg-success', 'bg-danger');
                        if (data.status === 'pending') {
                            statusElement.classList.add('bg-warning');
                        } else if (data.status === 'delivering') {
                            statusElement.classList.add('bg-primary');
                        } else if (data.status === 'delivered') {
                            statusElement.classList.add('bg-success');
                            const estDelivery = cardElement.querySelector('.status-timeline p:nth-child(2)');
                            if (estDelivery) estDelivery.style.display = 'none';
                        } else if (data.status === 'cancelled') {
                            statusElement.classList.add('bg-danger');
                            const estDelivery = cardElement.querySelector('.status-timeline p:nth-child(2)');
                            if (estDelivery) estDelivery.style.display = 'none';
                        }
                    }

                    // Update rider info
                    if (data.rider && riderElement) {
                        riderElement.textContent = `Rider: ${data.rider.name} (Phone: ${data.rider.phone})`;
                    }
                })
                .catch(error => console.error('Error fetching order status:', error));
        }

        document.addEventListener('DOMContentLoaded', function () {
            const orderCards = document.querySelectorAll('.card');
            orderCards.forEach(card => {
                const orderId = card.querySelector('h5').textContent.match(/#(\d+)/)[1];
                setInterval(() => updateOrderStatus(orderId, card), 10000);
                updateOrderStatus(orderId, card);
            });
        });
    </script>
@endsection
@endsection