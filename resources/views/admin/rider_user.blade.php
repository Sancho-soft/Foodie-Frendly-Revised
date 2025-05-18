@extends('layouts.welcome_admin')

@section('title', 'Rider Users')

@section('content')
<div class="container mt-2">
    <h2 class="mb-3 text-center text-brown">🏍️ Rider User-Management - Admin Panel 🏍️</h2>
    
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

    <!-- Add Rider Form -->
    <div class="mb-5">
        <div class="card shadow" style="border-radius: 12px; background-color: #fefaf3;">
            <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #c8a879;">
                <h5 class="fw-bold text-dark mb-0">Add Rider</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.riders.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Enter full name" style="background-color: #fffaf2;" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Enter email" style="background-color: #fffaf2;" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Enter password" style="background-color: #fffaf2;" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirmation" class="form-control" placeholder="Confirm password" style="background-color: #fffaf2;" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone Number</label>
                            <input type="number" name="phone" class="form-control @error('phone') is-invalid @enderror" placeholder="Enter phone number" style="background-color: #fffaf2;" value="{{ old('phone') }}" required>
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">License Code</label>
                            <input type="text" name="license_code" class="form-control @error('license_code') is-invalid @enderror" placeholder="Enter.Formatted: license code" style="background-color: #fffaf2;" value="{{ old('license_code') }}" required>
                            @error('license_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select @error('status') is-invalid @enderror" style="background-color: #fffaf2;" required>
                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            @error('status')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn text-white" style="background-color: #8b5e3c;">
                            <i class="bi bi-plus-circle me-1"></i> Save Rider
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Riders List Table -->
    <div class="card shadow" style="border-radius: 12px;">
        <div class="card-header d-flex justify-content-between align-items-center" style="background-color: #c8a879;">
            <h5 class="fw-bold text-dark mb-0">Rider Users</h5>
            <form action="{{ route('admin.riders.index') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Search riders..." value="{{ request('search') }}">
                    <select name="status_filter" class="form-select">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status_filter') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status_filter') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="text-white" style="background-color: #3e3e3e;">
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>License Code</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($riders as $rider)
                        <tr>
                            <td>{{ $rider->id }}</td>
                            <td>{{ $rider->name }}</td>
                            <td>{{ $rider->email }}</td>
                            <td>{{ $rider->phone }}</td>
                            <td>{{ $rider->license_code }}</td>
                            <td>
                                <span class="badge rounded-pill {{ $rider->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($rider->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.riders.edit', $rider->id) }}" class="btn btn-warning btn-sm me-1">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="{{ route('admin.riders.destroy', $rider->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">No riders found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection