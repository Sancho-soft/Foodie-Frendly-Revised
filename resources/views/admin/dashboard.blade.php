@extends('layouts.welcome_admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="admin-header">
    <h1>Dashboard Overview</h1>
    <div class="text-muted">Welcome back, Administrator!</div>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-body">
                <h5 class="card-title">Total Users</h5>
                <p class="display-4">{{ $totalUsers }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-body">
                <h5 class="card-title">Total Orders</h5>
                <p class="display-4">{{ $totalOrders }}</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card">
            <div class="card-body">
                <h5 class="card-title">Total Revenue</h5>
                <p class="display-4">${{ number_format($totalRevenue, 2) }}</p>
            </div>
        </div>
    </div>
</div>

<!-- Role Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card border-left-primary shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                            Total Users</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalUsers }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-left-success shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                            Admins</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdmins }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-left-info shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center">
                    <div class="col mr-2">
                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                            Customers</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCustomers }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>  
@endsection