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
                <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/30 border-l-4 border-rose-500 rounded-xl text-rose-800 dark:text-rose-300 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Rincian Transaksi #PW-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</h3>
                        <p class="text-xs text-gray-400 mt-1">Periksa informasi pembeli, produk, dan verifikasi bukti transfer manual.</p>
                    </div>
                    <a href="{{ route('admin.dashboard') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-semibold flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                <div class="p-6 space-y-8">
                    <!-- Grid Info Pesanan & Pembeli -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Informasi Pembeli</h4>
                            <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-xl p-4 space-y-3">
                                <div>
                                    <span class="text-xs text-gray-400 block">Nama Lengkap</span>
                                    <span class="font-bold text-gray-800 dark:text-white">{{ $order->user->name }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Alamat Email</span>
                                    <span class="text-sm text-gray-600 dark:text-gray-300">{{ $order->user->email }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Informasi Transaksi</h4>
                            <div class="bg-gray-50 dark:bg-gray-900/50 border border-gray-100 dark:border-gray-800 rounded-xl p-4 space-y-3">
                                <div>
                                    <span class="text-xs text-gray-400 block">Tanggal Pembelian</span>
                                    <span class="text-sm text-gray-800 dark:text-white font-medium">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-400 block">Status Saat Ini</span>
                                    <div class="mt-1">
                                        @if($order->status === 'menunggu_verifikasi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($order->status === 'lunas')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                                Lunas / Selesai
                                            </span>
                                        @elseif($order->status === 'selesai')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300">
                                                Diterima oleh Buyer
                                            </span>
                                        @elseif($order->status === 'dibatalkan')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400">
                                                Dibatalkan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
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
                        <div class="border border-gray-100 dark:border-gray-800 rounded-xl overflow-hidden">
                            <table class="w-full text-left border-collapse">
                                <thead>
                                    <tr class="bg-gray-50 dark:bg-gray-900/30 text-xs font-bold text-gray-400 uppercase border-b border-gray-100 dark:border-gray-800">
                                        <th class="px-4 py-3">Nama Produk</th>
                                        <th class="px-4 py-3">Penjual</th>
                                        <th class="px-4 py-3 text-right">Jumlah</th>
                                        <th class="px-4 py-3 text-right">Harga Satuan</th>
                                        <th class="px-4 py-3 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-800 text-sm text-gray-700 dark:text-gray-300">
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
                                            <td class="px-4 py-3.5 text-right font-mono">Rp {{ number_format($detail->harga_saat_beli, 0, ',', '.') }}</td>
                                            <td class="px-4 py-3.5 text-right font-bold text-teal-600 dark:text-teal-400 font-mono">
                                                Rp {{ number_format($detail->jumlah * $detail->harga_saat_beli, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                    <tr class="bg-gray-50 dark:bg-gray-900/10 font-bold border-t border-gray-100 dark:border-gray-800 text-gray-800 dark:text-white">
                                        <td colspan="4" class="px-4 py-4 text-right text-gray-400 uppercase tracking-wider text-xs">Total Pembayaran</td>
                                        <td class="px-4 py-4 text-right text-lg text-emerald-600 dark:text-emerald-400 font-black font-mono">
                                            Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Bukti Transfer & Aksi Verifikasi -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100 dark:border-gray-800">
                        <!-- Bukti Transfer Gambar -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Bukti Transfer Bank</h4>
                            @if($order->bukti_transfer)
                                <div class="p-2 border border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/30 rounded-2xl flex items-center justify-center overflow-hidden max-w-xs shadow-sm">
                                    <a href="{{ asset('storage/bukti_transfer/' . $order->bukti_transfer) }}" target="_blank" title="Klik untuk memperbesar">
                                        <img src="{{ asset('storage/bukti_transfer/' . $order->bukti_transfer) }}" 
                                             class="max-w-full h-auto rounded-xl hover:scale-105 transition-transform duration-200 shadow-md border border-gray-200/20" 
                                             alt="Bukti Transfer">
                                    </a>
                                </div>
                            @else
                                <div class="p-6 border border-dashed border-rose-300 dark:border-rose-900/50 bg-rose-50/10 rounded-xl text-center">
                                    <p class="text-rose-500 text-xs italic font-bold">Buyer belum mengunggah bukti transfer.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Form Verifikasi -->
                        <div class="space-y-4">
                            <h4 class="text-sm font-bold text-gray-400 uppercase tracking-wider">Aksi Verifikasi Admin</h4>
                            @if($order->status === 'menunggu_verifikasi')
                                <div class="bg-gray-50 dark:bg-gray-900/30 border border-gray-100 dark:border-gray-800 rounded-2xl p-6 space-y-4">
                                    <p class="text-xs text-gray-400 leading-relaxed">
                                        Periksa kembali apakah jumlah dana pada mutasi rekening bank Rekber Anda sudah sesuai dengan nominal total pembayaran di atas sebelum menyetujui transaksi.
                                    </p>
                                    
                                    <div class="flex flex-col sm:flex-row gap-3 pt-2">
                                        <!-- Form Approve -->
                                        <form action="{{ route('admin.order.verify', $order->id) }}" method="POST" class="flex-1">
                                            @csrf
                                            <input type="hidden" name="status" value="lunas">
                                            <button type="submit" class="w-full py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition duration-150 shadow-md shadow-emerald-600/10">
                                                ✅ Setujui
                                            </button>
                                        </form>

                                        <!-- Form Reject -->
                                        <form action="{{ route('admin.order.verify', $order->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan pesanan ini?')">
                                            @csrf
                                            <input type="hidden" name="status" value="dibatalkan">
                                            <button type="submit" class="w-full py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-black uppercase tracking-wider transition duration-150 shadow-md shadow-rose-600/10">
                                                ❌ Tolak
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div class="bg-gray-50 dark:bg-gray-900/20 border border-gray-100 dark:border-gray-800 rounded-2xl p-6 flex flex-col items-center justify-center text-center">
                                    <span class="text-2xl mb-2">🔒</span>
                                    <p class="text-xs text-gray-400 font-medium">Transaksi ini telah diproses dan status telah terkunci.</p>
                                    <span class="text-sm font-bold text-gray-300 mt-1">Status: {{ strtoupper($order->status) }}</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>