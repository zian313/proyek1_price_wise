<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Detail Pesanan #' . $order->id) }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <!-- Alert Error jika ada -->
            @if(session('error'))
                <div
                    class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/30 border-l-4 border-rose-500 rounded-xl text-rose-800 dark:text-rose-300 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div
                    class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Rincian Transaksi
                            #PW-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Periksa informasi pembeli, produk, dan verifikasi bukti
                            transfer manual.</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}"
                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-semibold flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>

                <div class="p-6 space-y-8">
                    <!-- Grid Info Pesanan & Pembeli -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Informasi Penerima &
                                Pengiriman</h4>
                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-xl p-4 space-y-3">
                                <div>
                                    <span class="text-xs text-gray-400 block">Nama Penerima</span>
                                    <span
                                        class="font-bold text-gray-800 dark:text-white">{{ $order->nama ?? $order->user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Alamat Email</span>
                                    <span
                                        class="text-sm text-gray-600 dark:text-gray-300">{{ $order->email ?? $order->user->email }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Ekspedisi</span>
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 bg-indigo-500/10 text-indigo-400 text-xs font-bold rounded border border-indigo-500/20 mt-0.5">{{ $order->ekspedisi ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Alamat Lengkap</span>
                                    <span
                                        class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed">{{ $order->alamat ?? '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Informasi Transaksi
                            </h4>
                            <div
                                class="bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-xl p-4 space-y-3">
                                <div>
                                    <span class="text-xs text-gray-400 block">Tanggal Pembelian</span>
                                    <span
                                        class="text-sm text-gray-800 dark:text-white font-medium">{{ $order->created_at->format('d M Y, H:i') }}
                                        WIB</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Metode Pembayaran</span>
                                    <span
                                        class="text-sm text-gray-800 dark:text-white font-semibold">{{ $order->metode_pembayaran ?? '-' }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Status Saat Ini</span>
                                    <div class="mt-1">
                                        @if($order->status === 'menunggu_pembayaran')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                                                Menunggu Pembayaran
                                            </span>
                                        @elseif($order->status === 'menunggu_verifikasi')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($order->status === 'lunas')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                                Lunas / Selesai
                                            </span>
                                        @elseif($order->status === 'selesai')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300">
                                                Diterima oleh Buyer
                                            </span>
                                        @elseif($order->status === 'menunggu_konfirmasi_refund')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-pulse"></span>
                                                💸 Menunggu Konfirmasi Buyer
                                            </span>
                                        @elseif($order->status === 'dibatalkan')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400">
                                                Refund Selesai
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Detail Rincian Produk -->
                    <div class="space-y-4">
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Item yang Dibeli</h4>
                        <div class="border border-gray-100 dark:border-gray-800 rounded-xl overflow-x-auto">
                            <table class="w-full text-left border-collapse min-w-[600px]">
                                <thead>
                                    <tr
                                        class="bg-gray-50 dark:bg-gray-900/30 text-xs font-bold text-gray-400 uppercase border-b border-gray-100 dark:border-gray-800">
                                        <th class="px-4 py-3">Nama Produk</th>
                                        <th class="px-4 py-3">Penjual</th>
                                        <th class="px-4 py-3 text-right">Jumlah</th>
                                        <th class="px-4 py-3 text-right">Harga Satuan</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody
                                    class="divide-y divide-gray-100 dark:divide-gray-800 text-sm text-gray-700 dark:text-gray-300">
                                    @foreach($order->orderDetails as $detail)
                                        @php $product = $detail->product; @endphp
                                        <tr>
                                            <td class="px-4 py-3.5 font-bold text-gray-800 dark:text-white">
                                                {{ $product ? $product->nama_produk : 'Produk Dihapus' }}
                                            </td>
                                            <td class="px-4 py-3.5 text-xs text-gray-400">
                                                {{ $product && $product->user ? $product->user->name : '-' }}
                                            </td>
                                            <td class="px-4 py-3.5 text-right font-mono">{{ $detail->jumlah }}</td>
                                            <td class="px-4 py-3.5 text-right font-mono">Rp
                                                {{ number_format($detail->harga_saat_beli, 0, ',', '.') }}
                                            </td>
                                            <td
                                                class="px-4 py-3.5 text-right font-bold text-teal-600 dark:text-teal-400 font-mono">
                                                Rp
                                                {{ number_format($detail->jumlah * $detail->harga_saat_beli, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr
                                        class="bg-gray-50 dark:bg-gray-900/10 font-bold border-t border-gray-100 dark:border-gray-800 text-gray-800 dark:text-white">
                                        <td colspan="4"
                                            class="px-4 py-4 text-right text-gray-400 uppercase tracking-wider text-xs">
                                            Total Pembayaran</td>
                                        <td
                                            class="px-4 py-4 text-right text-lg text-emerald-600 dark:text-emerald-400 font-black font-mono">
                                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Informasi Rekening Seller (Untuk Transfer Dana) -->
                    <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Informasi Rekening Penjual
                            (Seller)</h4>
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-xl p-4">
                            <p class="text-xs text-gray-400 mb-3 leading-relaxed">
                                Setelah status pesanan <strong>SELESAI</strong> (pembeli telah menerima barang), gunakan
                                rekening di bawah ini untuk meneruskan dana ke Penjual.
                            </p>
                            @foreach($order->orderDetails as $detail)
                                @php 
                                                                                                                                                                                                                                                                                                                                    $product = $detail->product;
                                    $seller = $product ? $product->user : null;
                                    // Ambil detail bank dari produk, jika kosong ambil dari profile user/seller
                                    $bank_name = ($product && $product->bank_name) ? $product->bank_name : ($seller ? $seller->bank_name : null);
                                    $no_rekening = ($product && $product->no_rekening) ? $product->no_rekening : ($seller ? $seller->no_rekening : null);
                                    $atas_nama = ($product && $product->atas_nama) ? $product->atas_nama : ($seller ? $seller->atas_nama : ($seller ? $seller->name : null));
                                @endphp
                                <div
                                    class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 py-2 border-b border-gray-100 dark:border-gray-800 last:border-0">
                                    <div>
                                        <p class="text-sm font-bold text-gray-800 dark:text-white">
                                            {{ $product ? $product->nama_produk : 'Produk Dihapus' }}
                                        </p>
                                        <p class="text-xs text-gray-400">Penjual: {{ $seller ? $seller->name : '-' }}
                                            ({{ $seller ? $seller->email : '-' }})</p>
                                    </div>
                                    <div class="text-right">
                                        @if($bank_name && $no_rekening)
                                            <p class="text-sm font-bold text-indigo-600 dark:text-indigo-400">{{ $bank_name }}
                                            </p>
                                            <p class="text-base font-bold font-mono text-gray-800 dark:text-white">
                                                {{ $no_rekening }}
                                            </p>
                                            <p class="text-xs text-gray-400">a.n. {{ $atas_nama }}</p>
                                        @else
                                            <p class="text-xs text-rose-500 italic font-bold">Penjual belum melengkapi data
                                                rekening bank.</p>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <!-- Bukti Transfer & Aksi Verifikasi -->
                    <div
                        class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <!-- Bukti Transfer Gambar -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Bukti Transfer Bank
                            </h4>
                            @if($order->bukti_transfer)
                                <div
                                    class="p-2 border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/30 rounded-2xl flex items-center justify-center overflow-hidden max-w-xs shadow-sm">
                                    <a href="{{ asset('storage/bukti_transfer/' . $order->bukti_transfer) }}"
                                        target="_blank" title="Klik untuk memperbesar">
                                        <img src="{{ asset('storage/bukti_transfer/' . $order->bukti_transfer) }}"
                                            class="max-w-full h-auto rounded-xl hover:scale-105 transition-transform duration-200 shadow-md border border-gray-200/20"
                                            alt="Bukti Transfer">
                                    </a>
                                </div>
                            @else
                                <div
                                    class="p-6 border border-dashed border-rose-300 dark:border-rose-900/50 bg-rose-50/10 rounded-xl text-center">
                                    <p class="text-rose-500 text-xs italic font-bold">Buyer belum mengunggah bukti transfer.
                                    </p>
                                </div>
                            @endif
                        </div>

                        <!-- Form Verifikasi -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Aksi Verifikasi Admin
                            </h4>
                            @if($order->status === 'menunggu_verifikasi')
                                <div
                                    class="bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800 rounded-2xl p-6 space-y-4">
                                    <p class="text-xs text-gray-400 leading-relaxed">
                                        Periksa kembali apakah jumlah dana pada mutasi rekening bank Rekber Anda sudah
                                        sesuai dengan nominal total pembayaran di atas sebelum menyetujui transaksi.
                                    </p>

                                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                        <!-- Form Approve -->
                                        <form action="{{ route('admin.order.verify', $order->id) }}" method="POST"
                                            class="flex-1">
                                            @csrf
                                            <input type="hidden" name="status" value="lunas">
                                            <button type="submit"
                                                class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition duration-150 shadow-md shadow-emerald-600/10">
                                                ✅ Setujui
                                            </button>
                                        </form>

                                        <!-- Form Reject -->
                                        <form action="{{ route('admin.order.verify', $order->id) }}" method="POST"
                                            class="flex-1" id="form-reject-order-{{ $order->id }}">
                                            @csrf
                                            <input type="hidden" name="status" value="dibatalkan">
                                            <input type="hidden" name="admin_note" id="note-order-{{ $order->id }}"
                                                value="">
                                            <button type="button" onclick="confirmRejectOrder({{ $order->id }})"
                                                class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition duration-150 shadow-md shadow-rose-600/10">
                                                ❌ Batalkan Transaksi
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div
                                    class="bg-gray-50 dark:bg-gray-900/20 border border-gray-100 dark:border-gray-800 rounded-2xl p-6 flex flex-col items-center justify-center text-center">
                                    <span class="text-2xl mb-2">🔒</span>
                                    <p class="text-xs text-gray-400 font-medium">Transaksi ini telah diproses dan status
                                        telah terkunci.</p>
                                    <span class="text-sm font-bold text-gray-300 mt-1">Status:
                                        {{ strtoupper($order->status) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- SECTION: NO RESI & WAKTU PENGIRIMAN -->
                    <div class="space-y-4 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Nomor Resi & Timer
                            Pengiriman</h4>
                        <div
                            class="bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-xl p-5 space-y-4">
                            <div class="flex flex-col gap-2">
                                <div>
                                    <span class="text-xs text-gray-400 block">Nomor Resi Pengiriman (Khusus Diinput
                                        Seller)</span>
                                    <span
                                        class="text-base font-bold font-mono text-teal-400">{{ $order->no_resi ?? 'Belum ada resi pengiriman' }}</span>
                                    @if($order->bukti_resi)
                                        <a href="{{ asset('storage/bukti_resi/' . $order->bukti_resi) }}" target="_blank" class="text-xs font-bold text-teal-400 hover:underline block mt-1">📸 Lihat Bukti Foto Resi Pengiriman</a>
                                    @endif
                                </div>
                            </div>

                            <!-- CONTROL TIMER 24 JAM ADMIN & MONITORING PENGIRIMAN -->
                            <div class="pt-4 border-t border-gray-200 dark:border-gray-800 space-y-4">
                                <div
                                    class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                                    <div>
                                        <span class="text-xs font-bold text-gray-300 block">Status Monitoring Pengiriman
                                            Admin</span>
                                        @if($order->status === 'investigasi')
                                            <p class="text-xs text-rose-400 font-bold mt-0.5 animate-pulse">
                                                🚨 Status: Dalam Investigasi (Terindikasi Barang Hilang/Kendala Ekspedisi)
                                            </p>
                                        @elseif($order->status === 'keterlambatan')
                                            <p class="text-xs text-amber-400 font-bold mt-0.5">
                                                ⏳ Status: Mengalami Keterlambatan Transit (Notifikasi Terkirim ke Buyer)
                                            </p>
                                        @elseif($order->waktu_sampai)
                                            @php
                                                $deadline = \Carbon\Carbon::parse($order->waktu_sampai)->addHours(24);
                                                $isExpired = now()->greaterThanOrEqualTo($deadline);
                                            @endphp
                                            <p class="text-xs text-amber-400 font-medium mt-0.5">
                                                Waktu Sampai:
                                                {{ \Carbon\Carbon::parse($order->waktu_sampai)->format('d M Y, H:i') }} WIB
                                                @if(!$isExpired)
                                                    (Timer Aktif hingga {{ $deadline->format('H:i, d M') }})
                                                @else
                                                    (Timer 24 Jam Telah Berakhir)
                                                @endif
                                            </p>
                                        @else
                                            <p class="text-xs text-gray-400 mt-0.5">Pantau resi kurir. Tekan tombol sesuai
                                                status terupdate dari ekspedisi.</p>
                                        @endif
                                    </div>

                                    <div class="flex flex-wrap items-center gap-2">
                                        @if($order->status === 'dikirim' || $order->status === 'keterlambatan')
                                            <form action="{{ route('admin.order.markInvestigation', $order->id) }}" method="POST"
                                                method="POST"
                                                onsubmit="confirmSubmit(event, 'Tandai order ini sebagai BARANG HILANG / INVESTIGASI?', 'Investigasi', 'warning')">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-2 bg-rose-900/80 hover:bg-rose-800 text-rose-200 border border-rose-700 rounded-xl text-xs font-bold transition">
                                                    🔍 Tandai Barang Hilang (Investigasi)
                                                </button>
                                            </form>
                                        @endif

                                        @if(!$order->waktu_sampai && ($order->barang_dikirim || $order->status === 'dikirim' || $order->status === 'keterlambatan'))
                                            <form action="{{ route('admin.order.markArrived', $order->id) }}" method="POST"
                                                onsubmit="confirmSubmit(event, 'Mulai timer 24 jam konfirmasi untuk buyer?', 'Mulai Timer', 'info', '#f59e0b')">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3.5 py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-xl text-xs font-bold transition">
                                                    ⏱️ Tandai Delivered (Mulai Timer 24 Jam)
                                                </button>
                                            </form>
                                        @endif

                                        @if(!in_array($order->status, ['selesai', 'dibatalkan']))
                                            <form action="{{ route('admin.order.autoConfirm', $order->id) }}" method="POST"
                                                onsubmit="confirmSubmit(event, 'Selesaikan transaksi dan teruskan saldo ke seller?', 'Selesaikan & Teruskan', 'success', '#10b981')">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3.5 py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-bold transition">
                                                    ⚡ Konfirmasi Otomatis & Teruskan Saldo
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>

                                {{-- FORM PENYELESAIAN INVESTIGASI (Jika status = investigasi) --}}
                                @if($order->status === 'investigasi')
                                    <div class="bg-rose-950/60 border border-rose-500/40 rounded-xl p-4 space-y-3">
                                        <h5 class="text-xs font-black text-rose-300 uppercase tracking-wider">Hasil
                                            Verifikasi & Investigasi Ekspedisi:</h5>
                                        <p class="text-xs text-gray-300 leading-relaxed">
                                            Admin mengecek bukti resi & verifikasi dengan pihak ekspedisi. Apakah barang
                                            ditemukan atau dipastikan hilang?
                                        </p>
                                        <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                            <form action="{{ route('admin.order.resolveInvestigation', $order->id) }}" method="POST"
                                                method="POST" class="flex-1"
                                                onsubmit="confirmSubmit(event, 'Barang ditemukan? Ekspedisi akan mengirim ulang ke Buyer.', 'Kirim ke Buyer', 'success', '#10b981')">
                                                @csrf
                                                <input type="hidden" name="hasil_investigasi" value="ditemukan">
                                                <button type="submit"
                                                    class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold rounded-lg transition">
                                                    ✅ Ya, Barang Ditemukan (Kirim Kembali oleh Ekspedisi)
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.order.resolveInvestigation', $order->id) }}" method="POST"
                                                method="POST" class="flex-1"
                                                onsubmit="confirmSubmit(event, 'Barang pasti hilang? Dana akan dikembalikan (Refund) ke Buyer dan pesanan dibatalkan.', 'Refund Buyer', 'error', '#be123c')">
                                                @csrf
                                                <input type="hidden" name="hasil_investigasi" value="hilang">
                                                <button type="submit"
                                                    class="w-full py-2 bg-rose-700 hover:bg-rose-600 text-white text-xs font-bold rounded-lg transition">
                                                    ❌ Tidak, Barang Hilang (Refund Buyer & Batalkan Order)
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- SECTION: PENINJAUAN VIDEO UNBOXING & REFUND -->
                    @if($order->status === 'komplain' || $order->alasan_komplain)
                        <div class="space-y-4 pt-6 border-t border-rose-500/30">
                            <h4 class="text-sm font-bold text-rose-400 uppercase tracking-wider">⚠️ Peninjauan Pengajuan
                                Refund / Komplain Buyer</h4>
                            <div class="bg-rose-500/10 border border-rose-500/30 rounded-2xl p-5 space-y-4">
                                <div>
                                    <span class="text-xs text-rose-300 font-bold block">Alasan Refund dari Buyer:</span>
                                    <p
                                        class="text-sm text-gray-200 mt-1 bg-slate-950 p-3 rounded-xl border border-slate-800">
                                        {{ $order->alasan_komplain }}
                                    </p>
                                </div>

                                <div>
                                    <span class="text-xs text-rose-300 font-bold block mb-2">Bukti Video Unboxing
                                        (Wajib):</span>
                                    @if($order->video_unboxing)
                                        <div
                                            class="max-w-md rounded-xl overflow-hidden border border-slate-800 bg-slate-950 p-2">
                                            <video controls class="w-full rounded-lg bg-black"
                                                style="max-height: 400px; object-fit: contain;">
                                                <source src="{{ asset('storage/unboxing_videos/' . $order->video_unboxing) }}">
                                                Browser Anda tidak mendukung pemutar video.
                                            </video>
                                            <a href="{{ asset('storage/unboxing_videos/' . $order->video_unboxing) }}"
                                                target="_blank"
                                                class="text-xs text-indigo-400 hover:underline block mt-2 text-center">
                                                📥 Download / Buka Video di Tab Baru
                                            </a>
                                        </div>
                                    @else
                                        <p class="text-xs text-rose-400 italic">Buyer tidak melampirkan video unboxing.</p>
                                    @endif
                                    {{-- KARTU DATA RETUR & REKENING BUYER (SELALU MUNCUL JIKA SUDAH DIISI BUYER) --}}
                                    @if($order->no_resi_retur || $order->norek_refund)
                                        <div
                                            class="bg-indigo-950/90 border-2 border-indigo-500/60 rounded-2xl p-5 space-y-3 my-4 shadow-xl">
                                            <div class="flex items-center justify-between">
                                                <h5
                                                    class="text-xs font-black text-indigo-300 uppercase tracking-wider flex items-center gap-2">
                                                    📦 Data Resi Pengembalian & Rekening Refund Buyer:
                                                </h5>
                                                <span
                                                    class="px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">
                                                    TERISI OLEH BUYER ✓
                                                </span>
                                            </div>

                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                                <div class="bg-slate-900 p-3 rounded-xl border border-indigo-500/30">
                                                    <span class="text-gray-400 block text-[10px] uppercase font-bold mb-1">Resi
                                                        Retur Pengembalian Barang:</span>
                                                    @if($order->no_resi_retur)
                                                        <div class="flex items-center gap-2">
                                                            <span
                                                                class="text-xs px-2 py-0.5 bg-indigo-500/30 text-indigo-200 font-bold rounded border border-indigo-500/40">
                                                                {{ $order->ekspedisi_retur ?? 'Ekspedisi' }}
                                                            </span>
                                                            <span
                                                                class="font-mono font-bold text-teal-300 text-sm bg-slate-950 px-2 py-0.5 rounded border border-slate-800">
                                                                {{ $order->no_resi_retur }}
                                                            </span>
                                                        </div>
                                                        @if($order->bukti_resi_retur)
                                                            <a href="{{ asset('storage/bukti_resi/' . $order->bukti_resi_retur) }}" target="_blank" class="text-[10px] text-teal-400 hover:underline font-bold block mt-1">📸 Lihat Bukti Foto Resi Retur</a>
                                                        @endif
                                                        <span class="text-[10px] text-gray-400 block mt-1">Tanggal Retur:
                                                            {{ $order->tanggal_retur ? $order->tanggal_retur->diffForHumans() : '-' }}</span>
                                                    @else
                                                        <span class="text-amber-400 italic font-semibold">Buyer Belum Menginput Resi
                                                            Retur</span>
                                                    @endif
                                                </div>

                                                <div class="bg-slate-900 p-3 rounded-xl border border-indigo-500/30">
                                                    <span
                                                        class="text-gray-400 block text-[10px] uppercase font-bold mb-1">Rekening
                                                        Refund Buyer (Tujuan Transfer):</span>
                                                    @if($order->norek_refund)
                                                        <span class="font-bold text-white block text-sm">{{ $order->bank_refund }} -
                                                            <strong
                                                                class="text-amber-300 font-mono">{{ $order->norek_refund }}</strong></span>
                                                        <span class="text-xs text-indigo-300 font-semibold block mt-0.5">a.n
                                                            {{ $order->namarek_refund }}</span>
                                                    @else
                                                        <span class="text-amber-400 italic font-semibold">Buyer Belum Menginput
                                                            Rekening</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    @if($order->status === 'komplain')
                                        <div class="pt-4 border-t border-rose-500/20 space-y-4">
                                            {{-- INFORMASI PENGEMBALIAN DANA (REFUND) --}}
                                            <div class="bg-amber-500/10 border border-amber-500/30 rounded-xl p-4 space-y-2">
                                                <h5
                                                    class="text-xs font-black text-amber-300 uppercase tracking-wider flex items-center gap-1.5">
                                                    💳 Informasi Pengembalian Dana (Refund Buyer):
                                                </h5>
                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs text-gray-300">
                                                    <div>
                                                        <span class="text-gray-400">Total Nominal Refund:</span>
                                                        <span class="font-black text-teal-300 block text-sm">Rp
                                                            {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                                                    </div>
                                                    <div>
                                                        <span class="text-gray-400">Penerima Refund (Buyer):</span>
                                                        <span
                                                            class="font-bold text-white block">{{ $order->nama ?? $order->user->name }}
                                                            ({{ $order->email ?? $order->user->email }})</span>
                                                    </div>
                                                </div>
                                            </div>

                                            <p class="text-xs text-gray-400 leading-relaxed">
                                                Tinjau alasan dan video unboxing dari buyer. Jika disetujui, buyer akan diminta
                                                mengembalikan barang ke seller & menginput nomor rekening refund.
                                            </p>
                                            <div class="flex flex-col sm:flex-row gap-3">
                                                <form action="{{ route('admin.order.approveRefund', $order->id) }}" method="POST"
                                                    onsubmit="confirmSubmit(event, 'Setujui komplain buyer? Buyer akan diminta memasukkan No. Resi Retur & Rekening Refund.', 'Setuju Refund', 'success', '#10b981')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full py-2.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition duration-150 shadow-md">
                                                        ✅ Setujui Refund (Minta Buyer Kirim Barang & No. Rekening)
                                                    </button>
                                                </form>
                                                <form action="{{ route('admin.order.rejectRefund', $order->id) }}" method="POST"
                                                    onsubmit="confirmSubmit(event, 'Tolak refund? Transaksi akan diselesaikan dan saldo diteruskan ke seller.', 'Tolak Refund', 'error', '#e11d48')">
                                                    @csrf
                                                    <button type="submit"
                                                        class="w-full py-2.5 bg-rose-700 hover:bg-rose-800 text-white rounded-xl text-xs font-black uppercase tracking-wider transition duration-150 shadow-md">
                                                        ❌ Tolak Refund (Selesaikan Transaksi)
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    @elseif(in_array($order->status, ['refund_disetujui', 'barang_diretur', 'investigasi_retur']))
                                        {{-- TAHAP RETUR & TRANSFER REFUND OLEH ADMIN --}}
                                        <div class="pt-4 border-t border-indigo-500/30 space-y-4">
                                            <div class="bg-indigo-950/80 border border-indigo-500/40 rounded-2xl p-5 space-y-3">
                                                <h5
                                                    class="text-xs font-black text-indigo-300 uppercase tracking-wider flex items-center gap-1.5">
                                                    📦 Status Pengembalian Barang & Rekening Buyer:
                                                </h5>

                                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                                                    <div class="bg-slate-900 p-3 rounded-xl border border-slate-800">
                                                        <span
                                                            class="text-gray-400 block text-[10px] uppercase font-bold mb-0.5">Resi
                                                            Retur Pengembalian Barang:</span>
                                                        @if($order->no_resi_retur)
                                                            <span class="inline-flex items-center gap-2 mt-0.5">
                                                                <span
                                                                    class="text-xs px-2 py-0.5 bg-indigo-500/20 text-indigo-300 font-bold rounded border border-indigo-500/30">
                                                                    {{ $order->ekspedisi_retur ?? 'Ekspedisi' }}
                                                                </span>
                                                                <span
                                                                    class="font-mono font-bold text-teal-300 text-sm">{{ $order->no_resi_retur }}</span>
                                                            </span>
                                                            @if($order->bukti_resi_retur)
                                                                <a href="{{ asset('storage/bukti_resi/' . $order->bukti_resi_retur) }}" target="_blank" class="text-[10px] text-teal-400 hover:underline font-bold block mt-1">📸 Lihat Bukti Foto Resi Retur</a>
                                                            @endif
                                                            <span class="text-[10px] text-gray-400 block mt-1">Dikirim
                                                                {{ $order->tanggal_retur ? $order->tanggal_retur->diffForHumans() : '' }}</span>
                                                        @else
                                                            <span class="text-amber-400 italic font-semibold">Buyer Belum Menginput
                                                                Resi Retur</span>
                                                        @endif
                                                    </div>

                                                    <div class="bg-slate-900 p-3 rounded-xl border border-slate-800">
                                                        <span
                                                            class="text-gray-400 block text-[10px] uppercase font-bold mb-0.5">Rekening
                                                            Refund Buyer:</span>
                                                        @if($order->norek_refund)
                                                            <span class="font-bold text-white block">{{ $order->bank_refund }} -
                                                                {{ $order->norek_refund }}</span>
                                                            <span class="text-xs text-indigo-300">a.n
                                                                {{ $order->namarek_refund }}</span>
                                                        @else
                                                            <span class="text-amber-400 italic font-semibold">Buyer Belum Menginput
                                                                Rekening</span>
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="pt-2">
                                                    @if($order->status === 'investigasi_retur')
                                                        <div class="bg-red-950/40 border border-red-500/50 p-4 rounded-xl mb-4">
                                                            <p class="text-sm text-red-400 font-black mb-2 flex items-center gap-2">
                                                                🚨 SELLER MENOLAK BARANG RETUR (DISPUTE)
                                                            </p>
                                                            <p class="text-xs text-gray-300 mb-2">
                                                                <span class="font-bold text-gray-400">Alasan Penolakan:</span><br>
                                                                {{ $order->seller_dispute_reason }}
                                                            </p>
                                                            @if($order->seller_dispute_video)
                                                                <a href="{{ asset('storage/dispute_videos/' . $order->seller_dispute_video) }}" target="_blank" class="inline-block mt-2 px-3 py-1.5 bg-red-600/80 hover:bg-red-500 text-white rounded text-xs font-bold transition">
                                                                    🎥 Tonton Video Unboxing Seller
                                                                </a>
                                                            @endif
                                                            <div class="mt-4 pt-3 border-t border-red-500/30 flex gap-2">
                                                                <form action="{{ route('admin.order.rejectRefund', $order->id) }}" method="POST" onsubmit="confirmSubmit(event, 'Tolak refund pembeli dan berikan dana kepada seller karena pembeli terbukti curang?', 'Ya, Tolak Refund', 'error', '#e11d48')" class="flex-1">
                                                                    @csrf
                                                                    <button type="submit" class="w-full py-2 bg-rose-700 hover:bg-rose-600 text-white rounded-lg text-[10px] font-black uppercase tracking-wider transition">
                                                                        ❌ Pembeli Curang (Beri Dana ke Seller)
                                                                    </button>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    @elseif($order->retur_diterima_seller)
                                                        <p class="text-xs text-emerald-400 font-bold mb-3 flex items-center gap-1">
                                                            ✓ Seller telah mengonfirmasi bahwa barang retur sudah sampai di
                                                            toko/rumahnya! Silakan transfer dana refund ke rekening buyer.
                                                        </p>
                                                    @else
                                                        <p class="text-xs text-amber-400 mb-3 leading-relaxed">
                                                            ⏳ Barang dalam perjalanan kembali ke Seller. Jika Seller sudah
                                                            mengonfirmasi terima barang (atau resi retur menunjukkan paket tiba),
                                                            Anda dapat memproses transfer refund di bawah.
                                                        </p>
                                                    @endif

                                                    @if($order->norek_refund)
                                                        {{-- FORM UPLOAD BUKTI TRANSFER REFUND --}}
                                                        <form action="{{ route('admin.order.finalizeRefund', $order->id) }}" method="POST" enctype="multipart/form-data"
                                                            onsubmit="confirmSubmit(event, 'Konfirmasi bahwa Anda sudah mentransfer Rp {{ number_format($order->total_harga, 0, ',', '.') }} ke rekening Buyer?', 'Ya, Sudah Transfer', 'success', '#10b981')">
                                                            @csrf
                                                            @if($errors->has('bukti_transfer_refund'))
                                                                <div
                                                                    class="mb-2 p-2 bg-rose-500/20 border border-rose-500/40 rounded-lg text-rose-300 text-xs">
                                                                    {{ $errors->first('bukti_transfer_refund') }}
                                                                </div>
                                                            @endif
                                                            <div class="space-y-2">
                                                                <label
                                                                    class="block text-[10px] font-bold text-gray-400 uppercase tracking-wider">📸
                                                                    Upload Screenshot / Foto Bukti Transfer Refund (Wajib)</label>
                                                                <input type="file" name="bukti_transfer_refund" accept="image/*"
                                                                    required
                                                                    class="block w-full text-xs text-slate-400 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-emerald-950 file:text-emerald-300 hover:file:bg-emerald-900 border border-slate-700 rounded-lg cursor-pointer bg-slate-950 p-1.5" />
                                                                <p class="text-[10px] text-gray-500">Format: JPG/PNG/WEBP, maks. 5
                                                                    MB</p>
                                                            </div>
                                                            <button type="submit"
                                                                class="w-full py-3 bg-emerald-600 hover:bg-emerald-500 text-white rounded-xl text-xs font-black uppercase tracking-wider transition shadow-lg shadow-emerald-600/20">
                                                                💸 Konfirmasi Transfer & Upload Bukti — Rp
                                                                {{ number_format($order->total_harga, 0, ',', '.') }}
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @elseif($order->status === 'menunggu_konfirmasi_refund')
                                        {{-- STATUS: SUDAH TRANSFER, MENUNGGU KONFIRMASI BUYER --}}
                                        <div class="pt-4 border-t border-cyan-500/20">
                                            <div class="bg-cyan-950/60 border border-cyan-500/40 rounded-2xl p-5 space-y-3">
                                                <h5
                                                    class="text-xs font-black text-cyan-300 uppercase tracking-wider flex items-center gap-1.5">
                                                    <span class="w-2 h-2 bg-cyan-400 rounded-full animate-pulse"></span>
                                                    💸 Bukti Transfer Terkirim — Menunggu Konfirmasi Buyer
                                                </h5>
                                                @if($order->bukti_transfer_refund)
                                                    <div class="p-2 border border-cyan-500/30 bg-slate-950 rounded-xl max-w-xs">
                                                        <p class="text-[10px] text-gray-400 mb-1.5 font-bold uppercase">Screenshot
                                                            Bukti Transfer Admin:</p>
                                                        <a href="{{ asset('storage/bukti_refund/' . $order->bukti_transfer_refund) }}"
                                                            target="_blank">
                                                            <img src="{{ asset('storage/bukti_refund/' . $order->bukti_transfer_refund) }}"
                                                                class="w-full rounded-lg hover:opacity-80 transition border border-slate-700"
                                                                alt="Bukti Transfer Refund">
                                                        </a>
                                                        <a href="{{ asset('storage/bukti_refund/' . $order->bukti_transfer_refund) }}"
                                                            target="_blank"
                                                            class="text-[10px] text-cyan-400 hover:underline block text-center mt-1">Buka
                                                            di tab baru</a>
                                                    </div>
                                                @endif
                                                <p class="text-xs text-gray-300 leading-relaxed">
                                                    Admin telah mengklaim sudah mentransfer dana refund sebesar <strong
                                                        class="text-teal-300">Rp
                                                        {{ number_format($order->total_harga, 0, ',', '.') }}</strong> ke
                                                    rekening Buyer ({{ $order->bank_refund }} - {{ $order->norek_refund }} a.n
                                                    {{ $order->namarek_refund }}).
                                                    Menunggu Buyer mengkonfirmasi penerimaan dana.
                                                </p>
                                            </div>
                                        </div>
                                    @elseif($order->status === 'dibatalkan' && $order->alasan_komplain)
                                        <div class="pt-4 border-t border-rose-500/20">
                                            <div class="bg-rose-950/80 border border-rose-500/40 rounded-xl p-4 space-y-2">
                                                <p class="text-xs font-black text-rose-300 uppercase tracking-wider">
                                                    ✅ Refund Selesai 100% — Buyer Mengkonfirmasi Penerimaan Dana
                                                </p>
                                                <p class="text-xs text-gray-300 leading-relaxed">
                                                    Pengembalian dana sebesar <strong class="text-teal-300">Rp
                                                        {{ number_format($order->total_harga, 0, ',', '.') }}</strong> ke
                                                    rekening Buyer ({{ $order->bank_refund }} - {{ $order->norek_refund }} a.n
                                                    {{ $order->namarek_refund }}) telah dikonfirmasi diterima.
                                                </p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                    @endif

                        {{-- ================================================================
                        SEKSI NOTIFIKASI ADMIN → BUYER (Info Keterlambatan / Estimasi)
                        ================================================================= --}}
                        @if(!in_array($order->status, ['menunggu_pembayaran', 'dibatalkan', 'selesai']))
                            <div class="space-y-4 pt-6 border-t border-indigo-500/20">
                                <h4
                                    class="text-sm font-bold text-indigo-400 uppercase tracking-wider flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    📨 Kirim Notifikasi ke Buyer
                                </h4>

                                {{-- Preview pesan yang sudah dikirim --}}
                                @if($order->admin_note)
                                    <div
                                        class="bg-indigo-500/10 border border-indigo-500/30 rounded-2xl p-4 flex items-start gap-3">
                                        <div
                                            class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center shrink-0 mt-0.5">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-black text-indigo-300 uppercase tracking-wider mb-1">Pesan
                                                Terakhir Dikirim ke Buyer:</p>
                                            <p class="text-sm text-gray-200 leading-relaxed">{{ $order->admin_note }}</p>
                                            <div class="flex items-center gap-4 mt-2 flex-wrap">
                                                @if($order->estimasi_tiba)
                                                    <span
                                                        class="inline-flex items-center gap-1.5 text-xs font-bold text-teal-400 bg-teal-500/10 border border-teal-500/20 px-2.5 py-1 rounded-full">
                                                        📅 Estimasi Tiba:
                                                        {{ \Carbon\Carbon::parse($order->estimasi_tiba)->locale('id')->isoFormat('D MMMM YYYY') }}
                                                    </span>
                                                @endif
                                                @if($order->admin_note_at)
                                                    <span class="text-[11px] text-gray-500">Dikirim
                                                        {{ $order->admin_note_at->diffForHumans() }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                {{-- Form Kirim / Update Notifikasi --}}
                                <div class="bg-gray-50 dark:bg-gray-900/30 border border-indigo-500/20 rounded-2xl p-5">
                                    <p class="text-xs text-gray-400 mb-4 leading-relaxed">
                                        Gunakan fitur ini untuk memberi tahu buyer mengenai kondisi pengiriman,
                                        keterlambatan, atau informasi lain yang perlu diketahui buyer. Pesan ini akan muncul
                                        <strong class="text-indigo-300">secara mencolok</strong> di halaman riwayat
                                        pembelian buyer.
                                    </p>
                                    <form action="{{ route('admin.order.sendNote', $order->id) }}" method="POST"
                                        class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-xs font-bold text-gray-300 mb-1.5">
                                                Pesan untuk Buyer <span class="text-rose-400">*</span>
                                            </label>
                                            <textarea name="admin_note" rows="3" required maxlength="1000"
                                                placeholder="Contoh: Halo, kami ingin menginformasikan bahwa pengiriman paket Anda sedikit mengalami keterlambatan karena cuaca buruk di area transit. Paket dijadwalkan tiba pada tanggal yang telah kami estimasikan. Mohon sabar menunggu, kami memastikan barang Anda dalam kondisi aman. Terima kasih atas kepercayaan Anda! 🙏"
                                                class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-sm text-white focus:border-indigo-500 focus:ring-indigo-500 resize-none leading-relaxed"
                                                oninput="updateCharCount(this)">{{ $order->admin_note }}</textarea>
                                            <div class="flex justify-between mt-1">
                                                <span class="text-[11px] text-gray-500">Tulis pesan yang jelas, sopan, dan
                                                    meyakinkan agar buyer tetap tenang.</span>
                                                <span id="charCount"
                                                    class="text-[11px] text-gray-500">{{ strlen($order->admin_note ?? '') }}/1000</span>
                                            </div>
                                        </div>

                                        <div>
                                            <label class="block text-xs font-bold text-gray-300 mb-1.5">
                                                Estimasi Tanggal Tiba (Opsional)
                                            </label>
                                            <input type="date" name="estimasi_tiba"
                                                value="{{ $order->estimasi_tiba ? \Carbon\Carbon::parse($order->estimasi_tiba)->format('Y-m-d') : '' }}"
                                                min="{{ date('Y-m-d') }}"
                                                class="bg-slate-950 border border-slate-700 rounded-xl px-3 py-2 text-sm text-white focus:border-indigo-500 focus:ring-indigo-500" />
                                            <p class="text-[11px] text-gray-500 mt-1">Kosongkan jika belum ada estimasi yang
                                                pasti.</p>
                                        </div>

                                        <div class="flex justify-end pt-1">
                                            <button type="submit"
                                                class="flex items-center gap-2 px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition shadow-lg shadow-indigo-600/20">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" />
                                                </svg>
                                                {{ $order->admin_note ? 'Update Notifikasi ke Buyer' : 'Kirim Notifikasi ke Buyer' }}
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            </div>
        </div>

        <script>
            function updateCharCount(el) {
                document.getElementById('charCount').textContent = el.value.length + '/1000';
            }
        </script>
</x-app-layout>

@push('scripts')
    <script>
        function confirmRejectOrder(id) {
            Swal.fire({
                title: 'Batalkan Transaksi',
                text: 'Masukkan alasan pembatalan (misal: Bukti palsu, Saldo kurang):',
                input: 'text',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Batalkan Transaksi',
                cancelButtonText: 'Tutup',
                background: '#1e293b',
                color: '#f8fafc',
                confirmButtonColor: '#e11d48',
                cancelButtonColor: '#475569',
                inputValidator: (value) => {
                    if (!value || value.trim() === "") {
                        return 'Alasan penolakan / pembatalan transaksi wajib diisi!'
                    }
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('note-order-' + id).value = result.value;
                    document.getElementById('form-reject-order-' + id).submit();
                }
            });
        }
    </script>
@endpush