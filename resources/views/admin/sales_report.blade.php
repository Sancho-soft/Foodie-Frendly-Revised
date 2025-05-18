@extends('layouts.welcome_admin')

@section('title', 'Sales Report')

@section('content')
<!-- Font Awesome CDN for icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<!-- XLSX for Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.16.9/xlsx.full.min.js"></script>

<div class="container py-4" style="background-color: #f4ece3; border-radius: 15px;">
    <h2 class="mb-4 text-center" style="color: #5D3A00;"><i class="fas fa-chart-bar me-2"></i> Sales Report</h2>

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

    <!-- Date Filter Form -->
    <div class="card shadow-sm mb-4" style="background-color: #fff7f0;">
        <div class="card-body">
            <h5 style="color: #5D3A00;"><i class="fas fa-filter me-2"></i> Filter Sales by Date</h5>
            <form action="{{ route('admin.sales_report.filter') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-4">
                        <label for="start_date" class="form-label">Start Date</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ request('start_date') }}" required>
                    </div>
                <div class="col-md-4">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ request('end_date') }}" required>
                    </div>
                <div class="col-md-4">
                    <label class="form-label">&nbsp;</label>
                        <button type="submit" class="btn w-100" style="background-color: #d2b48c; color: white;">
                        <i class="fas fa-search me-1"></i> Generate Report
                        </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-2 mb-4">
        <div class="col-md-3">
            <div class="card shadow-sm h-100" style="background-color: #fff7f0;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        <small class="text-muted d-block">Total Sales</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <strong>₱{{ number_format($totalSales, 2) }}</strong>
                        <i class="fas fa-dollar-sign text-primary ms-2" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100" style="background-color: #fff7f0;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        <small class="text-muted d-block">Total Orders</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <strong>{{ $totalOrders }}</strong>
                        <i class="fas fa-receipt text-success ms-2" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100" style="background-color: #fff7f0;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        <small class="text-muted d-block">Avg. Order</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <strong>₱{{ number_format($avgOrder, 2) }}</strong>
                        <i class="fas fa-calculator text-info ms-2" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm h-100" style="background-color: #fff7f0;">
                <div class="card-body d-flex justify-content-between align-items-center">
                    <div class="text-start">
                        <small class="text-muted d-block">Popular Item</small>
                    </div>
                    <div class="d-flex align-items-center">
                        <strong>{{ $popularItem }} ({{ $popularCount }})</strong>
                        <i class="fas fa-star text-warning ms-2" style="font-size: 1.5rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Sales Analysis -->
    <div class="card shadow-sm mb-4" style="background-color: #fff7f0;">
        <div class="card-header" style="background-color: #d2b48c;">
            <h5 class="mb-0 text-white">Sales Analysis</h5>
        </div>
                <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background-color: #f4ece3;">
                                <tr>
                                    <th>Category</th>
                                    <th>Total Sales</th>
                                    <th>Orders</th>
                                    <th>Percentage</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($categories as $category)
                                <tr>
                                    <td>{{ $category['name'] }}</td>
                                    <td>₱{{ number_format($category['sales'], 2) }}</td>
                                    <td>{{ $category['count'] }}</td>
                                    <td>{{ number_format(($category['sales'] / $totalSales) * 100, 1) }}%</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-md-6">
                    <canvas id="categoryPieChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Sales Trends -->
    <div class="card shadow-sm mb-4" style="background-color: #fff7f0;">
        <div class="card-header" style="background-color: #d2b48c;">
            <h5 class="mb-0 text-white">Sales Trends</h5>
        </div>
                <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <canvas id="weeklyChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Detailed Sales Report Table -->
    <div class="card shadow-sm" style="background-color: #fff7f0;">
        <div class="card-header" style="background-color: #d2b48c;">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0 text-white">Detailed Sales Report</h5>
                <button class="btn btn-light btn-sm" onclick="exportToExcel()">
                    <i class="fas fa-file-excel me-1"></i> Export to Excel
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover" id="salesTable">
                    <thead style="background-color: #f4ece3;">
                        <tr>
                            <th>Date</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Items</th>
                            <th>Subtotal</th>
                            <th>Tax Fee</th>
                            <th>Delivery Fee</th>
                            <th>Total</th>
                            <th>Payment Method</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                @foreach($recentOrders as $order)
                    @php
                                $subtotal = $order->orderItems->sum(function($item) {
                                    return $item->price * $item->quantity;
                                });
                                $taxFee = 20.00;
                                $deliveryFee = $order->delivery_fee ?? 0.00;
                                $totalAmount = $order->total_amount;
                    @endphp
                            <tr>
                                <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name }}</td>
                                <td>
                                @foreach($order->orderItems as $item)
                                        {{ $item->food->name }} (x{{ $item->quantity }}){{ !$loop->last ? ', ' : '' }}
                                @endforeach
                                </td>
                                <td>₱{{ number_format($subtotal, 2) }}</td>
                                <td>₱{{ number_format($taxFee, 2) }}</td>
                                <td>₱{{ number_format($deliveryFee, 2) }}</td>
                                <td>₱{{ number_format($totalAmount, 2) }}</td>
                                <td>{{ $order->payment_method }}</td>
                                <td>
                            <span class="badge {{ $order->status === 'delivered' ? 'bg-success' : 'bg-warning' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot style="background-color: #f4ece3;">
                        <tr class="fw-bold">
                            <td colspan="4">Total</td>
                            <td>₱{{ number_format($recentOrders->sum(function($order) {
                                return $order->orderItems->sum(function($item) {
                                    return $item->price * $item->quantity;
                                });
                            }), 2) }}</td>
                            <td>₱{{ number_format($recentOrders->count() * 20.00, 2) }}</td>
                            <td>₱{{ number_format($recentOrders->sum('delivery_fee'), 2) }}</td>
                            <td>₱{{ number_format($recentOrders->sum('total_amount'), 2) }}</td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                </table>
                    </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Weekly Sales Chart
    const weeklyChartCtx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(weeklyChartCtx, {
        type: 'bar',
        data: {
            labels: @json($weeklySales['labels']),
            datasets: [{
                label: 'Sales',
                data: @json($weeklySales['data']),
                backgroundColor: '#d2b48c',
                borderColor: '#a97c50',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return '₱' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return '₱' + value.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                        }
                    }
                }
            }
        }
    });

    // Category Pie Chart
    const categoryPieChartCtx = document.getElementById('categoryPieChart').getContext('2d');
    new Chart(categoryPieChartCtx, {
        type: 'pie',
        data: {
            labels: @json(collect($categories)->pluck('name')),
            datasets: [{
                data: @json(collect($categories)->pluck('sales')),
                backgroundColor: @json(collect($categories)->pluck('color')),
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                    labels: {
                        boxWidth: 20,
                        padding: 15
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.raw.toLocaleString('en-US', { 
                                style: 'currency', 
                                currency: 'PHP'
                            });
                            return `${context.label}: ${value}`;
                        }
                    }
                }
            }
        }
    });

    // Export to Excel function
    function exportToExcel() {
        const table = document.getElementById('salesTable');
        const wb = XLSX.utils.table_to_book(table, {sheet: "Sales Report"});
        const wbout = XLSX.write(wb, {bookType: 'xlsx', type: 'binary'});

        function s2ab(s) {
            const buf = new ArrayBuffer(s.length);
            const view = new Uint8Array(buf);
            for (let i = 0; i < s.length; i++) view[i] = s.charCodeAt(i) & 0xFF;
            return buf;
        }

        const blob = new Blob([s2ab(wbout)], {type: 'application/octet-stream'});
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'sales_report_' + new Date().toISOString().slice(0,10) + '.xlsx';
        a.click();
        window.URL.revokeObjectURL(url);
    }
</script>
@endpush
@endsection