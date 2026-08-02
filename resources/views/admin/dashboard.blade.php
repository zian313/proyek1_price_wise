<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Alert Success -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 rounded-xl text-emerald-800 dark:text-emerald-300 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Alert Error -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/30 border-l-4 border-rose-500 rounded-xl text-rose-800 dark:text-rose-300 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- SECTION: PERSETUJUAN VERIFIKASI AKUN SELLER -->
            <div class="mb-10 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-amber-500/10 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span>🛡️ Pengajuan Verifikasi Seller</span>
                            @if(count($pendingSellers) > 0)
                                <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-500 text-black animate-pulse">
                                    {{ count($pendingSellers) }} Pending
                                </span>
                            @endif
                        </h3>
                        <p class="text-xs text-gray-400 mt-1">Periksa data identitas KTP, foto selfie, dan alamat lengkap toko seller sebelum memberikan persetujuan.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/30 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">Seller & Email</th>
                                <th class="px-6 py-4">Nama KTP</th>
                                <th class="px-6 py-4">Alamat Lengkap Toko/Rumah</th>
                                <th class="px-6 py-4">Dokumen KTP</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($pendingSellers as $seller)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 text-sm text-gray-700 dark:text-gray-300 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 dark:text-white">{{ $seller->name }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $seller->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-teal-600 dark:text-teal-400">
                                        {{ $seller->nama_ktp ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs max-w-xs leading-relaxed text-gray-300">
                                        {{ $seller->alamat_lengkap ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5">
                                            @if($seller->foto_ktp)
                                                <a href="{{ asset('storage/seller_docs/' . $seller->foto_ktp) }}" target="_blank" class="inline-flex items-center text-xs text-indigo-400 hover:underline gap-1">
                                                    📄 Lihat Foto KTP
                                                </a>
                                            @endif
                                            @if($seller->selfie_ktp)
                                                <a href="{{ asset('storage/seller_docs/' . $seller->selfie_ktp) }}" target="_blank" class="inline-flex items-center text-xs text-emerald-400 hover:underline gap-1">
                                                    📸 Lihat Selfie + KTP
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                                            Menunggu Approval
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <form action="{{ route('admin.seller.approve', $seller->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui seller ini?')" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                                    Setujui (Approve)
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.seller.reject', $seller->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menolak seller ini?')" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-xs">
                                        Tidak ada pendaftaran seller yang sedang menunggu persetujuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- =====================================================
                 SEKSI ANTRIAN REFUND / KOMPLAIN — PRIORITAS ADMIN
            ====================================================== -->
            @if($complaintOrders->count() > 0)
            <div class="mb-10 bg-white dark:bg-gray-800 rounded-2xl shadow-lg border-2 border-rose-500/50 overflow-hidden">
                <div class="p-6 border-b border-rose-500/30 bg-rose-500/10 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/20 flex items-center justify-center text-xl animate-bounce">
                            ⚠️
                        </div>
                        <div>
                            <h3 class="text-lg font-black text-rose-400 flex items-center gap-2">
                                Antrian Refund / Komplain Buyer
                                <span class="inline-flex items-center justify-center w-6 h-6 rounded-full text-xs font-black bg-rose-500 text-white animate-pulse">
                                    {{ $complaintOrders->count() }}
                                </span>
                            </h3>
                            <p class="text-xs text-rose-300/70 mt-0.5">Buyer telah mengajukan refund. Tinjau video unboxing dan putuskan keputusan di halaman detail.</p>
                        </div>
                    </div>
                    <span class="text-xs font-bold text-rose-400 bg-rose-500/10 border border-rose-500/30 px-3 py-1.5 rounded-full uppercase tracking-wider">
                        Butuh Tindakan Segera
                    </span>
                </div>

                <div class="divide-y divide-rose-500/10">
                    @foreach($complaintOrders as $cOrder)
                        @php $cProduct = $cOrder->orderDetails->first()?->product; @endphp
                        <div class="p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 hover:bg-rose-500/5 transition">
                            <div class="flex items-start gap-4">
                                <!-- Foto Produk -->
                                <div class="w-12 h-12 rounded-xl bg-slate-900 border border-rose-500/20 overflow-hidden flex items-center justify-center shrink-0">
                                    @if($cProduct && $cProduct->foto)
                                        <img src="{{ asset('storage/products/' . $cProduct->foto) }}" alt="" class="w-full h-full object-cover">
                                    @else
                                        <span class="text-xl">📦</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="font-mono font-bold text-rose-300 text-sm">#PW-{{ str_pad($cOrder->id, 5, '0', STR_PAD_LEFT) }}</span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs font-semibold text-white">{{ $cOrder->user->name ?? 'Buyer' }}</span>
                                        <span class="text-xs text-gray-400">•</span>
                                        <span class="text-xs font-bold text-teal-400">Rp {{ number_format($cOrder->total_harga, 0, ',', '.') }}</span>
                                    </div>
                                    <p class="text-sm font-bold text-gray-200 mt-1">
                                        {{ $cProduct->nama_produk ?? 'Produk dihapus' }}
                                    </p>
                                    <div class="mt-2 bg-rose-500/10 border border-rose-500/20 rounded-lg px-3 py-2 max-w-md">
                                        <span class="text-[10px] font-bold text-rose-400 uppercase tracking-wider block mb-0.5">Alasan Komplain Buyer:</span>
                                        <p class="text-xs text-gray-300 leading-relaxed line-clamp-2">{{ $cOrder->alasan_komplain ?? '-' }}</p>
                                    </div>
                                    <div class="mt-2 flex items-center gap-2 flex-wrap">
                                        @if($cOrder->status === 'barang_diretur')
                                            <span class="text-[11px] bg-indigo-500/20 border border-indigo-500/30 text-indigo-300 px-2 py-0.5 rounded font-bold">
                                                📦 Retur: {{ $cOrder->ekspedisi_retur ?? 'Kurir' }} (Resi: {{ $cOrder->no_resi_retur }})
                                            </span>
                                            @if($cOrder->norek_refund)
                                                <span class="text-[11px] bg-teal-500/20 border border-teal-500/30 text-teal-300 px-2 py-0.5 rounded font-bold">
                                                    💳 Rek: {{ $cOrder->bank_refund }} - {{ $cOrder->norek_refund }}
                                                </span>
                                            @endif
                                        @elseif($cOrder->status === 'refund_disetujui')
                                            <span class="text-[11px] bg-amber-500/20 border border-amber-500/30 text-amber-300 px-2 py-0.5 rounded font-bold">
                                                ⏳ Menunggu Resi Retur & Rekening Buyer
                                            </span>
                                        @endif

                                        @if($cOrder->video_unboxing)
                                            <span class="text-[11px] text-emerald-400 flex items-center gap-1 font-bold">
                                                <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                                Video Unboxing Tersedia
                                            </span>
                                        @else
                                            <span class="text-[11px] text-rose-400 font-bold">⚠️ Tanpa Video Unboxing</span>
                                        @endif
                                        <span class="text-[10px] text-gray-500">• {{ $cOrder->updated_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <a href="{{ route('admin.order.detail', $cOrder->id) }}" class="flex items-center gap-2 px-4 py-2.5 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-black transition shadow-lg shadow-rose-600/20 whitespace-nowrap">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Tinjau & Putuskan
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Semua Transaksi Masuk</h3>
                    <p class="text-xs text-gray-400 mt-1">Pantau seluruh order, verifikasi bukti pembayaran manual, dan kendalikan status transaksi platform Price Wise.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/30 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">ID Transaksi</th>
                                <th class="px-6 py-4">Pembeli</th>
                                <th class="px-6 py-4">Total Harga</th>
                                <th class="px-6 py-4">Bukti Transfer</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 text-sm text-gray-700 dark:text-gray-300 transition duration-150 {{ $order->status === 'komplain' ? 'bg-rose-500/5 border-l-2 border-rose-500' : '' }}">
                                    <td class="px-6 py-4 font-mono font-bold text-gray-400">
                                        #PW-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}
                                        @if($order->status === 'komplain')
                                            <span class="ml-1 text-[10px] font-black text-rose-400 bg-rose-500/10 border border-rose-500/20 px-1.5 py-0.5 rounded uppercase">Refund</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-gray-800 dark:text-white">{{ $order->user->name ?? 'Guest' }}</td>
                                    <td class="px-6 py-4 font-bold text-teal-600 dark:text-teal-400">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->bukti_transfer)
                                            <a href="{{ asset('storage/bukti_transfer/' . $order->bukti_transfer) }}" target="_blank" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-slate-900/10 text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-200 gap-1.5 transition">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-rose-500 text-xs italic">Belum Upload</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->status === 'komplain')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40 gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-rose-400 rounded-full animate-pulse"></span>
                                                ⚠️ Komplain / Refund
                                            </span>
                                        @elseif($order->status === 'menunggu_verifikasi')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                                Menunggu Verifikasi
                                            </span>
                                        @elseif($order->status === 'lunas')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-350">
                                                Lunas
                                            </span>
                                        @elseif($order->status === 'selesai')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-50 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300">
                                                Diterima (Buyer)
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
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.order.detail', $order->id) }}" class="inline-flex items-center px-3 py-1.5 {{ $order->status === 'komplain' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-indigo-600 hover:bg-indigo-700' }} text-white rounded-lg text-xs font-bold transition duration-150 shadow-sm">
                                            {{ $order->status === 'komplain' ? '⚠️ Tinjau Refund' : 'Periksa Detail' }}
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                                        Belum ada data pesanan transaksi yang masuk.
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