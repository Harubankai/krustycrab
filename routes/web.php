<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

// ----------------------------------------
// Single-page login/register/forgot-password
// ----------------------------------------
Route::get('/', [UserController::class, 'showLoginForm'])->name('hexavers');
Route::post('/login', [UserController::class, 'login'])->name('login.post');
Route::post('/register', [UserController::class, 'register'])->name('register.post');
Route::post('/forgotpass', [UserController::class, 'forgotpassPost'])->name('forgotpass.post');

// ----------------------------------------
// Customer profile routes
// ----------------------------------------
Route::get('/customer/profile', [UserController::class, 'customerProfile'])->name('customer.profile');
Route::post('/customer/profile', [UserController::class, 'updateCustomerProfile'])->name('customer.profile.update');
Route::post('/customer/profile/password', [UserController::class, 'updateCustomerPassword'])->name('customer.profile.password');

// ----------------------------------------
// Rider profile routes
// ----------------------------------------
Route::get('/rider/profile', [UserController::class, 'riderProfile'])->name('rider.profile');
Route::post('/rider/profile', [UserController::class, 'updateRiderProfile'])->name('rider.profile.update');
Route::post('/rider/profile/password', [UserController::class, 'updateRiderPassword'])->name('rider.profile.password');

// ----------------------------------------
// Logout
// ----------------------------------------
Route::get('/logout', [UserController::class, 'logout'])->name('logout');

// ----------------------------------------
// Admin dashboard & rider management
// ----------------------------------------
Route::get('/admin', [UserController::class, 'adminDashboard'])->name('admin.dashboard');
Route::view('/admin/modify-menu', 'admin.modify-menu')->name('admin.modify-menu');

// Manage Riders: load page, add rider, and list riders dynamically
Route::get('/admin/manage-riders', [UserController::class, 'manageRidersPage'])->name('admin.manage-riders');
Route::post('/admin/riders', [UserController::class, 'storeRider'])->name('admin.riders.store');
Route::get('/admin/riders/list', [UserController::class, 'listRiders'])->name('admin.riders.list');

// ----------------------------------------
// Instructor dashboard
// ----------------------------------------
Route::get('/instructor', [UserController::class, 'instructorDashboard'])->name('instructor.dashboard');

// ----------------------------------------
// Rider dashboards
// ----------------------------------------
Route::get('/rider', [UserController::class, 'riderDashboard'])->name('rider.dashboard');
Route::get('/rider/delivery', [UserController::class, 'riderDelivery'])->name('rider.delivery');

// ----------------------------------------
// Dashboard API (Real-time data)
// ----------------------------------------
Route::get('/total-riders', function () {
    return DB::table('user')
        ->where('role', 'rider')
        ->count();
});

// ----------------------------------------
// Customer dashboards & pages
// ----------------------------------------
Route::get('/customer', [UserController::class, 'customerDashboard'])->name('customer.dashboard');
Route::redirect('/customer/menu', '/customer')->name('customer.menu');
Route::view('/customer/cart', 'customer.cart')->name('customer.cart');
Route::view('/customer/orders', 'customer.orders')->name('customer.orders');
Route::redirect('/customer/my-orders', '/customer/orders')->name('customer.my-orders');
Route::post('/checkout', [OrderController::class, 'store'])->name('checkout');

// ----------------------------------------
// Order API for syncing across devices
// ----------------------------------------
Route::get('/api/orders/my-orders', [OrderController::class, 'myOrders'])->name('api.orders.my');
Route::get('/api/orders/available', [OrderController::class, 'availableOrders'])->name('api.orders.available');
Route::post('/api/orders/{id}/accept', [OrderController::class, 'acceptOrder'])->name('api.orders.accept');
Route::post('/api/orders/{id}/status', [OrderController::class, 'updateStatus'])->name('api.orders.status');
Route::get('/api/admin/statistics', [OrderController::class, 'adminStatistics'])->name('api.admin.stats');

// ----------------------------------------
// Menu API for persistent database syncing
// ----------------------------------------
Route::get('/api/menu', [\App\Http\Controllers\MenuItemController::class, 'index'])->name('api.menu.index');
Route::post('/api/menu', [\App\Http\Controllers\MenuItemController::class, 'store'])->name('api.menu.store');
Route::put('/api/menu/{id}', [\App\Http\Controllers\MenuItemController::class, 'update'])->name('api.menu.update');
Route::delete('/api/menu/{id}', [\App\Http\Controllers\MenuItemController::class, 'destroy'])->name('api.menu.destroy');

// ----------------------------------------
// Password reset routes
// ----------------------------------------
Route::get('/reset-password/{token}', [UserController::class, 'showResetForm'])->name('reset.password.form');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('reset.password.post');
