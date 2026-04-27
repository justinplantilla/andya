<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;

Route::get('/', function () {
    return view('index', [
        'featured_products' => \App\Models\Product::with(['category', 'inventory'])
            ->where('status', 'active')
            ->withSum('orderItems', 'quantity')
            ->orderByDesc('order_items_sum_quantity')
            ->take(8)->get(),
        'store_name'    => \App\Models\Setting::get('store_name', "Andaya's Native Products"),
        'store_email'   => \App\Models\Setting::get('store_email'),
        'store_phone'   => \App\Models\Setting::get('store_phone'),
        'store_address' => \App\Models\Setting::get('store_address'),
    ]);
})->name('home');
Route::get('/about', fn() => view('about'))->name('about');
Route::get('/services', fn() => view('services'))->name('services');

// Auth pages
Route::get('/login', fn() => view('auth.login'))->name('login')->middleware('guest');
Route::get('/register', fn() => view('auth.register'))->name('register')->middleware('guest');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/verify-email', [AuthController::class, 'showVerify'])->name('verify.show');
Route::post('/verify-email', [AuthController::class, 'verify'])->name('verify.submit');
Route::post('/verify-email/resend', [AuthController::class, 'resendCode'])->name('verify.resend');
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('forgot.password')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'sendResetCode'])->name('forgot.password.send')->middleware('guest');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset.show')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset')->middleware('guest');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout.get');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [\App\Http\Controllers\Admin\AdminController::class, 'products'])->name('products.index');
    Route::get('/products/create', [\App\Http\Controllers\Admin\AdminController::class, 'createProduct'])->name('products.create');
    Route::post('/products', [\App\Http\Controllers\Admin\AdminController::class, 'storeProduct'])->name('products.store');
    Route::get('/products/{product}/edit', [\App\Http\Controllers\Admin\AdminController::class, 'editProduct'])->name('products.edit');
    Route::put('/products/{product}', [\App\Http\Controllers\Admin\AdminController::class, 'updateProduct'])->name('products.update');
    Route::delete('/products/{product}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyProduct'])->name('products.destroy');
    Route::get('/categories', [\App\Http\Controllers\Admin\AdminController::class, 'categories'])->name('categories.index');
    Route::post('/categories', [\App\Http\Controllers\Admin\AdminController::class, 'storeCategory'])->name('categories.store');
    Route::put('/categories/{category}', [\App\Http\Controllers\Admin\AdminController::class, 'updateCategory'])->name('categories.update');
    Route::delete('/categories/{category}', [\App\Http\Controllers\Admin\AdminController::class, 'destroyCategory'])->name('categories.destroy');
    Route::get('/inventory', [\App\Http\Controllers\Admin\AdminController::class, 'inventory'])->name('inventory.index');
    Route::post('/inventory/{inventory}/adjust', [\App\Http\Controllers\Admin\AdminController::class, 'adjustInventory'])->name('inventory.adjust');
    Route::get('/orders', [\App\Http\Controllers\Admin\AdminController::class, 'orders'])->name('orders.index');
    Route::put('/orders/{order}/status', [\App\Http\Controllers\Admin\AdminController::class, 'updateOrderStatus'])->name('orders.status');
    Route::post('/orders/{order}/process-print', [\App\Http\Controllers\Admin\AdminController::class, 'processAndPrint'])->name('orders.processPrint');
    Route::get('/purchases', [\App\Http\Controllers\Admin\AdminController::class, 'purchases'])->name('purchases.index');
    Route::get('/purchases/create', [\App\Http\Controllers\Admin\AdminController::class, 'createPurchase'])->name('purchases.create');
    Route::post('/purchases', [\App\Http\Controllers\Admin\AdminController::class, 'storePurchase'])->name('purchases.store');
    Route::put('/purchases/{purchase}/status', [\App\Http\Controllers\Admin\AdminController::class, 'updatePurchaseStatus'])->name('purchases.status');
    Route::get('/customers', [\App\Http\Controllers\Admin\AdminController::class, 'customers'])->name('customers.index');
    Route::get('/invoices', [\App\Http\Controllers\Admin\AdminController::class, 'invoices'])->name('invoices.index');
    Route::put('/invoices/{invoice}/mark-paid', [\App\Http\Controllers\Admin\AdminController::class, 'markInvoicePaid'])->name('invoices.markPaid');
    Route::get('/invoices/{invoice}/receipt', [\App\Http\Controllers\Admin\AdminController::class, 'printReceipt'])->name('invoices.receipt');
    Route::get('/settings', [\App\Http\Controllers\Admin\AdminController::class, 'settings'])->name('settings');
    Route::get('/reports', [\App\Http\Controllers\Admin\AdminController::class, 'reports'])->name('reports');
    Route::post('/settings', [\App\Http\Controllers\Admin\AdminController::class, 'saveSettings'])->name('settings.save');
    Route::put('/settings/password', [\App\Http\Controllers\Admin\AdminController::class, 'updatePassword'])->name('profile.password');
    Route::post('/notifications/read', [\App\Http\Controllers\Admin\AdminController::class, 'markNotificationsRead'])->name('notifications.read');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Admin\AdminController::class, 'markOneRead'])->name('notifications.readOne');
});

// Customer routes
Route::middleware(['auth', 'customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Customer\CustomerController::class, 'dashboard'])->name('dashboard');
    Route::get('/products', [\App\Http\Controllers\Customer\CustomerController::class, 'products'])->name('products');
    Route::get('/cart', [\App\Http\Controllers\Customer\CustomerController::class, 'cart'])->name('cart');
    Route::post('/cart/add', [\App\Http\Controllers\Customer\CustomerController::class, 'addToCart'])->name('cart.add');
    Route::post('/cart/buy-now', [\App\Http\Controllers\Customer\CustomerController::class, 'buyNow'])->name('buy.now');
    Route::post('/cart/update', [\App\Http\Controllers\Customer\CustomerController::class, 'updateCart'])->name('cart.update');
    Route::post('/cart/remove', [\App\Http\Controllers\Customer\CustomerController::class, 'removeFromCart'])->name('cart.remove');
    Route::get('/orders', [\App\Http\Controllers\Customer\CustomerController::class, 'orders'])->name('orders');
    Route::post('/orders', [\App\Http\Controllers\Customer\CustomerController::class, 'storeOrder'])->name('orders.store');
    Route::post('/orders/direct', [\App\Http\Controllers\Customer\CustomerController::class, 'directOrder'])->name('orders.direct');
    Route::get('/orders/{id}', [\App\Http\Controllers\Customer\CustomerController::class, 'showOrder'])->name('orders.show');
    Route::put('/orders/{id}/cancel', [\App\Http\Controllers\Customer\CustomerController::class, 'cancelOrder'])->name('orders.cancel');
    Route::get('/orders/{id}/confirmation', [\App\Http\Controllers\Customer\CustomerController::class, 'showConfirmation'])->name('orders.confirmation');
    Route::get('/profile', [\App\Http\Controllers\Customer\CustomerController::class, 'profile'])->name('profile');
    Route::put('/profile', [\App\Http\Controllers\Customer\CustomerController::class, 'updateProfile'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\Customer\CustomerController::class, 'updatePassword'])->name('profile.password');
    Route::post('/notifications/read', [\App\Http\Controllers\Customer\CustomerController::class, 'markNotificationsRead'])->name('notifications.read');
    Route::post('/notifications/{notification}/read', [\App\Http\Controllers\Customer\CustomerController::class, 'markOneNotificationRead'])->name('notifications.readOne');
});
