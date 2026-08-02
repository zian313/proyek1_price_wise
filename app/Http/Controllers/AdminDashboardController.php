<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Semua order untuk tabel utama
        $orders = Order::with('user')->latest()->get();
        // Seller yang pending persetujuan
        $pendingSellers = \App\Models\User::where('role', 'seller')->where('seller_status', 'pending')->latest()->get();
        // Semua seller
        $allSellers = \App\Models\User::where('role', 'seller')->latest()->get();
        // Order yang sedang dalam status komplain / refund / barang diretur — PRIORITAS ADMIN
        $complaintOrders = Order::with(['user', 'orderDetails.product'])
            ->whereIn('status', ['komplain', 'refund_disetujui', 'barang_diretur'])
            ->latest()
            ->get();
        // Permintaan Penarikan Dana Seller (Withdrawal)
        $pendingWithdrawals = \App\Models\Withdrawal::with('user')->where('status', 'pending')->latest()->get();

        return view('admin.dashboard', compact('orders', 'pendingSellers', 'allSellers', 'complaintOrders', 'pendingWithdrawals'));
    }
}