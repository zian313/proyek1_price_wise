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

    // Setujui pendaftaran seller
    public function approveSeller($id)
    {
        $seller = \App\Models\User::where('role', 'seller')->findOrFail($id);
        $seller->update(['seller_status' => 'approved']);

        return redirect()->route('admin.dashboard')->with('success', "Akun seller {$seller->name} telah berhasil disetujui!");
    }

    // Tolak pendaftaran seller
    public function rejectSeller($id)
    {
        $seller = \App\Models\User::where('role', 'seller')->findOrFail($id);
        $seller->update(['seller_status' => 'rejected']);

        return redirect()->route('admin.dashboard')->with('error', "Akun seller {$seller->name} telah ditolak.");
    }

    // Admin: Tandai barang telah sampai di alamat (Mulai Timer 24 Jam)
    public function markAsArrived($id)
    {
        $order = Order::findOrFail($id);
        $order->update([
            'waktu_sampai' => now(),
        ]);

        return redirect()->back()->with('success', 'Barang berhasil ditandai telah sampai di alamat! Timer konfirmasi 24 jam untuk buyer resmi dimulai.');
    }

    // Admin: Update No Resi dari Admin
    public function updateResi(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $request->validate([
            'no_resi' => 'required|string|max:255',
        ]);

        $order->update([
            'no_resi' => $request->no_resi,
            'barang_dikirim' => true,
            'tanggal_dikirim' => $order->tanggal_dikirim ?? now(),
        ]);

        return redirect()->back()->with('success', 'Nomor resi berhasil diperbarui.');
    }

    // Admin / System: Konfirmasi Otomatis setelah 24 Jam atau Approve Komplain
    public function autoConfirm($id)
    {
        $order = Order::with('orderDetails.product.user')->findOrFail($id);

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                $order->update(['status' => 'selesai']);

                foreach ($order->orderDetails as $detail) {
                    $seller = $detail->product->user;
                    $totalAmount = $detail->jumlah * $detail->harga_saat_beli;
                    $seller->increment('saldo', $totalAmount);
                }
            });

            return redirect()->back()->with('success', 'Transaksi berhasil diselesaikan secara otomatis! Saldo seller telah diteruskan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Admin: Setujui Awal Refund Buyer (Tahap 1 ACC: Buyer wajib kirim resi retur & norek)
    public function approveRefund($id)
    {
        $order = Order::findOrFail($id);

        if (!in_array($order->status, ['komplain', 'investigasi'])) {
            return redirect()->back()->with('error', 'Aksi ini hanya berlaku untuk order dengan status komplain atau investigasi.');
        }

        $order->update([
            'status' => 'refund_disetujui',
            'admin_note' => 'Pengajuan refund Anda disetujui Admin! Silakan kirimkan barang kembali ke seller & masukkan Nomor Resi Retur beserta Nomor Rekening pengembalian dana Anda.',
            'admin_note_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Refund buyer disetujui (Tahap 1). Buyer diminta memasukkan nomor resi pengembalian barang & nomor rekening.');
    }

    // Admin: Selesaikan Transfer Refund ke Rekening Buyer (Upload Bukti + Tunggu Konfirmasi Buyer)
    public function finalizeRefundTransfer(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if (!$order->norek_refund) {
            return redirect()->back()->with('error', 'Buyer belum memasukkan nomor rekening pengembalian dana.');
        }

        $request->validate([
            'bukti_transfer_refund' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'bukti_transfer_refund.required' => 'Foto / screenshot bukti transfer wajib diunggah.',
            'bukti_transfer_refund.image'    => 'File harus berupa gambar.',
            'bukti_transfer_refund.max'      => 'Ukuran gambar maksimal 5 MB.',
        ]);

        // Simpan bukti transfer refund
        $namaFile = null;
        if ($request->hasFile('bukti_transfer_refund')) {
            $file = $request->file('bukti_transfer_refund');
            $namaFile = 'refund_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $storagePath = public_path('storage/bukti_refund');
            if (!file_exists($storagePath)) {
                mkdir($storagePath, 0755, true);
            }
            $file->move($storagePath, $namaFile);
        }

        $order->update([
            'status'                 => 'menunggu_konfirmasi_refund',
            'bukti_transfer_refund'  => $namaFile,
            'admin_note'             => 'Dana refund sebesar Rp ' . number_format($order->total_harga, 0, ',', '.') . ' telah ditransfer Admin ke rekening Anda (' . $order->bank_refund . ' - ' . $order->norek_refund . ' a.n ' . $order->namarek_refund . '). Silakan konfirmasi apakah dana sudah masuk.',
            'admin_note_at'          => now(),
        ]);

        return redirect()->back()->with('success', 'Bukti transfer refund berhasil diunggah! Buyer diminta mengkonfirmasi penerimaan dana.');
    }

    // Admin: Tolak Pengajuan Refund Buyer (Komplain Ditolak — Transaksi Tetap Selesai, Saldo Diteruskan ke Seller)
    public function rejectRefund($id)
    {
        $order = Order::with('orderDetails.product.user')->findOrFail($id);

        if ($order->status !== 'komplain') {
            return redirect()->back()->with('error', 'Aksi ini hanya berlaku untuk order dengan status komplain.');
        }

        try {
            \Illuminate\Support\Facades\DB::transaction(function () use ($order) {
                $order->update(['status' => 'selesai']);

                foreach ($order->orderDetails as $detail) {
                    if ($detail->product && $detail->product->user) {
                        $seller = $detail->product->user;
                        $totalAmount = $detail->jumlah * $detail->harga_saat_beli;
                        $seller->increment('saldo', $totalAmount);
                    }
                }
            });

            return redirect()->back()->with('success', 'Pengajuan refund buyer ditolak. Transaksi diselesaikan dan saldo diteruskan ke seller.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    // Admin: Kirim Notifikasi / Pesan ke Buyer (Info Keterlambatan, Estimasi Tiba, dll)
    public function sendAdminNote(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'admin_note'    => 'required|string|max:1000',
            'estimasi_tiba' => 'nullable|date|after_or_equal:today',
        ], [
            'admin_note.required'    => 'Pesan untuk buyer wajib diisi.',
            'estimasi_tiba.after_or_equal' => 'Estimasi tiba tidak boleh di masa lalu.',
        ]);

        $order->update([
            'status'        => $order->status === 'dikirim' ? 'keterlambatan' : $order->status,
            'admin_note'    => $request->admin_note,
            'estimasi_tiba' => $request->estimasi_tiba ?: null,
            'admin_note_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Notifikasi keterlambatan/informasi berhasil dikirim ke buyer!');
    }

    // Admin: Tandai Barang Hilang / Masuk Status Investigasi
    public function markAsInvestigation($id)
    {
        $order = Order::findOrFail($id);

        $order->update([
            'status' => 'investigasi',
            'admin_note' => 'Paket sedang dalam proses investigasi oleh Admin & Ekspedisi karena terindikasi hilang/kendala fatal.',
            'admin_note_at' => now(),
        ]);

        return redirect()->back()->with('warning', 'Status transaksi diubah menjadi INVESTIGASI. Silakan koordinasi dengan kurir & ekspedisi.');
    }

    // Admin: Selesaikan Hasil Investigasi Barang Hilang (Kirim Kembali ATAU Refund Buyer)
    public function resolveInvestigation(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $request->validate([
            'hasil_investigasi' => 'required|in:ditemukan,hilang',
        ]);

        if ($request->hasil_investigasi === 'ditemukan') {
            $order->update([
                'status' => 'dikirim',
                'admin_note' => 'Hasil Investigasi: Barang telah ditemukan oleh pihak ekspedisi dan sedang dikirim kembali ke alamat tujuan.',
                'admin_note_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Investigasi selesai: Barang ditemukan dan status dikembalikan ke Shipping (Dikirim).');
        } else {
            // Barang tidak ditemukan -> Refund dana ke buyer (Batalkan order, seller tidak dapat saldo)
            $order->update([
                'status' => 'dibatalkan',
                'admin_note' => 'Hasil Investigasi: Barang dinyatakan HILANG oleh ekspedisi. Dana transaksi akan dikembalikan (Refund) sepenuhnya ke Buyer.',
                'admin_note_at' => now(),
            ]);
            return redirect()->back()->with('success', 'Investigasi selesai: Barang tidak ditemukan. Order dibatalkan dan refund diproses untuk buyer.');
        }
    }
}