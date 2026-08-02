<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    // 1. Menampilkan halaman form checkout (Detail harga & upload bukti transfer)
    public function checkout($product_id)
    {
        $product = Product::findOrFail($product_id);
        return view('buyer.checkout', compact('product'));
    }

    // 2. Memproses data checkout langsung (nama, alamat, email, ekspedisi, metode pembayaran)
    public function storeTransaction(Request $request, $product_id)
    {
        $product = Product::findOrFail($product_id);

        // Validasi input data checkout
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'alamat' => 'required|string',
            'ekspedisi' => 'required|string|max:255',
            'metode_pembayaran' => 'required|string|max:255',
        ]);

        $order = null;

        // Gunakan DB Transaction agar jika salah satu simpan gagal, database otomatis rollback (aman)
        DB::transaction(function () use ($product, $request, &$order) {
            // 1. Simpan ke tabel orders utama
            $order = Order::create([
                'user_id' => Auth::id(), // ID Buyer yang membeli
                'total_harga' => $product->harga,
                'status' => 'menunggu_pembayaran', // Status awal: menunggu pembayaran
                'nama' => $request->nama,
                'email' => $request->email,
                'alamat' => $request->alamat,
                'ekspedisi' => $request->ekspedisi,
                'metode_pembayaran' => $request->metode_pembayaran,
            ]);

            // 2. Simpan ke tabel rincian order_details
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'jumlah' => 1, // Sistem jual beli barang bekas biasanya 1 unit per item
                'harga_saat_beli' => $product->harga,
            ]);
        });

        return redirect()->route('orders.payment', $order->id)->with('success', 'Checkout berhasil! Silakan lakukan pembayaran.');
    }

    // 2b. Menampilkan halaman pembayaran dengan instruksi transfer bank & upload bukti
    public function payment($order_id)
    {
        $order = Order::with('orderDetails.product')->findOrFail($order_id);

        // Pastikan order milik buyer yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak sah.');
        }

        // Jika status order bukan 'menunggu_pembayaran', alihkan ke riwayat
        if ($order->status !== 'menunggu_pembayaran') {
            return redirect()->route('orders.history')->with('error', 'Status pesanan ini tidak memerlukan pembayaran.');
        }

        // Ambil detail produk pertama untuk ditampilkan
        $detail = $order->orderDetails->first();
        $product = $detail ? $detail->product : null;

        return view('buyer.payment', compact('order', 'product'));
    }

    // 2c. Memproses upload bukti transfer pembayaran
    public function pay(Request $request, $order_id)
    {
        $order = Order::findOrFail($order_id);

        // Pastikan order milik buyer yang sedang login
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Aksi tidak sah.');
        }

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

        // Update status order dan bukti transfer
        $order->update([
            'status' => 'menunggu_verifikasi',
            'bukti_transfer' => $nama_file_bukti,
        ]);

        return redirect()->route('orders.history')->with('success', 'Bukti transfer berhasil dikirim! Menunggu verifikasi dari admin.');
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
        $order = Order::with('orderDetails.product')->findOrFail($order_id);

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
                // Update order status menjadi 'selesai'
                $order->update(['status' => 'selesai']);

                // Tambahkan saldo ke seller berdasarkan order details
                foreach ($order->orderDetails as $detail) {
                    $seller = $detail->product->user; // Ambil seller dari product
                    $totalAmount = $detail->jumlah * $detail->harga_saat_beli;
                    
                    // Update saldo seller
                    $seller->increment('saldo', $totalAmount);
                    
                    Log::info('Saldo updated for seller', [
                        'seller_id' => $seller->id,
                        'seller_name' => $seller->name,
                        'amount_added' => $totalAmount,
                        'new_saldo' => $seller->saldo + $totalAmount,
                        'order_id' => $order->id
                    ]);
                }
            });

            Log::info('confirmReceipt success', ['order_id' => $order->id, 'user_id' => Auth::id()]);

            return redirect()->route('orders.history')->with('success', 'Terima kasih! Konfirmasi penerimaan berhasil. Saldo seller telah diperbarui.');
        } catch (\Exception $e) {
            Log::error('confirmReceipt error', ['order_id' => $order->id, 'error' => $e->getMessage()]);
            return redirect()->route('orders.history')->with('error', $e->getMessage());
        }
    }
    // 6. Menampilkan halaman cetak struk
