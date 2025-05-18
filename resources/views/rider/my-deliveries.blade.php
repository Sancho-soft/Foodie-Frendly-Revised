@extends('layouts.rider')

@section('title', 'My Deliveries')

@section('content')
<div class="container-fluid mt-3">
    <div class="row">
        <div class="col-12">

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

            <div class="card shadow" style="border-radius: 12px; background-color: #fefaf3;">
                <div class="card-header d-flex justify-content-between align-items-center py-3" style="background-color: #c8a879;">
                    <h5 class="fw-bold text-dark mb-0" style="font-size: 1.2rem;">
                        <i class="bi bi-truck me-2"></i> My Deliveries
                    </h5>
                </div>
                <div class="card-body p-3">
                    @if($deliveredOrders->isEmpty())
                        <p class="text-center mb-0 py-4" style="font-size: 1rem;">You have not completed any deliveries yet.</p>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover mb-0" style="font-size: 0.95rem;">
                                <thead class="text-white" style="background-color: #3e3e3e;">
                                    <tr>
                                        <th class="px-3 py-2">Order #</th>
                                        <th class="px-3 py-2">Customer</th>
                                        <th class="px-3 py-2">Delivery Address</th>
                                        <th class="px-3 py-2">Payment</th>
                                        <th class="px-3 py-2">Total</th>
                                        <th class="px-3 py-2">Status</th>
                                        <th class="px-3 py-2">Delivered At</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($deliveredOrders as $order)
                                        <tr>
                                            <td class="px-3 py-2">{{ $order->id }}</td>
                                            <td class="px-3 py-2">{{ $order->user->name }}</td>
                                            <td class="px-3 py-2">{{ $order->delivery_address ?? 'Not specified' }}</td>
                                            <td class="px-3 py-2">{{ $order->payment_method ?? 'Not specified' }}</td>
                                            <td class="px-3 py-2">
                                                @php
                                                    $subtotal = $order->orderItems->sum(fn($item) => $item->price * $item->quantity);
                                                    $deliveryFee = $order->delivery_fee ?? 50.00;
                                                    $total = $subtotal + $deliveryFee;
                                                @endphp
                                                ₱{{ number_format($total, 2) }}
                                            </td>
                                            <td class="px-3 py-2">
                                                <span class="badge rounded-pill badge-{{ $order->status }}" style="font-size: 0.85rem; padding: 0.35em 0.65em;">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2">{{ $order->updated_at->format('M j, Y H:i') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</div>

<style>
    .badge-delivered {
        background-color: #28a745;
        color: #fff;
    }
    .badge-cancelled {
        background-color: #dc3545;
        color: #fff;
    }
    .table {
        width: 100%;
        margin-bottom: 0;
    }
    .table th, .table td {
        vertical-align: middle;
    }
    .card {
        border: none;
    }
    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
</style>
@endsection