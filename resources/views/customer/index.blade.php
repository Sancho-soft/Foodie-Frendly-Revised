@extends('layouts.app')

@section('title', 'Food Menu')

@section('content')
<div class="container mt-4">
    <!-- Header + View Cart button aligned -->
    <div class="d-flex justify-content-between align-items-center mb-4">
       <h1 class="mb-0 text-dark fw-bold">FOOD MENU</h1>
        <a href="{{ route('cart') }}" class="btn btn-brown">
            <i class="bi bi-cart4 me-1"></i> View Cart
        </a>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert" id="success-alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Search and Filter Bar -->
    <div class="row mb-4">
        <div class="col-md-6 mb-3 mb-md-0">
            <form action="{{ route('home') }}" method="GET" class="d-flex">
                <input type="text" name="q" class="form-control me-2 border-brown" placeholder="Search food by name or description..." value="{{ request()->input('q') }}" style="background-color: #fffaf2;">
                <button type="submit" class="btn btn-brown">Search</button>
            </form>
        </div>
        <div class="col-md-6">
            <form action="{{ route('home') }}" method="GET" class="d-flex justify-content-md-end">
                <select name="category" class="form-control border-brown me-2" style="background-color: #fffaf2; max-width: 200px;" onchange="this.form.submit()">
                    <option value="">All Categories</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category }}" {{ request()->input('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="q" value="{{ request()->input('q') }}">
            </form>
        </div>
    </div>

    <!-- Food Items Grid -->
    <div class="row g-4">
        @foreach ($foods as $food)
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 shadow-sm border-0 food-card" style="background-color: #fefaf3;">
                    <img src="{{ asset('storage/' . $food->image) }}" class="card-img-top food-image" alt="{{ $food->name }}">
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold mb-2">{{ $food->name }}</h5>
                        <p class="card-text text-muted mb-3">{{ $food->description }}</p>
                        
                        <form action="{{ route('cart.add', $food->id) }}" method="POST" class="food-options-form mt-auto">
                            @csrf
                            <div class="mb-3">
                                @php
                                    $foodOptions = $food->getSpecificOptions();
                                @endphp
                                
                                @if(isset($foodOptions['size_options']))
                                    <div class="option-section mb-3">
                                        <label class="form-label fw-bold">Size</label>
                                        <select name="size" class="form-select border-brown" style="background-color: #fffaf2;" required>
                                            @foreach($foodOptions['size_options'] as $option)
                                                <option value="{{ strtolower($option['name']) }}" data-price="{{ $option['price'] }}">
                                                    {{ $option['name'] }} 
                                                    @if($option['price'] > 0)
                                                        (+₱{{ number_format($option['price'], 2) }})
                                                    @else
                                                        (₱{{ number_format($food->price, 2) }})
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                @endif

                                @if(isset($foodOptions['add_ons']) && count($foodOptions['add_ons']) > 0)
                                    <div class="option-section">
                                        <label class="form-label fw-bold">Add-ons</label>
                                        <div class="row g-2">
                                            @foreach($foodOptions['add_ons'] as $option)
                                                <div class="col-6">
                                                    <div class="form-check">
                                                        <input type="checkbox" name="addons[]" class="form-check-input" 
                                                               value="{{ strtolower($option['name']) }}" data-price="{{ $option['price'] }}">
                                                        <label class="form-check-label small">
                                                            {{ $option['name'] }}
                                                            @if($option['price'] > 0)
                                                                (+₱{{ number_format($option['price'], 2) }})
                                                            @endif
                                                        </label>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-3">
                                <div>
                                    <span class="fw-bold">Total: ₱</span>
                                    <span class="total-price fw-bold">{{ number_format($food->price, 2) }}</span>
                                </div>
                                <div class="d-flex align-items-center">
                                    <input type="number" name="quantity" value="1" min="1" class="form-control me-2 border-brown quantity-input" style="width: 60px; background-color: #fffaf2;">
                                    <button type="submit" class="btn btn-brown">
                                        <i class="bi bi-cart-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .food-card {
        transition: transform 0.2s;
        height: 100%;
    }
    .food-card:hover {
        transform: translateY(-5px);
    }
    .food-image {
        height: 200px;
        object-fit: cover;
    }
    .form-check-input:checked {
        background-color: #8B4513;
        border-color: #8B4513;
    }
    .food-options-form {
        font-size: 0.9rem;
    }
    .quantity-input::-webkit-inner-spin-button,
    .quantity-input::-webkit-outer-spin-button {
        opacity: 1;
    }
    .option-section {
        background-color: rgba(255, 255, 255, 0.5);
        padding: 10px;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .form-check-label {
        font-size: 0.85rem;
    }
    .card-body {
        padding: 1.25rem;
    }
    .btn-brown {
        white-space: nowrap;
    }
    @media (max-width: 768px) {
        .food-card {
            margin-bottom: 1rem;
        }
        .card-body {
            padding: 1rem;
        }
        .form-check-label {
            font-size: 0.8rem;
        }
    }
</style>

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('.food-options-form');
        
        forms.forEach(form => {
            const basePrice = parseFloat(form.querySelector('.total-price').textContent.replace(/,/g, ''));
            const quantityInput = form.querySelector('.quantity-input');
            const totalPriceElement = form.querySelector('.total-price');
            const sizeSelect = form.querySelector('select[name="size"]');
            const checkboxes = form.querySelectorAll('input[type="checkbox"]');

            function updateTotalPrice() {
                let total = basePrice;
                
                // Add size price
                if (sizeSelect) {
                    const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
                    total += parseFloat(selectedOption.dataset.price || 0);
                }

                // Add checkbox prices
                checkboxes.forEach(checkbox => {
                    if (checkbox.checked) {
                        total += parseFloat(checkbox.dataset.price || 0);
                    }
                });

                // Multiply by quantity
                total *= parseInt(quantityInput.value || 1);

                // Update display with proper formatting
                totalPriceElement.textContent = total.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
            }

            // Add event listeners
            if (sizeSelect) sizeSelect.addEventListener('change', updateTotalPrice);
            checkboxes.forEach(checkbox => checkbox.addEventListener('change', updateTotalPrice));
            quantityInput.addEventListener('input', updateTotalPrice);
        });

        // Auto-dismiss success alert
        const successAlert = document.getElementById('success-alert');
        if (successAlert) {
            setTimeout(() => {
                successAlert.classList.remove('show');
            }, 3000);
        }
    });
</script>
@endsection