public function receipt($order_id)
{
    $order = Order::with([
        'user',
        'orderDetails.product'
    ])->findOrFail($order_id);

    // Pastikan hanya pemilik transaksi yang bisa melihat
    if ($order->user_id !== Auth::id()) {
        abort(403, 'Aksi tidak sah.');
    }

    // Hanya transaksi selesai yang boleh dicetak
    if ($order->status !== 'selesai') {
        return redirect()->route('orders.history')
            ->with('error', 'Struk hanya dapat dicetak setelah transaksi selesai.');
    }

    return view('buyer.receipt', compact('order'));
}
public function downloadReceipt($order_id)
{
    $order = Order::with([
        'user',
        'orderDetails.product.user'
    ])->findOrFail($order_id);

    // Pastikan hanya pemilik transaksi yang bisa download
    if ($order->user_id != Auth::id()) {
        abort(403);
    }

    // Hanya transaksi selesai yang boleh didownload
    if ($order->status != 'selesai') {
        return redirect()->route('orders.history')
            ->with('error', 'Struk hanya dapat didownload setelah transaksi selesai.');
    }

    // Enable remote assets (images) and HTML5 parser for DomPDF so images load correctly
    Pdf::setOptions([
        'isRemoteEnabled' => true,
        'isHtml5ParserEnabled' => true,
    ]);

    // Pass a flag to the view so the template can hide interactive buttons when rendering PDF
    $pdf = Pdf::loadView('buyer.receipt', ['order' => $order, 'for_pdf' => true])
        ->setPaper('a4', 'portrait');

    return $pdf->download('Struk-PriceWise-' . str_pad($order->id, 5, '0', STR_PAD_LEFT) . '.pdf');
}

// Seller: Tandai barang sudah dikirim (wajib isi No Resi)
public function sellerSendPackage(Request $request, $order_id)
{
    $order = Order::with('orderDetails.product')->findOrFail($order_id);

    // Pastikan order milik seller yang sedang login (cek apakah seller memiliki product di order ini)
    $sellerHasProduct = false;
    foreach ($order->orderDetails as $detail) {
        if ($detail->product && $detail->product->user_id === Auth::id()) {
            $sellerHasProduct = true;
            break;
        }
    }

    if (!$sellerHasProduct) {
        return redirect()->route('seller.orders')->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
    }

    // Hanya bisa kirim barang jika status sudah 'lunas'
    if ($order->status !== 'lunas') {
        return redirect()->route('seller.orders')->with('error', 'Barang hanya bisa dikirim jika status pesanan sudah "Lunas".');
    }

    $request->validate([
        'no_resi' => 'required|string|max:255',
    ]);

    try {
        $order->update([
            'barang_dikirim' => true,
            'tanggal_dikirim' => now(),
            'no_resi' => $request->no_resi,
        ]);

        return redirect()->route('seller.orders')->with('success', 'Barang berhasil ditandai sebagai telah dikirim dengan No. Resi: ' . $request->no_resi);
    } catch (\Exception $e) {
        return redirect()->route('seller.orders')->with('error', $e->getMessage());
    }
}

// Buyer: Ajukan komplain / refund (Wajib Upload Video Unboxing)
public function submitComplaint(Request $request, $order_id)
{
    $order = Order::findOrFail($order_id);

    if ($order->user_id !== Auth::id()) {
        abort(403, 'Aksi tidak sah.');
    }

    // Komplain bisa diajukan jika status lunas/dikirim ATAU barang sudah dikirim
    if (!in_array($order->status, ['lunas', 'dikirim']) && !$order->barang_dikirim) {
        return redirect()->route('orders.history')->with('error', 'Komplain hanya dapat diajukan untuk barang yang telah dikirim.');
    }

    $request->validate([
        'alasan_komplain' => 'required|string|max:1000',
        'video_unboxing' => 'required|file|mimes:mp4,mov,avi,webm,mkv|max:204800', // Maksimal 200MB
    ]);

    $videoName = null;
    if ($request->hasFile('video_unboxing')) {
        $file = $request->file('video_unboxing');
        $videoName = time() . '_unboxing_' . uniqid() . '.' . $file->getClientOriginalExtension();
        
        // Pastikan folder ada, lalu simpan file
        $storagePath = storage_path('app/public/unboxing_videos');
        if (!file_exists($storagePath)) {
            mkdir($storagePath, 0755, true);
        }
        $file->move($storagePath, $videoName);
    }

    $order->update([
        'status' => 'komplain',
        'alasan_komplain' => $request->alasan_komplain,
        'video_unboxing' => $videoName,
    ]);

    if ($request->ajax() || $request->wantsJson() || $request->header('X-Requested-With') === 'XMLHttpRequest') {
        return response()->json(['success' => true, 'message' => 'Pengajuan refund berhasil dikirim!']);
    }

    return redirect()->route('orders.history')->with('success', 'Pengajuan refund/komplain berhasil dikirim beserta video unboxing! Admin akan meninjau pengajuan Anda.');
}

