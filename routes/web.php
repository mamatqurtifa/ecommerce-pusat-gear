<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;

/*------------------------------------------ Frontend Routes ------------------------------------------*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Product Routes (nanti)
Route::prefix('products')->name('frontend.products.')->group(function () {
    // Route::get('/', [Frontend\ProductController::class, 'index'])->name('index');
    // Route::get('/category/{category:slug}', [Frontend\ProductController::class, 'category'])->name('category');
    // Route::get('/{product:slug}', [Frontend\ProductController::class, 'show'])->name('show');
});

/* Authentication Routes */
require __DIR__.'/auth.php';

/*------------------------------------------ Customer Routes ------------------------------------------*/

Route::middleware(['auth', 'verified'])->group(function () {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Cart Routes (nanti)
    Route::prefix('cart')->name('frontend.cart.')->group(function () {
        // Route::get('/', [Frontend\CartController::class, 'index'])->name('index');
        // Route::post('/add', [Frontend\CartController::class, 'add'])->name('add');
        // Route::put('/update/{cart}', [Frontend\CartController::class, 'update'])->name('update');
        // Route::delete('/remove/{cart}', [Frontend\CartController::class, 'remove'])->name('remove');
        // Route::delete('/clear', [Frontend\CartController::class, 'clear'])->name('clear');
    });
    
    // Checkout Routes (nanti)
    Route::prefix('checkout')->name('frontend.checkout.')->group(function () {
        // Route::get('/', [Frontend\CheckoutController::class, 'index'])->name('index');
        // Route::post('/process', [Frontend\CheckoutController::class, 'process'])->name('process');
    });
    
    // Order Routes (nanti)
    Route::prefix('orders')->name('frontend.orders.')->group(function () {
        // Route::get('/', [Frontend\OrderController::class, 'index'])->name('index');
        // Route::get('/{order}', [Frontend\OrderController::class, 'show'])->name('show');
        // Route::post('/{order}/cancel', [Frontend\OrderController::class, 'cancel'])->name('cancel');
    });
    
    // Payment Routes (nanti)
    Route::prefix('payment')->name('frontend.payment.')->group(function () {
        // Route::get('/success', [Frontend\PaymentController::class, 'success'])->name('success');
        // Route::get('/failure', [Frontend\PaymentController::class, 'failure'])->name('failure');
        // Route::post('/callback', [Frontend\PaymentController::class, 'callback'])->name('callback');
    });
});

/*------------------------------------------ Admin Routes ------------------------------------------*/

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Category Management
    Route::resource('categories', CategoryController::class);
    
    // Product Management
    Route::resource('products', ProductController::class);
    Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');

    // User Management
    Route::resource('users', UserController::class);
    Route::post('/users/{user}/verify-email', [UserController::class, 'verifyEmail'])->name('users.verify-email');

    // Order Management - Temporary placeholder route
    Route::resource('orders', OrderController::class);
    Route::post('/orders/bulk-action', [OrderController::class, 'bulkAction'])->name('orders.bulk-action');
    
    // Reports (nanti)
    // Route::prefix('reports')->name('reports.')->group(function () {
    //     Route::get('/sales', [Admin\ReportController::class, 'sales'])->name('sales');
    //     Route::get('/products', [Admin\ReportController::class, 'products'])->name('products');
    //     Route::get('/customers', [Admin\ReportController::class, 'customers'])->name('customers');
    // });
    
    // Settings (nanti)
    // Route::prefix('settings')->name('settings.')->group(function () {
    //     Route::get('/', [Admin\SettingController::class, 'index'])->name('index');
    //     Route::put('/', [Admin\SettingController::class, 'update'])->name('update');
    // });
});

/*------------------------------------------ Dashboard Redirect Route ------------------------------------------*/

Route::get('/dashboard', function () {
    $user = auth()->user();
    
    if ($user->hasAnyRole(['super-admin', 'admin', 'staff'])) {
        return redirect()->route('admin.dashboard');
    }
    
    return redirect()->route('home');
})->middleware(['auth', 'verified'])->name('dashboard');

/*------------------------------------------ API Routes untuk Payment Gateway ------------------------------------------*/

// Payment Webhook (tidak perlu auth)
// Route::post('/webhook/duitku', [Frontend\PaymentController::class, 'webhook'])->name('webhook.duitku');

/*------------------------------------------ Admin API Routes (buat AJAX calls) ------------------------------------------*/

Route::middleware(['auth', 'verified', 'admin'])->prefix('admin/api')->name('admin.api.')->group(function () {
    // Quick actions
    Route::post('/products/{product}/toggle-status', [ProductController::class, 'toggleStatus'])->name('products.toggle-status');
    Route::post('/products/{product}/toggle-featured', [ProductController::class, 'toggleFeatured'])->name('products.toggle-featured');
    Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
    
    // Dashboard stats
    Route::get('/stats', [DashboardController::class, 'stats'])->name('stats');
});