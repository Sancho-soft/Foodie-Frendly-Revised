@extends('layouts.welcome_admin')

@section('content')
<div class="container py-4" style="background-color: #f4ece3; border-radius: 15px;">
    <h2 class="mb-4 text-center" style="color: #5D3A00;">📦 Create New Order</h2>

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
        <div class="card-body">
            <form action="{{ route('admin.orders.store') }}" method="POST">
                @csrf
                <!-- Customer Selection -->
                <div class="mb-3">
                    <label for="customer_id" class="form-label" style="color: #6F4F37;">Customer</label>
                    <select class="form-select" id="customer_id" name="customer_id" required>
                        <option value="">-- Select Customer --</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Payment Method -->
                <div class="mb-3">
                    <label for="payment_method" class="form-label" style="color: #6F4F37;">Payment Method</label>
                    <select class="form-select" id="payment_method" name="payment_method" required>
                        <option value="Cash on Delivery">Cash on Delivery</option>
                        <option value="GCash">GCash</option>
                        <option value="PayMaya">PayMaya</option>
                    </select>
                    @error('payment_method')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Order Items -->
                <div id="order-items-container">
                    <div class="order-item mb-3 border p-3" style="background-color: #fff; border-radius: 5px;">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="food_id_1" class="form-label" style="color: #6F4F37;">Food Item</label>
                                <select class="form-select" id="food_id_1" name="items[0][food_id]" required>
                                    <option value="">-- Select Food --</option>
                                    @foreach($foods as $food)
                                        <option value="{{ $food->id }}">{{ $food->name }} (₱{{ number_format($food->price, 2) }})</option>
                                    @endforeach
                                </select>
                                @error('items.0.food_id')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="quantity_1" class="form-label" style="color: #6F4F37;">Quantity</label>
                                <input type="number" class="form-control" id="quantity_1" name="items[0][quantity]" min="1" value="1" required>
                                @error('items.0.quantity')
                                    <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-3 mb-3">
                                <label class="form-label"> </label>
                                <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                            </div>
                        </div>
                    </div>
                </div>

                <button type="button" class="btn btn-primary mb-3" id="add-item">Add Item</button>

                <div class="mb-3">
                    <label for="delivery_address" class="form-label" style="color: #6F4F37;">Delivery Address</label>
                    <input type="text" class="form-control" id="delivery_address" name="delivery_address" required>
                    @error('delivery_address')
                        <div class="text-danger">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Create Order</button>
                <a href="{{ route('admin.order_menu') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>

<script>
    let itemIndex = 1;

    document.getElementById('add-item').addEventListener('click', function () {
        const container = document.getElementById('order-items-container');
        const newItem = document.createElement('div');
        newItem.className = 'order-item mb-3 border p-3';
        newItem.style.backgroundColor = '#fff';
        newItem.style.borderRadius = '5px';
        newItem.innerHTML = `
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="food_id_${itemIndex}" class="form-label" style="color: #6F4F37;">Food Item</label>
                    <select class="form-select" id="food_id_${itemIndex}" name="items[${itemIndex}][food_id]" required>
                        <option value="">-- Select Food --</option>
                        @foreach($foods as $food)
                            <option value="{{ $food->id }}">{{ $food->name }} (₱{{ number_format($food->price, 2) }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="quantity_${itemIndex}" class="form-label" style="color: #6F4F37;">Quantity</label>
                    <input type="number" class="form-control" id="quantity_${itemIndex}" name="items[${itemIndex}][quantity]" min="1" value="1" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label"> </label>
                    <button type="button" class="btn btn-danger btn-sm remove-item">Remove</button>
                </div>
            </div>
        `;
        container.appendChild(newItem);
        itemIndex++;
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-item')) {
            e.target.closest('.order-item').remove();
        }
    });
</script>

<style>
    body {
        background-color: #eaddcf;
    }
</style>
@endsection