<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // Menampilkan daftar semua order di dashboard admin
    public function index()
    {
        $orders = Order::with('user')->get();
        return view('admin.dashboard', compact('orders'));
    }

    // Menampilkan detail order spesifik
    public function show($id)
    {
        $order = Order::with(['user', 'orderDetails.product'])->findOrFail($id);
        return view('admin.detail', compact('order'));
    }

    // Aksi untuk verifikasi (approve/reject)
    public function verify(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:lunas,dibatalkan',
        ]);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order, $request) {
                if ($request->status === 'lunas') {
                    $order->update(['status' => 'lunas']);

                    // Kurangi stok produk yang dibeli
                    foreach ($order->orderDetails as $detail) {
                        $product = $detail->product;
                        if ($product->stok >= $detail->jumlah) {
                            $product->decrement('stok', $detail->jumlah);
                        } else {
                            throw new \Exception("Stok untuk produk '{$product->nama_produk}' tidak mencukupi.");
                        }
                    }
                } else {
                    $order->update(['status' => 'dibatalkan']);
                }
            });

            return redirect('/admin/dashboard')->with('success', 'Status pesanan berhasil diupdate!');
        } catch (\Exception $e) {
            return redirect('/admin/dashboard')->with('error', $e->getMessage());
        }
    }

    // Aksi untuk tandai barang sudah dikirim
    public function sendPackage(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        // Hanya bisa kirim barang jika status sudah 'lunas'
        if ($order->status !== 'lunas') {
            return redirect("/admin/order/{$id}")->with('error', 'Barang hanya bisa dikirim jika status pesanan sudah "Lunas".');
        }

        try {
            $order->update([
                'barang_dikirim' => true,
                'tanggal_dikirim' => now(),
            ]);

            return redirect("/admin/order/{$id}")->with('success', 'Barang berhasil ditandai sebagai telah dikirim! Buyer akan mendapatkan notifikasi.');
        } catch (\Exception $e) {
            return redirect("/admin/order/{$id}")->with('error', $e->getMessage());
        }
    }
}