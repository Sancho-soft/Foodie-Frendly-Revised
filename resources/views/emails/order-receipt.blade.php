<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .order-info {
            background: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .total-section {
            margin-top: 20px;
            border-top: 2px solid #ddd;
            padding-top: 10px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Order Confirmation</h1>
        <p>Thank you for your order!</p>
    </div>

    <div class="order-info">
        <h2>Order #{{ $order->id }}</h2>
        <p><strong>Order Date:</strong> {{ $order->created_at->format('F d, Y h:i A') }}</p>
        <p><strong>Delivery Address:</strong> {{ $order->delivery_address }}</p>
        <p><strong>Payment Method:</strong> {{ $order->payment_method }}</p>
    </div>

    <h3>Order Details</h3>
    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Quantity</th>
                <th>Price</th>
                <th>Total</th>
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
        </tbody>
    </table>

    <div class="total-section">
        @php
            $subtotal = $order->orderItems->sum(function($item) {
                return $item->price * $item->quantity;
            });
            $taxFee = 20.00;
        @endphp
        <p><strong>Subtotal:</strong> ₱{{ number_format($subtotal, 2) }}</p>
        <p><strong>Tax Fee:</strong> ₱{{ number_format($taxFee, 2) }}</p>
        <p><strong>Delivery Fee:</strong> ₱{{ number_format($order->delivery_fee, 2) }}</p>
        <p><strong>Total Amount:</strong> ₱{{ number_format($order->total_amount, 2) }}</p>
    </div>

    <div class="footer">
        <p>Thank you for choosing Foodie Friendly!</p>
        <p>If you have any questions about your order, please contact our customer support.</p>
    </div>
</body>
</html> 