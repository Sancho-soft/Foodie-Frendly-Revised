@extends('layouts.app')

@section('title', 'Cart')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0 text-brown fw-bold">Your Cart</h1>
        <a href="{{ route('home') }}" class="btn btn-outline-brown">
            <i class="bi bi-arrow-left me-2"></i>Continue Shopping
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

    @if($cartItems->isEmpty())
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="bi bi-cart-x" style="font-size: 4rem; color: var(--secondary-brown);"></i>
            </div>
            <h3 class="text-brown mb-4">Your cart is empty</h3>
            <p class="text-muted mb-4">Looks like you haven't added any items to your cart yet.</p>
            <a href="{{ route('home') }}" class="btn btn-brown btn-lg">
                <i class="bi bi-shop me-2"></i>Browse Menu
            </a>
        </div>
    @else
        <div class="row">
            <!-- Cart Items -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm border-0" style="background-color: #fefaf3;">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Item</th>
                                        <th class="text-center">Price</th>
                                        <th class="text-center">Quantity</th>
                                        <th class="text-center">Subtotal</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($cartItems as $item)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="{{ asset('storage/' . $item->food->image) }}" 
                                                         alt="{{ $item->food->name }}" 
                                                         class="rounded me-3"
                                                         style="width: 60px; height: 60px; object-fit: cover;">
                                                    <div>
                                                        <h6 class="mb-0">{{ $item->food->name }}</h6>
                                                        <small class="text-muted">{{ Str::limit($item->food->description, 50) }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="text-center">₱{{ number_format($item->food->price, 2) }}</td>
                                            <td class="text-center" style="width: 150px;">
                                                <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <div class="input-group input-group-sm">
                                                        <input type="number" name="quantity" value="{{ $item->quantity }}" 
                                                               min="1" class="form-control text-center border-brown" 
                                                               style="background-color: #fffaf2;">
                                                        <button type="submit" class="btn btn-outline-brown">
                                                            <i class="bi bi-arrow-repeat"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </td>
                                            <td class="text-center">₱{{ number_format($item->quantity * $item->food->price, 2) }}</td>
                                            <td class="text-end">
                                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-link text-danger" 
                                                            onclick="return confirm('Remove this item from cart?')">
                                                        <i class="bi bi-trash"></i>
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
            </div>

            <!-- Order Summary -->
            <div class="col-lg-4">
                @php
                    $subtotal = $total;
                    $vatFee = 20.00;
                    $deliveryFee = \Illuminate\Support\Facades\DB::table('delivery_fees')
                        ->orderBy('date_added', 'desc')
                        ->first()->fee ?? 50.00;
                    $totalWithVatAndDelivery = $subtotal + $vatFee + $deliveryFee;
                @endphp

                <div class="card shadow-sm border-0 mb-4" style="background-color: #fefaf3;">
                    <div class="card-body">
                        <h5 class="card-title fw-bold mb-4">Order Summary</h5>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span>Subtotal</span>
                                <span>₱{{ number_format($subtotal, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Tax Fee</span>
                                <span>₱{{ number_format($vatFee, 2) }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span>Delivery Fee</span>
                                <span>₱{{ number_format($deliveryFee, 2) }}</span>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between fw-bold">
                                <span>Total Amount</span>
                                <span>₱{{ number_format($totalWithVatAndDelivery, 2) }}</span>
                            </div>
                        </div>

                        <form id="checkoutForm" action="{{ route('cart.checkout') }}" method="POST">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label">Payment Method</label>
                                <select name="payment_method" id="payment_method" 
                                        class="form-select @error('payment_method') is-invalid @enderror" 
                                        style="background-color: #fffaf2;" required>
                                    <option value="Cash on Delivery">Cash on Delivery</option>
                                    <option value="GCash">GCash</option>
                                    <option value="PayMaya">PayMaya</option>
                                </select>
                                @error('payment_method')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-4">
                                <label class="form-label">Delivery Address</label>
                                <textarea name="delivery_address" id="delivery_address" 
                                          class="form-control @error('delivery_address') is-invalid @enderror" 
                                          style="background-color: #fffaf2;" rows="3" required 
                                          placeholder="Please input your address">{{ old('delivery_address') }}</textarea>
                                @error('delivery_address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="button" class="btn btn-brown w-100" onclick="showConfirmation()">
                                <i class="bi bi-bag-check me-2"></i>Place Order
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Confirmation Modal -->
        <div class="modal fade" id="confirmOrderModal" tabindex="-1" aria-labelledby="confirmOrderModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content" style="background-color: #fefaf3;">
                    <div class="modal-header bg-brown text-white">
                        <h5 class="modal-title" id="confirmOrderModalLabel">Confirm Your Order</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h6 class="fw-bold mb-3">Order Summary:</h6>
                        <div class="order-items mb-3">
                            @foreach($cartItems as $item)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset('storage/' . $item->food->image) }}" 
                                             alt="{{ $item->food->name }}" 
                                             class="rounded me-2"
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                        <span>{{ $item->food->name }} x {{ $item->quantity }}</span>
                                    </div>
                                    <span>₱{{ number_format($item->quantity * $item->food->price, 2) }}</span>
                                </div>
                            @endforeach
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Subtotal:</span>
                            <span>₱{{ number_format($subtotal, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tax Fee:</span>
                            <span>₱{{ number_format($vatFee, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Delivery Fee:</span>
                            <span>₱{{ number_format($deliveryFee, 2) }}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold mt-2">
                            <span>Total Amount:</span>
                            <span>₱{{ number_format($totalWithVatAndDelivery, 2) }}</span>
                        </div>
                        <hr>
                        <div class="delivery-details mt-3">
                            <h6 class="fw-bold">Delivery Details:</h6>
                            <p class="mb-1"><strong>Payment Method:</strong> <span id="modalPaymentMethod"></span></p>
                            <p class="mb-1"><strong>Delivery Address:</strong> <span id="modalDeliveryAddress"></span></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-brown" onclick="submitOrder()">
                            <i class="bi bi-check2-circle me-2"></i>Confirm Order
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .table th {
        font-weight: 600;
        font-size: 0.9rem;
    }
    .input-group-sm > .form-control {
        font-size: 0.9rem;
    }
    .btn-outline-brown {
        color: var(--primary-brown);
        border-color: var(--primary-brown);
    }
    .btn-outline-brown:hover {
        color: white;
        background-color: var(--primary-brown);
    }
    @media (max-width: 768px) {
        .table {
            font-size: 0.9rem;
        }
        .btn {
            font-size: 0.9rem;
        }
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.remove('show');
            }, 3000);
        }
    });

    function showConfirmation() {
        const paymentMethod = document.getElementById('payment_method').value;
        const deliveryAddress = document.getElementById('delivery_address').value.trim();

        if (!paymentMethod || !deliveryAddress) {
            alert('Please fill in all required fields before proceeding.');
            return;
        }

        document.getElementById('modalPaymentMethod').textContent = paymentMethod;
        document.getElementById('modalDeliveryAddress').textContent = deliveryAddress;

        const modal = new bootstrap.Modal(document.getElementById('confirmOrderModal'));
        modal.show();
    }

    function submitOrder() {
        const submitButton = document.querySelector('.modal-footer .btn-brown');
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';

        document.getElementById('checkoutForm').submit();
    }
</script>
@endsection