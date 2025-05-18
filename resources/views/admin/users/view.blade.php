@extends('layouts.welcome_admin')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Rider Details</div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="form-group">
                        <label>Name:</label>
                        <p>{{ $user->name }}</p>
                    </div>

                    <div class="form-group">
                        <label>Email:</label>
                        <p>{{ $user->email }}</p>
                    </div>

                    <div class="form-group">
                        <label>Role:</label>
                        <p>{{ $user->role }}</p>
                    </div>

                    <div class="form-group">
                        <label>Phone Number:</label>
                        <p>{{ $user->rider->phone_number ?? 'N/A' }}</p>
                    </div>

                    <div class="form-group">
                        <label>License Number:</label>
                        <p>{{ $user->rider->license_number ?? 'N/A' }}</p>
                    </div>

                    <a href="{{ route('admin.user_management') }}" class="btn btn-primary">Back to User Management</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection