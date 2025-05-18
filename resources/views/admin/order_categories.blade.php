@extends('layouts.welcome_admin')

@section('title', 'Order Categories')

@section('content')
    <div class="admin-header">
        <h2 style="color: var(--primary-color);">Add New Food Item</h2>
    </div>

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
<!-- Add Food Form -->
<div class="admin-card mb-4">
    <div class="card-body">
        <form action="{{ route('foods.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">
                <div class="col-md-3">
                    <label for="name" class="form-label fw-bold">Food Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="description" class="form-label fw-bold">Description</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}">
                    @error('description')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="price" class="form-label fw-bold">Price (₱)</label>
                    <input type="number" step="0.01" max="999.99" class="form-control" id="price" name="price" value="{{ old('price') }}" required>
                    @error('price')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="category" class="form-label fw-bold">Category</label>
                    <input type="text" class="form-control" id="category" name="category" value="{{ old('category') }}" placeholder="e.g., Pizza, Burger, Drink" required>
                    @error('category')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-2">
                    <label for="image" class="form-label fw-bold">Image</label>
                    <input type="file" class="form-control" id="image" name="image">
                    @error('image')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-success btn-sm">
                        + Add Food
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>


    <!-- Food Table -->
    <div class="admin-card">
        <div class="card-body">
            <h5 style="color: var(--primary-color); margin-bottom: 1.5rem;">Food Items</h5>
            @if($foods->isEmpty())
                <p class="text-center">No food items available.</p>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead style="background-color: var(--primary-color); color: white;">
                            <tr>
                                <th>#</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($foods as $food)
                                <tr>
                                    <td>{{ $food->id }}</td>
                                    <td>
                                        @if($food->image)
                                            <img src="{{ str_starts_with($food->image, 'http') ? $food->image : asset('storage/' . $food->image) }}" alt="{{ $food->name }}" style="width: 50px; height: 50px; object-fit: cover; border-radius: 5px;">
                                        @else
                                            <span>No Image</span>
                                        @endif
                                    </td>
                                    <td>{{ $food->name }}</td>
                                    <td>{{ $food->description ?? 'N/A' }}</td>
                                    <td>₱{{ number_format($food->price, 2) }}</td>
                                    <td>{{ $food->category }}</td>
                                    <td>
                                        <a href="{{ route('foods.edit', $food) }}" class="btn btn-warning btn-sm">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                        <form action="{{ route('foods.destroy', $food) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Are you sure you want to delete this food item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $foods->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection