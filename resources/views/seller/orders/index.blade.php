<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Pesanan Masuk') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Alert Success -->
            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 rounded-xl text-emerald-800 dark:text-emerald-300 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Alert Error -->
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

            {{-- ============================================================
                 BANNER PERINGATAN REFUND — Muncul jika ada komplain aktif
            ============================================================= --}}
            @php
                $hasComplaint = $orderDetails->filter(fn($d) => in_array($d->order->status, ['komplain', 'refund_disetujui', 'barang_diretur']))->count();
            @endphp
            @if($hasComplaint > 0)
            <div class="mb-6 rounded-2xl border-2 border-rose-500 bg-rose-950/60 overflow-hidden shadow-xl shadow-rose-500/10">
                <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <div class="flex items-center gap-3 shrink-0">
                        <div class="w-12 h-12 rounded-xl bg-rose-500/20 flex items-center justify-center text-2xl animate-bounce shrink-0">
                            ⚠️
                        </div>
                        <div>
                            <h4 class="text-base font-black text-rose-300 flex items-center gap-2">
                                Ada Pengajuan Refund / Komplain!
                                <span class="inline-flex items-center justify-center w-5 h-5 rounded-full text-xs font-black bg-rose-500 text-white animate-pulse">{{ $hasComplaint }}</span>
                            </h4>
                            <p class="text-xs text-rose-400/80 mt-0.5 leading-relaxed">
                                Buyer mengajukan komplain pada salah satu pesanan Anda. <strong class="text-rose-300">Admin sedang meninjau</strong> bukti video unboxing dan akan memutuskan kelanjutan transaksi ini. Harap bersabar — Anda akan mendapat saldo jika komplain ditolak oleh Admin.
                            </p>
                        </div>
                    </div>
                    <div class="shrink-0 sm:ml-auto">
                        <div class="px-4 py-2 bg-rose-500/20 border border-rose-500/30 rounded-xl text-xs font-bold text-rose-300 uppercase tracking-wider text-center">
                            🔍 Dalam Peninjauan Admin
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Kelola Pesanan</h3>
                    <p class="text-xs text-gray-400 mt-1">Periksa bukti pembayaran pembeli dan ubah status pesanan.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/30 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">ID Order</th>
                                <th class="px-6 py-4">Pembeli</th>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Total Harga</th>
                                <th class="px-6 py-4">Bukti Transfer</th>
                                <th class="px-6 py-4 text-center">Status / Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($orderDetails as $detail)
                                @php
                                    // Ambil data order dan produk dari relasi order detail
                                    $order = $detail->order;
                                    $product = $detail->product;
                                    $isKomplain = $order->status === 'komplain';
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 text-sm text-gray-700 dark:text-gray-300 transition duration-150 {{ $isKomplain ? 'bg-rose-500/5 border-l-4 border-rose-500' : '' }}">
                                    <td class="px-6 py-4 font-mono font-bold text-gray-400">
                                        #PW-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                        @if($isKomplain)
                                            <span class="block mt-1 text-[10px] font-black text-rose-400 bg-rose-500/10 border border-rose-500/20 px-1.5 py-0.5 rounded uppercase w-fit">⚠️ Refund</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 dark:text-white">
                                            {{ $order->nama ?? $order->user->name }}</div>
                                        <div class="text-xs text-gray-400 mt-0.5">{{ $order->email ?? $order->user->email }}
                                        </div>
                                        <div class="text-xs text-indigo-600 dark:text-indigo-400 font-bold mt-1">Kurir:
                                            {{ $order->ekspedisi ?? '-' }}</div>
                                        <div class="text-xs text-gray-500 mt-1 max-w-xs break-all"
                                            title="{{ $order->alamat }}">Alamat: {{ $order->alamat ?? '-' }}</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-gray-100 dark:bg-gray-950 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden flex items-center justify-center shrink-0">
                                                @if($product->foto)
                                                    <img src="{{ asset('storage/products/' . $product->foto) }}"
                                                        alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                                                @else
                                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                                        viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                        </path>
                                                    </svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="font-bold text-gray-800 dark:text-white">
                                                    {{ $product->nama_produk }}</p>
                                                <p class="text-xs text-gray-400">Jumlah: {{ $detail->jumlah }} unit</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->bukti_transfer)
                                            <a href="{{ asset('storage/bukti_transfer/' . $order->bukti_transfer) }}"
                                                target="_blank"
                                                class="text-xs font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                                    </path>
                                                </svg>
                                                Lihat Bukti Bayar
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Tidak ada bukti</span>
                                        @endif
                                    </td>
                                    <!-- Kolom Status & Aksi Seller -->
                                    <td class="px-6 py-4 text-center">
                                        @if(in_array($order->status, ['komplain', 'refund_disetujui', 'barang_diretur', 'menunggu_konfirmasi_refund']) || ($order->status === 'dibatalkan' && $order->alasan_komplain))
                                            {{-- STATUS KOMPLAIN & RETUR: TAMPILKAN ALASAN, VIDEO UNBOXING, & MONITORING RESI RETUR UNTUK SELLER --}}
                                            <div class="flex flex-col items-start gap-2.5 text-left min-w-[240px]">
                                                @if($order->status === 'komplain')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40 gap-1.5">
                                                        <span class="w-1.5 h-1.5 bg-rose-400 rounded-full animate-pulse"></span>
                                                        ⚠️ Komplain Aktif
                                                    </span>
                                                @elseif($order->status === 'refund_disetujui')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 gap-1.5">
                                                        ⏳ Refund Disetujui (Menunggu Buyer Kirim Resi Retur)
                                                    </span>
                                                @elseif($order->status === 'barang_diretur')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 gap-1.5">
                                                        📦 Buyer Mengirimkan Barang Retur
                                                    </span>
                                                @elseif($order->status === 'menunggu_konfirmasi_refund')
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 gap-1.5">
                                                        <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-pulse"></span>
                                                        💸 Menunggu Konfirmasi Dana Buyer
                                                    </span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-950 text-rose-400 border border-rose-800">
                                                        ❌ Refund Selesai (Dibatalkan)
                                                    </span>
                                                @endif

                                                @if($order->alasan_komplain)
                                                    <div class="bg-slate-950 border border-rose-500/30 rounded-xl p-3 w-full">
                                                        <p class="text-[10px] font-black text-rose-400 uppercase tracking-wider mb-1">💬 Alasan Komplain Buyer:</p>
                                                        <p class="text-xs text-gray-200 leading-relaxed font-sans bg-slate-900 p-2 rounded-lg border border-slate-800">{{ $order->alasan_komplain }}</p>
                                                    </div>
                                                @endif

                                                {{-- PEMUTAR VIDEO UNBOXING UNTUK SELLER --}}
                                                @if($order->video_unboxing)
                                                    <div class="bg-slate-950 border border-indigo-500/30 rounded-xl p-2.5 w-full">
                                                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-wider mb-1.5 flex items-center gap-1">
                                                            📹 Bukti Video Unboxing Buyer:
                                                        </p>
                                                        <video controls class="w-full rounded-lg bg-black border border-slate-800" style="max-height: 250px; object-fit: contain;">
                                                            <source src="{{ asset('storage/unboxing_videos/' . $order->video_unboxing) }}">
                                                            Browser tidak mendukung pemutar video.
                                                        </video>
                                                        <a href="{{ asset('storage/unboxing_videos/' . $order->video_unboxing) }}" target="_blank" class="text-[10px] text-indigo-400 hover:underline block mt-1.5 text-center font-bold">
                                                            📥 Download / Buka Video Full
                                                        </a>
                                                    </div>
                                                @endif

                                                {{-- KONTROL RESI RETUR PENGEMBALIAN BARANG KE SELLER --}}
                                                @if($order->no_resi_retur)
                                                    <div class="bg-indigo-950/60 border border-indigo-500/40 rounded-xl p-3 w-full space-y-2">
                                                        <p class="text-[10px] font-black text-indigo-300 uppercase tracking-wider">📦 Resi Pengembalian Barang (Retur):</p>
                                                        <div class="flex items-center gap-2">
                                                            <span class="text-xs px-2 py-0.5 bg-indigo-500/20 text-indigo-300 font-bold rounded border border-indigo-500/30">
                                                                {{ $order->ekspedisi_retur ?? 'Ekspedisi' }}
                                                            </span>
                                                            <span class="text-xs font-mono font-bold text-teal-300 bg-slate-900 px-2 py-0.5 rounded border border-slate-800">
                                                                {{ $order->no_resi_retur }}
                                                            </span>
                                                        </div>
                                                        @if(!$order->retur_diterima_seller)
                                                            <form action="{{ route('seller.orders.confirmRetur', $order->id) }}" method="POST" onsubmit="confirmSubmit(event, 'Konfirmasi bahwa barang retur dari buyer sudah sampai di toko/rumah Anda? Setelah ini Admin akan mentransfer dana refund.', 'Ya, Sudah Sampai', 'info', '#059669')">
                                                                @csrf
                                                                <button type="submit" class="w-full py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition">
                                                                    ✅ Konfirmasi Barang Retur Diterima
                                                                </button>
                                                            </form>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-400">
                                                                ✓ Barang retur telah Anda terima
                                                            </span>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>
                                        @elseif($order->status === 'menunggu_verifikasi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400">
                                                Menunggu Verifikasi Admin
                                            </span>
                                            {{-- Status Lunas & belum dikirim: tampilkan tombol kirim barang --}}
                                        @elseif($order->status === 'lunas' && !$order->barang_dikirim)
                                            <div class="flex flex-col items-center gap-2">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                                    Lunas
                                                </span>
                                                <form action="{{ route('seller.orders.sendPackage', $order->id) }}" method="POST" class="flex flex-col items-center gap-2" onsubmit="confirmSubmit(event, 'Tandai barang ini sebagai telah dikirim?', 'Ya, Dikirim', 'info', '#2563eb')">
                                                    @csrf
                                                    <input type="text" name="no_resi" placeholder="Masukkan No. Resi" required class="text-xs bg-slate-900 border border-slate-700 rounded-lg px-2.5 py-1 text-white focus:border-teal-500 focus:ring-teal-500 w-40 text-center" />
                                                    <button type="submit" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition">
                                                        🚚 Kirim Barang
                                                    </button>
                                                </form>
                                            </div>
                                        @elseif($order->status === 'dibatalkan')
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300">
                                                Dibatalkan
                                            </span>
                                        @elseif($order->status === 'selesai')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300">
                                                ✅ Selesai — Saldo Diterima
                                            </span>
                                            {{-- Status sudah dikirim: tampilkan label dan tanggal pengiriman --}}
                                        @elseif($order->barang_dikirim || $order->status === 'dikirim')
                                            <div class="flex flex-col items-center gap-1">
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                                                    🚚 Barang Dikirim
                                                </span>
                                                @if($order->no_resi)
                                                    <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-2 py-0.5 rounded border border-slate-700">
                                                        Resi: {{ $order->no_resi }}
                                                    </span>
                                                @endif
                                                <span class="text-xs text-gray-400">
                                                    @if($order->tanggal_dikirim)
                                                        {{ \Carbon\Carbon::parse($order->tanggal_dikirim)->format('d M Y') }}
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <span
                                                class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4">
                                            </path>
                                        </svg>
                                        Belum ada pesanan masuk untuk produk Anda.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
