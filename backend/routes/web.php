<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', fn() => view('index'))->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/services', fn() => view('services'))->name('services');

// Auth pages
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::get('/register', fn() => view('auth.register'))->name('register')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', fn() => view('admin.dashboard'))->name('dashboard');

    Route::get('/products', fn() => view('admin.dashboard'))->name('products.index');
    Route::get('/products/create', fn() => view('admin.dashboard'))->name('products.create');
    Route::get('/categories', fn() => view('admin.dashboard'))->name('categories.index');
    Route::get('/inventory', fn() => view('admin.dashboard'))->name('inventory.index');
    Route::get('/orders', fn() => view('admin.dashboard'))->name('orders.index');
    Route::get('/purchases', fn() => view('admin.dashboard'))->name('purchases.index');
    Route::get('/attributes', fn() => view('admin.dashboard'))->name('attributes.index');
    Route::get('/invoices', fn() => view('admin.dashboard'))->name('invoices.index');
    Route::get('/settings', fn() => view('admin.dashboard'))->name('settings');
});

// Customer routes
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', fn() => view('customer.dashboard'))->name('dashboard');
});
