@extends('layouts.welcome_admin')

@section('content')
<!-- Custom Styles -->
<style>
    body {
        background-color: #f4f1ea;
    }

    .card {
        border: 1px solid #a1866f;
        background-color: #fffdf9;
        box-shadow: 0 4px 8px rgba(161, 134, 111, 0.2);
        border-radius: 12px;
    }

    .card-header {
        background-color: #d2b48c;
        color: #3e2f1c;
        font-weight: bold;
    }

    #userTable {
        background-color: #333;
        color: white;
    }

    #userTable thead th {
        background-color: #555;
        color: white;
        font-weight: bold;
        border: none;
    }

    #userTable tbody tr:hover {
        background-color: #444;
    }

    .badge {
        padding: 0.5em 0.75em;
        font-size: 0.875em;
        border-radius: 10px;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-primary {
        background-color: #007bff;
    }

    .badge-warning {
        background-color: #ffc107;
    }

    .search-bar {
        border-radius: 20px;
        border: 1px solid #ddd;
        padding: 10px 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .search-bar:focus {
        border-color: #a1866f;
        box-shadow: 0 2px 4px rgba(161, 134, 111, 0.3);
    }

    .btn-sm i {
        margin-right: 5px;
    }

    .btn-warning {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #fff;
    }

    .btn-warning:hover {
        background-color: #e0a800;
        border-color: #d39e00;
    }

    .btn-info {
        background-color: #17a2b8;
        border-color: #17a2b8;
        color: #fff;
    }

    .btn-info:hover {
        background-color: #138496;
        border-color: #117a8b;
    }
</style>

<div class="container-fluid">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-brown">User Management</h1>

        <div class="d-flex align-items-center">
            <form action="{{ route('admin.user_management') }}" method="GET" class="mr-3">
                <div class="input-group">
                    <select name="role_filter" class="form-control" style="min-width: 150px;" onchange="this.form.submit()">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role_filter') == 'admin' ? 'selected' : '' }}>Admins</option>
                        <option value="customer" {{ request('role_filter') == 'customer' ? 'selected' : '' }}>Customers</option>
                        <option value="rider" {{ request('role_filter') == 'rider' ? 'selected' : '' }}>Riders</option>
                    </select>
                </div>
            </form>

            <form action="{{ route('admin.user_management') }}" method="GET" class="d-flex">
                <div class="input-group">
                    <input 
                        type="text" 
                        name="search" 
                        class="form-control search-bar" 
                        placeholder="Search users..." 
                        value="{{ request('search') }}"
                        style="min-width: 200px;"
                    >
                   <div class="input-group-append">
                <button type="submit" class="btn btn-success">
              <i class="fas fa-search"></i> 
        </button>
    </div>

                </div>
            </form>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card shadow h-100 py-2">
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
        <div class="col-md-3">
            <div class="card shadow h-100 py-2">
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
        <div class="col-md-3">
            <div class="card shadow h-100 py-2">
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
        <div class="col-md-3">
            <div class="card shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Riders</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalRiders }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h3 class="m-0 font-weight-bold" style="color: rgb(0, 0, 0);">Users List</h3>
            <a href="{{ route('users.create') }}" class="btn btn-success">
                <i class="fas fa-plus"></i> 
            </a>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="userTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone Number</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                        <tr>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->role === 'rider' && $user->rider)
                                    {{ $user->rider->phone_number ?? 'N/A' }}
                                @else
                                    {{ $user->phone ?? 'N/A' }}
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $user->role === 'admin' ? 'badge-success' : ($user->role === 'customer' ? 'badge-primary' : 'badge-warning') }}">
                                    {{ $user->role }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i> 
                                </a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?')">
                                        <i class="fas fa-trash"></i> 
                                    </button>
                                </form>
                                @if($user->role === 'rider')
                                    <a href="{{ route('users.view', $user->id) }}" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-4">
                {{ $users->appends([
                    'search' => request('search'),
                    'role_filter' => request('role_filter')
                ])->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('.search-bar').on('keyup', function() {
            const searchTerm = $(this).val();
            $.ajax({
                url: "{{ route('admin.user_management') }}",
                data: { search: searchTerm },
                success: function(data) {
                    $('#userTable tbody').html($(data).find('#userTable tbody').html());
                }
            });
        });
    });
</script>
@endsection