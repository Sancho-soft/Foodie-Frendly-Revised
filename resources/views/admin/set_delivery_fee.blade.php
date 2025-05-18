@extends('layouts.welcome_admin')

@section('content')
<div class="container py-4" style="background-color: #f4ece3; border-radius: 15px;">
    <h2 class="mb-4 text-center" style="color: #5D3A00;">⚙️ Set Delivery Fee</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm" style="background-color: #fff7f0;">
        <div class="card-header fw-bold" style="background-color: #d2b48c; color: #3e2600;">
            📌 Update Delivery Fee
        </div>
        <div class="card-body">
            <form action="{{ route('admin.update_delivery_fee') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="delivery_fee" class="form-label">Delivery Fee (₱)</label>
                    <input type="number" step="0.01" name="delivery_fee" id="delivery_fee" class="form-control" value="{{ $currentDeliveryFee }}" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ route('admin.order_menu') }}" class="btn btn-secondary">Back</a>
            </form>
        </div>
    </div>
</div>
@endsection