// Buyer: Submit Resi Retur & Rekening Bank Pengembalian Dana
public function submitReturInfo(Request $request, $id)
{
    // Cari order berdasarkan ID (Admin atau Buyer pemilik order)
    $order = Order::findOrFail($id);

    if (auth()->user()->role !== 'admin' && $order->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
    }

    if (!in_array($order->status, ['komplain', 'refund_disetujui', 'barang_diretur'])) {
        return redirect()->back()->with('error', 'Status pesanan saat ini tidak memungkinkan untuk memasukkan info retur.');
    }

    $request->validate([
        'bank_refund'     => 'required|string|max:50',
        'norek_refund'    => 'required|string|max:50',
        'namarek_refund'  => 'required|string|max:100',
        'ekspedisi_retur' => 'required|string|max:50',
        'no_resi_retur'   => 'required|string|max:100',
    ], [
        'bank_refund.required'     => 'Nama Bank / E-Wallet wajib diisi.',
        'norek_refund.required'    => 'Nomor Rekening / No HP E-Wallet wajib diisi.',
        'namarek_refund.required'  => 'Nama Pemilik Rekening wajib diisi.',
        'ekspedisi_retur.required' => 'Jenis Ekspedisi Retur wajib dipilih / diisi.',
        'no_resi_retur.required'   => 'Nomor Resi Retur pengembalian barang wajib diisi.',
    ]);

    // Update status ke barang_diretur secara pasti!
    $order->update([
        'status'           => 'barang_diretur',
        'bank_refund'      => $request->bank_refund,
        'norek_refund'     => $request->norek_refund,
        'namarek_refund'   => $request->namarek_refund,
        'ekspedisi_retur'  => $request->ekspedisi_retur,
        'no_resi_retur'    => $request->no_resi_retur,
        'tanggal_retur'    => $order->tanggal_retur ?? now(),
    ]);

    return redirect()->back()->with('success', 'Informasi resi retur & nomor rekening berhasil disimpan! Penjual & Admin akan memantau kedatangan barang retur.');
}

// Seller: Konfirmasi Barang Retur Diterima di Alamat Toko/Rumah Seller
public function sellerConfirmRetur($id)
{
    $order = Order::findOrFail($id);

    // Pastikan order memiliki detail produk milik seller yang login
    $sellerOwnsProduct = $order->orderDetails()->whereHas('product', function($q) {
        $q->where('user_id', auth()->id());
    })->exists();

    if (!$sellerOwnsProduct && auth()->user()->role !== 'admin') {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk konfirmasi retur order ini.');
    }

    $order->update([
        'retur_diterima_seller' => true,
    ]);

    return redirect()->back()->with('success', 'Barang retur berhasil dikonfirmasi telah sampai! Admin akan mentransfer dana refund ke Buyer.');
}

// Buyer: Konfirmasi dana refund sudah diterima dari Admin
public function buyerKonfirmasiRefund(Request $request, $id)
{
    $order = Order::findOrFail($id);

    if ($order->user_id !== auth()->id()) {
        return redirect()->back()->with('error', 'Anda tidak memiliki akses ke pesanan ini.');
    }

    if ($order->status !== 'menunggu_konfirmasi_refund') {
        return redirect()->back()->with('error', 'Status pesanan tidak memerlukan konfirmasi refund.');
    }

    $sudahMasuk = $request->input('konfirmasi') === 'sudah';

    if ($sudahMasuk) {
        $order->update([
            'status'                   => 'dibatalkan',
            'refund_dikonfirmasi_buyer' => true,
        ]);
        return redirect()->route('orders.history')->with('success', '✅ Refund selesai! Konfirmasi berhasil. Terima kasih telah berbelanja di Price Wise.');
    } else {
        // Buyer bilang belum masuk — kirim notif ke admin tapi tetap di halaman
        return redirect()->back()->with('error', '⚠️ Dana belum masuk? Mohon tunggu 1×24 jam atau hubungi Admin Price Wise untuk klarifikasi lebih lanjut.');
    }
}

}
