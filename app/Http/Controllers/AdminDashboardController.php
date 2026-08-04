<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class AdminDashboardController extends Controller
{
    // Menampilkan halaman utama dashboard admin
    public function index()
    {
        return view('admin.dashboard');
    }

    // Menampilkan halaman daftar seller (menunggu persetujuan & semua seller)
    public function sellers()
    {
        $pendingSellers = \App\Models\User::where('role', 'seller')->where('seller_status', 'pending')->latest()->get();
        $allSellers = \App\Models\User::where('role', 'seller')->latest()->get();
        return view('admin.sellers', compact('pendingSellers', 'allSellers'));
    }

    // Menampilkan halaman daftar pesanan (termasuk komplain) dan penarikan dana
    public function orders()
    {
        $orders = Order::with('user')->latest()->get();
        $complaintOrders = Order::with(['user', 'orderDetails.product'])
            ->whereIn('status', ['komplain', 'refund_disetujui', 'barang_diretur'])
            ->latest()
            ->get();
        $pendingWithdrawals = \App\Models\Withdrawal::with('user')->where('status', 'pending')->latest()->get();

        return view('admin.orders', compact('orders', 'complaintOrders', 'pendingWithdrawals'));
    }
}