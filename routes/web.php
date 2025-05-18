<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\CartController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\FoodController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\RiderController;
use App\Http\Controllers\Rider\RiderDashboardController;
use App\Http\Controllers\Rider\ProfileController as RiderProfileController;
use App\Http\Middleware\CheckRole;

/*
|--------------------------------------------------------------------------
| Root Route
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Guest Routes
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------|
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| Customer Routes
|--------------------------------------------------------------------------|
*/
Route::middleware(['auth', CheckRole::class . ':customer'])->group(function () {
    Route::get('/home', [CartController::class, 'index'])->name('home');
    Route::get('/cart', [CartController::class, 'viewCart'])->name('cart');
    Route::post('/cart/add/{foodId}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/update/{id}', [CartController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
    Route::get('/order-history', [CartController::class, 'orders'])->name('order-history');
    Route::get('/order/{id}', [CartController::class, 'viewOrder'])->name('order.view');
    Route::post('/order/{id}/cancel', [CartController::class, 'cancelOrder'])->name('order.cancel');
    Route::get('/order/{id}/status', [CartController::class, 'getOrderStatus'])->name('order.status');
    Route::get('/profile', [CustomerProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [CustomerProfileController::class, 'edit'])->name('profile.edit');
    Route::post('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
    Route::get('/profile/change-password', [CustomerProfileController::class, 'showChangePasswordForm'])->name('profile.change-password');
    Route::post('/profile/change-password', [CustomerProfileController::class, 'changePassword'])->name('profile.update-password');
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------|
*/
Route::middleware(['auth', CheckRole::class . ':admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/user_management', [AdminController::class, 'userManagement'])->name('admin.user_management');
    Route::get('/users/create', [AdminController::class, 'create'])->name('users.create');
    Route::post('/users', [AdminController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [AdminController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [AdminController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [AdminController::class, 'destroy'])->name('users.destroy');
    Route::get('/users/{user}/view', [AdminController::class, 'view'])->name('users.view');
    Route::prefix('riders')->group(function () {
        Route::get('/', [RiderController::class, 'index'])->name('admin.riders.index');
        Route::get('/create', [RiderController::class, 'create'])->name('admin.riders.create');
        Route::post('/', [RiderController::class, 'store'])->name('admin.riders.store');
        Route::get('/{rider}/edit', [RiderController::class, 'edit'])->name('admin.riders.edit');
        Route::put('/{rider}', [RiderController::class, 'update'])->name('admin.riders.update');
        Route::delete('/{rider}', [RiderController::class, 'destroy'])->name('admin.riders.destroy');
    });
    Route::get('/order-categories', [AdminController::class, 'OrderCategories'])->name('admin.order_categories');
    Route::post('/foods', [FoodController::class, 'store'])->name('foods.store');
    Route::get('/foods/{food}/edit', [FoodController::class, 'edit'])->name('foods.edit');
    Route::put('/foods/{food}', [FoodController::class, 'update'])->name('foods.update');
    Route::delete('/foods/{food}', [FoodController::class, 'destroy'])->name('foods.destroy');
    Route::get('/order-menu', [OrderController::class, 'index'])->name('admin.order_menu');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('admin.orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('admin.orders.store');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('admin.orders.cancel');
    Route::post('/orders/{order}/complete', [OrderController::class, 'complete'])->name('admin.orders.complete');
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('admin.orders.update_status');
    Route::post('/orders/{order}/start-delivery', [OrderController::class, 'startDelivery'])->name('admin.orders.start_delivery');
    Route::post('/orders/{order}/complete-delivery', [OrderController::class, 'completeDelivery'])->name('admin.orders.complete_delivery');
    Route::get('/set-delivery-fee', [OrderController::class, 'setDeliveryFee'])->name('admin.set_delivery_fee');
    Route::post('/set-delivery-fee', [OrderController::class, 'updateDeliveryFee'])->name('admin.update_delivery_fee');
    Route::get('/sales-report', [SalesController::class, 'index'])->name('admin.sales_report.index');
    Route::post('/sales-report/filter', [SalesController::class, 'filter'])->name('admin.sales_report.filter');
});

/*
|--------------------------------------------------------------------------
| Rider Routes
|--------------------------------------------------------------------------|
*/
Route::prefix('rider')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [RiderDashboardController::class, 'index'])->name('rider.index');
    Route::get('/orders', [RiderDashboardController::class, 'orders'])->name('rider.orders');
    Route::get('/my-deliveries', [RiderDashboardController::class, 'myDeliveries'])->name('rider.my-deliveries');
    Route::get('/earnings', [RiderDashboardController::class, 'earnings'])->name('rider.earnings');
    Route::get('/profile', [RiderProfileController::class, 'show'])->name('rider.profile');
    Route::get('/profile/edit', [RiderProfileController::class, 'edit'])->name('rider.profile.edit');
    Route::post('/profile', [RiderProfileController::class, 'update'])->name('rider.profile.update');
    Route::get('/profile/change-password', [RiderProfileController::class, 'showChangePasswordForm'])->name('rider.profile.change-password');
    Route::post('/profile/change-password', [RiderProfileController::class, 'changePassword'])->name('rider.profile.update-password');
    Route::post('/start-delivery/{order}', [RiderDashboardController::class, 'startDelivery'])->name('rider.startDelivery');
    Route::post('/finish-delivery/{order}', [RiderDashboardController::class, 'finishDelivery'])->name('rider.finishDelivery');
});

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------|
*/
Route::get('/about', function () {
    return view('about');
})->name('about');