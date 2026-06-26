<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TransactionController extends Controller
{
    // 1. Menampilkan halaman form checkout (Detail harga & upload bukti transfer)
    public function checkout($product_id)
    {
        $product = Product::findOrFail($product_id);
        return view('buyer.checkout', compact('product'));
    }

    // 2. Memproses data checkout langsung beserta upload bukti transfer manual
    public function storeTransaction(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);

        // Validasi input file gambar bukti transfer
        $request->validate([
            'bukti_transfer' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        // Proses upload file gambar bukti transfer
        $nama_file_bukti = null;
        if ($request->hasFile('bukti_transfer')) {
            $file = $request->file('bukti_transfer');
            $nama_file_bukti = 'bukti_' . time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('storage/bukti_transfer'), $nama_file_bukti);
        }

        // Gunakan DB Transaction agar jika salah satu simpan gagal, database otomatis rollback (aman)
        DB::transaction(function () use ($product, $nama_file_bukti) {
            // 1. Simpan ke tabel orders utama
            $order = Order::create([
                'user_id' => Auth::id(), // ID Buyer yang membeli
                'total_harga' => $product->harga,
                'status' => 'menunggu_verifikasi', // Langsung menunggu verifikasi seller karena bukti langsung dikirim
                'bukti_transfer' => $nama_file_bukti,
            ]);

            // 2. Simpan ke tabel rincian order_details
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'jumlah' => 1, // Sistem jual beli barang bekas biasanya 1 unit per item
                'harga_saat_beli' => $product->harga,
            ]);
        });

        return redirect()->route('orders.history')->with('success', 'Bukti transfer berhasil dikirim! Menunggu verifikasi dari penjual.');
    }

    // 3. Menampilkan riwayat pembelian barang dari sisi Buyer
    public function history()
    {
        // Hubungkan ke orderDetails dan produk agar bisa ditampilkan di riwayat pembelian buyer
        $orders = Order::where('user_id', Auth::id())->with('orderDetails.product')->latest()->get();
        return view('buyer.history', compact('orders'));
    }

    // 4. Menampilkan daftar pesanan masuk dari sisi Seller
    public function sellerOrders()
    {
        // Mengambil order details yang produknya milik seller yang sedang login
        $orderDetails = OrderDetail::whereHas('product', function ($query) {
            $query->where('user_id', Auth::id());
        })->with(['product', 'order.user'])->latest()->get();

        return view('seller.orders.index', compact('orderDetails'));
    }

    // 5. Buyer konfirmasi bahwa barang telah diterima (hanya jika status sudah 'lunas')
    public function confirmReceipt(Request $request, $order_id)
    {
        $order = Order::with('orderDetails')->findOrFail($order_id);

        // Pastikan order milik buyer yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak sah.');
        }

        // Hanya boleh konfirmasi jika status sudah 'lunas'
        if ($order->status !== 'lunas') {
            return redirect()->route('orders.history')->with('error', 'Order belum bisa dikonfirmasi. Status belum lunas.');
        }

        try {
            Log::info('confirmReceipt called', ['order_id' => $order->id, 'user_id' => Auth::id(), 'status_before' => $order->status]);

            DB::transaction(function () use ($order) {
                $order->update(['status' => 'selesai']);
            });

            Log::info('confirmReceipt success', ['order_id' => $order->id, 'user_id' => Auth::id()]);

            return redirect()->route('orders.history')->with('success', 'Terima kasih! Konfirmasi penerimaan berhasil.');
        } catch (\Exception $e) {
            Log::error('confirmReceipt error', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return redirect()->route('orders.history')->with('error', $e->getMessage());
        }
    }
}
