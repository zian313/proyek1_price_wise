<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminDashboardController;

// Rute dasar
Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('welcome');
});

// Dashboard User/Seller
Route::get('/dashboard', function () {
    $products = Product::where('stok', '>', 0)->with(['category', 'user'])->latest()->get();
    
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    if (Auth::user()->role === 'seller') {
        $totalProducts = \App\Models\Product::where('user_id', Auth::id())->count();
        $totalEarnings = \App\Models\OrderDetail::whereHas('product', function ($q) {
            $q->where('user_id', Auth::id());
        })->whereHas('order', function ($q) {
            $q->where('status', 'lunas');
        })->sum(DB::raw('jumlah * harga_saat_beli'));
        
        $recentOrdersCount = \App\Models\OrderDetail::whereHas('product', function ($q) {
            $q->where('user_id', Auth::id());
        })->whereHas('order', function ($q) {
            $q->where('status', 'menunggu_verifikasi');
        })->count();
        
        return view('dashboard', compact('products', 'totalProducts', 'totalEarnings', 'recentOrdersCount'));
    }
    
    $categories = \App\Models\Category::all();
    return view('dashboard', compact('products', 'categories'));
})->middleware('auth')->name('dashboard');

// Auth & Transaksi Umum
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    
    Route::get('/checkout/{product_id}', [TransactionController::class, 'checkout'])->name('checkout');
    Route::post('/checkout/store/{product_id}', [TransactionController::class, 'storeTransaction'])->name('checkout.store');
    Route::get('/orders/{order_id}/payment', [TransactionController::class, 'payment'])->name('orders.payment');
    Route::post('/orders/{order_id}/pay', [TransactionController::class, 'pay'])->name('orders.pay');
    Route::get('/orders/history', [TransactionController::class, 'history'])->name('orders.history');
    Route::get('/seller/orders', [TransactionController::class, 'sellerOrders'])->name('seller.orders');
    // Buyer: konfirmasi bahwa barang telah diterima setelah status 'lunas'
    Route::post('/orders/{order_id}/confirm-receipt', [TransactionController::class, 'confirmReceipt'])->name('orders.confirmReceipt');
});

// ADMIN ROUTES
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/order/{id}', [AdminController::class, 'show'])->name('admin.order.detail');
    Route::post('/admin/order/{id}/verify', [AdminController::class, 'verify'])->name('admin.order.verify');
});

require __DIR__.'/auth.php';