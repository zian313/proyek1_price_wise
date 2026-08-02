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
        $totalEarnings = Auth::user()->saldo; // Ambil saldo dari user database
        
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
    Route::get('/orders/{order_id}/receipt', [TransactionController::class, 'receipt'])
    ->name('orders.receipt');
    Route::get('/orders/{order_id}/download', [TransactionController::class, 'downloadReceipt'])
    ->name('orders.download');
    Route::get('/seller/orders', [TransactionController::class, 'sellerOrders'])->name('seller.orders');
    // Seller: tandai barang sudah dikirim
    Route::post('/seller/orders/{order_id}/send-package', [TransactionController::class, 'sellerSendPackage'])->name('seller.orders.sendPackage');
    // Buyer: konfirmasi bahwa barang telah diterima setelah status 'lunas'
    Route::post('/orders/{order_id}/confirm-receipt', [TransactionController::class, 'confirmReceipt'])->name('orders.confirmReceipt');
    Route::post('/orders/{order_id}/complaint', [TransactionController::class, 'submitComplaint'])->name('orders.complaint');

    // Buyer: Submit resi retur & rekening refund (setelah admin approve refund)
    Route::post('/orders/{id}/submit-retur', [TransactionController::class, 'submitReturInfo'])->name('orders.submitRetur');
    // Seller: Konfirmasi barang retur sudah diterima
    Route::post('/seller/orders/{id}/confirm-retur', [TransactionController::class, 'sellerConfirmRetur'])->name('seller.orders.confirmRetur');
    // Buyer: Konfirmasi dana refund sudah diterima / belum
    Route::post('/orders/{id}/konfirmasi-refund', [TransactionController::class, 'buyerKonfirmasiRefund'])->name('orders.konfirmasiRefund');
});

// ADMIN ROUTES
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/order/{id}', [AdminController::class, 'show'])->name('admin.order.detail');
    Route::post('/admin/order/{id}/verify', [AdminController::class, 'verify'])->name('admin.order.verify');
    Route::post('/admin/order/{id}/send-package', [AdminController::class, 'sendPackage'])->name('admin.order.sendPackage');
    Route::post('/admin/seller/{id}/approve', [AdminController::class, 'approveSeller'])->name('admin.seller.approve');
    Route::post('/admin/seller/{id}/reject', [AdminController::class, 'rejectSeller'])->name('admin.seller.reject');
    Route::post('/admin/order/{id}/mark-arrived', [AdminController::class, 'markAsArrived'])->name('admin.order.markArrived');
    Route::post('/admin/order/{id}/update-resi', [AdminController::class, 'updateResi'])->name('admin.order.updateResi');
    Route::post('/admin/order/{id}/auto-confirm', [AdminController::class, 'autoConfirm'])->name('admin.order.autoConfirm');
    Route::post('/admin/order/{id}/approve-refund', [AdminController::class, 'approveRefund'])->name('admin.order.approveRefund');
    Route::post('/admin/order/{id}/finalize-refund', [AdminController::class, 'finalizeRefundTransfer'])->name('admin.order.finalizeRefund');
    Route::post('/admin/order/{id}/reject-refund', [AdminController::class, 'rejectRefund'])->name('admin.order.rejectRefund');
    Route::post('/admin/order/{id}/send-note', [AdminController::class, 'sendAdminNote'])->name('admin.order.sendNote');
    Route::post('/admin/order/{id}/mark-investigation', [AdminController::class, 'markAsInvestigation'])->name('admin.order.markInvestigation');
    Route::post('/admin/order/{id}/resolve-investigation', [AdminController::class, 'resolveInvestigation'])->name('admin.order.resolveInvestigation');

});

require __DIR__.'/auth.php';