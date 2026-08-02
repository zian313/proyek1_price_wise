<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Pembelian') }}
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

            <!-- Alert Validation Errors -->
            @if($errors->any())
                <div class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/30 border-l-4 border-rose-500 rounded-xl text-rose-800 dark:text-rose-300 shadow-sm">
                    <div class="font-bold text-xs uppercase tracking-wider mb-1 text-rose-400">Gagal Mengirim Pengajuan Refund:</div>
                    <ul class="list-disc list-inside text-xs space-y-1 text-rose-300">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Banner notifikasi admin dihapus — pesan admin kini terintegrasi langsung di kolom status order --}}

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-800 dark:text-white">Pesanan Saya</h3>
                    <p class="text-xs text-gray-400 mt-1">Lacak status transaksi dan verifikasi pembelian barang bekas Anda.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/30 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">ID Transaksi</th>
                                <th class="px-6 py-4">Tanggal</th>
                                <th class="px-6 py-4">Produk</th>
                                <th class="px-6 py-4">Total Harga</th>
                                <th class="px-6 py-4">Bukti Bayar</th>
                                <th class="px-6 py-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($orders as $order)
                                @php 
                                    // Mengambil detail produk dari detail order pertama
                                    $detail = $order->orderDetails->first();
                                    $product = $detail ? $detail->product : null;
                                @endphp
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 text-sm text-gray-700 dark:text-gray-300 transition duration-150">
                                    <td class="px-6 py-4 font-mono font-bold text-gray-400">#PW-{{ str_pad($order->id, 5, '0', STR_PAD_LEFT) }}</td>
                                    <td class="px-6 py-4 text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }} WIB</td>
                                    <td class="px-6 py-4">
                                        @if($product)
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 bg-gray-100 dark:bg-gray-950 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden flex items-center justify-center shrink-0">
                                                    @if($product->foto)
                                                        <img src="{{ asset('storage/products/' . $product->foto) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <p class="font-bold text-gray-800 dark:text-white">{{ $product->nama_produk }}</p>
                                                    <p class="text-xs text-gray-400">Penjual: {{ $product->user->name }}</p>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Produk dihapus</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($order->bukti_transfer)
                                            <a href="{{ asset('storage/bukti_transfer/' . $order->bukti_transfer) }}" target="_blank" class="text-xs font-bold text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                Lihat Bukti
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs italic">Tidak ada bukti</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        {{-- ============================================================
                                             KOLOM STATUS — SEMUA STATE DIHANDLE DI SINI
                                        ============================================================= --}}

                                        @if($order->status === 'menunggu_pembayaran')
                                            {{-- BAYAR --}}
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 gap-1.5">
                                                    <span class="w-1.5 h-1.5 bg-indigo-500 rounded-full animate-pulse"></span>
                                                    Menunggu Pembayaran
                                                </span>
                                                <a href="{{ route('orders.payment', $order->id) }}" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold transition">Bayar Sekarang</a>
                                            </div>

                                        @elseif($order->status === 'menunggu_verifikasi')
                                            {{-- VERIFIKASI ADMIN --}}
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-50 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>
                                                Menunggu Verifikasi
                                            </span>

                                        @elseif($order->status === 'komplain')
                                            {{-- TAHAP 1: REFUND DALAM PENINJAUAN ADMIN --}}
                                            <div class="flex flex-col gap-2 text-left">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/20 text-rose-300 border border-rose-500/40 gap-1.5 w-fit">
                                                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full animate-pulse"></span>
                                                    ⚠️ Refund Dalam Peninjauan Admin
                                                </span>
                                                <div class="bg-slate-900 border border-slate-700 rounded-xl p-3 max-w-xs space-y-1.5">
                                                    <p class="text-[10px] font-black text-gray-400 uppercase tracking-wider">Langkah Proses Refund:</p>
                                                    <p class="text-[11px] text-emerald-400 font-bold">✓ Pengajuan & Video Unboxing Terkirim</p>
                                                    <p class="text-[11px] text-amber-400 font-bold animate-pulse">⏳ Admin Sedang Meninjau...</p>
                                                    <p class="text-[10px] text-gray-500 leading-relaxed">Form resi & rekening refund akan muncul otomatis setelah Admin menyetujui.</p>
                                                </div>
                                                <p class="text-[10px] text-gray-500">Diajukan {{ $order->updated_at->diffForHumans() }}</p>
                                            </div>

                                        @elseif($order->status === 'refund_disetujui')
                                            {{-- TAHAP 2: ADMIN ACC — BUYER ISI RESI + NOREK --}}
                                            <div class="flex flex-col gap-2.5 text-left max-w-sm">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 gap-1.5 w-fit">
                                                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                                                    ✅ Refund Disetujui! Lengkapi Data Retur
                                                </span>
                                                {{-- Pesan dari Admin --}}
                                                @if($order->admin_note)
                                                    <div class="bg-indigo-950/60 border border-indigo-500/30 rounded-lg px-3 py-2">
                                                        <p class="text-[10px] font-black text-indigo-300 uppercase mb-0.5">📨 Pesan Admin:</p>
                                                        <p class="text-[11px] text-gray-200 leading-relaxed">{{ $order->admin_note }}</p>
                                                    </div>
                                                @endif
                                                {{-- Form isi resi + norek --}}
                                                <div class="bg-slate-900 border border-amber-500/30 rounded-xl p-3 space-y-2">
                                                    <p class="text-[11px] text-amber-300 font-bold">💳 Isi Rekening Refund & Resi Pengembalian Barang:</p>
                                                    <form action="{{ route('orders.submitRetur', $order->id) }}" method="POST" class="space-y-2">
                                                        @csrf
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div>
                                                                <label class="block text-[10px] text-gray-400 mb-0.5">Ekspedisi Retur</label>
                                                                <select name="ekspedisi_retur" required class="w-full text-xs bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-teal-500">
                                                                    <option value="">Pilih Ekspedisi</option>
                                                                    @foreach(['JNE','J&T','SiCepat','Anteraja','Pos Indonesia','Lainnya / Kurir Instant'] as $eks)
                                                                        <option value="{{ $eks }}" {{ old('ekspedisi_retur') === $eks ? 'selected' : '' }}>{{ $eks }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                            <div>
                                                                <label class="block text-[10px] text-gray-400 mb-0.5">No. Resi Retur</label>
                                                                <input type="text" name="no_resi_retur" value="{{ old('no_resi_retur') }}" placeholder="Nomor Resi" required class="w-full text-xs bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-teal-500 font-mono" />
                                                            </div>
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-2">
                                                            <div>
                                                                <label class="block text-[10px] text-gray-400 mb-0.5">Bank / E-Wallet</label>
                                                                <input type="text" name="bank_refund" value="{{ old('bank_refund') }}" placeholder="BCA / OVO" required class="w-full text-xs bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-teal-500" />
                                                            </div>
                                                            <div>
                                                                <label class="block text-[10px] text-gray-400 mb-0.5">No. Rekening / HP</label>
                                                                <input type="text" name="norek_refund" value="{{ old('norek_refund') }}" placeholder="Nomor rekening" required class="w-full text-xs bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-teal-500" />
                                                            </div>
                                                        </div>
                                                        <div>
                                                            <label class="block text-[10px] text-gray-400 mb-0.5">Nama Pemilik Rekening</label>
                                                            <input type="text" name="namarek_refund" value="{{ old('namarek_refund') }}" placeholder="Sesuai buku tabungan" required class="w-full text-xs bg-slate-950 border border-slate-700 rounded-lg p-2 text-white focus:border-teal-500" />
                                                        </div>
                                                        <button type="submit" class="w-full py-2 bg-amber-600 hover:bg-amber-500 text-white rounded-lg text-xs font-bold transition">
                                                            🚚 Simpan Resi & Rekening Refund
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>

                                        @elseif($order->status === 'barang_diretur')
                                            {{-- TAHAP 3: DATA SUDAH DIISI — FORM DISEMBUNYIKAN --}}
                                            <div class="flex flex-col gap-2 text-left max-w-sm">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-indigo-500/20 text-indigo-300 border border-indigo-500/40 gap-1.5 w-fit">
                                                    <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></span>
                                                    📦 Barang Sedang Dikembalikan ke Seller
                                                </span>
                                                <div class="bg-slate-900 border border-indigo-500/20 rounded-xl p-3 space-y-1.5 text-xs">
                                                    <p class="text-[10px] font-black text-indigo-300 uppercase tracking-wider">Data Retur Tersimpan ✓</p>
                                                    @if($order->no_resi_retur)
                                                        <p class="text-gray-300">
                                                            <span class="text-gray-500">Kurir:</span> <strong class="text-indigo-400">{{ $order->ekspedisi_retur ?? '-' }}</strong>
                                                            &nbsp;|&nbsp;
                                                            <span class="text-gray-500">Resi:</span> <strong class="font-mono text-white">{{ $order->no_resi_retur }}</strong>
                                                        </p>
                                                    @endif
                                                    @if($order->norek_refund)
                                                        <p class="text-gray-300">
                                                            <span class="text-gray-500">Rekening:</span> <strong class="text-white">{{ $order->bank_refund }} - {{ $order->norek_refund }}</strong> <span class="text-gray-500">({{ $order->namarek_refund }})</span>
                                                        </p>
                                                    @endif
                                                    @if($order->retur_diterima_seller)
                                                        <p class="text-emerald-400 font-bold text-[11px] pt-1">✅ Seller sudah terima barang retur. Admin akan segera transfer dana.</p>
                                                    @else
                                                        <p class="text-amber-400 text-[11px] pt-1">⏳ Menunggu Seller konfirmasi terima barang retur.</p>
                                                    @endif
                                                </div>
                                            </div>

                                        @elseif($order->status === 'menunggu_konfirmasi_refund')
                                            {{-- TAHAP 4: ADMIN SUDAH TRANSFER — BUYER KONFIRMASI --}}
                                            <div class="flex flex-col gap-2.5 text-left max-w-sm">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-cyan-500/20 text-cyan-300 border border-cyan-500/40 gap-1.5 w-fit">
                                                    <span class="w-1.5 h-1.5 bg-cyan-400 rounded-full animate-pulse"></span>
                                                    💸 Dana Refund Sudah Ditransfer Admin!
                                                </span>
                                                <div class="bg-slate-900 border border-cyan-500/30 rounded-xl p-3 space-y-3">
                                                    <p class="text-[10px] font-black text-cyan-300 uppercase tracking-wider">Konfirmasi Penerimaan Dana Refund:</p>
                                                    <p class="text-[11px] text-gray-200 leading-relaxed">
                                                        Admin menyatakan sudah mentransfer <strong class="text-teal-300">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                                                        ke <strong class="text-white">{{ $order->bank_refund }} - {{ $order->norek_refund }}</strong> (a.n {{ $order->namarek_refund }}).
                                                    </p>
                                                    {{-- Bukti Transfer --}}
                                                    @if($order->bukti_transfer_refund)
                                                        <div class="bg-slate-950 border border-slate-800 rounded-lg p-2">
                                                            <p class="text-[10px] text-gray-400 font-bold uppercase mb-1.5">📸 Bukti Transfer dari Admin:</p>
                                                            <a href="{{ asset('storage/bukti_refund/' . $order->bukti_transfer_refund) }}" target="_blank">
                                                                <img src="{{ asset('storage/bukti_refund/' . $order->bukti_transfer_refund) }}" class="w-full max-w-[200px] rounded-lg border border-slate-700 hover:opacity-80 transition" alt="Bukti Transfer Refund">
                                                            </a>
                                                            <a href="{{ asset('storage/bukti_refund/' . $order->bukti_transfer_refund) }}" target="_blank" class="text-[10px] text-cyan-400 hover:underline block mt-1">🔍 Lihat bukti full</a>
                                                        </div>
                                                    @endif
                                                    {{-- Tombol Konfirmasi --}}
                                                    <div class="flex gap-2 pt-1">
                                                        <form action="{{ route('orders.konfirmasiRefund', $order->id) }}" method="POST" class="flex-1" onsubmit="return confirm('Konfirmasi bahwa dana refund sudah masuk ke rekening Anda?')">
                                                            @csrf
                                                            <input type="hidden" name="konfirmasi" value="sudah">
                                                            <button type="submit" class="w-full py-2 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-black transition">
                                                                ✅ Dana Sudah Masuk
                                                            </button>
                                                        </form>
                                                        <form action="{{ route('orders.konfirmasiRefund', $order->id) }}" method="POST" class="flex-1">
                                                            @csrf
                                                            <input type="hidden" name="konfirmasi" value="belum">
                                                            <button type="submit" class="w-full py-2 bg-rose-900/80 hover:bg-rose-800 border border-rose-700 text-rose-200 rounded-lg text-xs font-black transition">
                                                                ❌ Belum Masuk
                                                            </button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>

                                        @elseif($order->status === 'dibatalkan' && $order->alasan_komplain)
                                            {{-- REFUND SELESAI (dari proses komplain) --}}
                                            <div class="flex flex-col gap-2 text-left">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-teal-500/20 text-teal-300 border border-teal-500/40 gap-1.5 w-fit">
                                                    <span class="w-1.5 h-1.5 bg-teal-400 rounded-full"></span>
                                                    ✅ Refund Selesai
                                                </span>
                                                <div class="bg-slate-900 border border-teal-500/20 rounded-xl p-3 text-xs space-y-1">
                                                    <p class="text-[10px] font-black text-teal-300 uppercase">Dana Refund Telah Diterima</p>
                                                    <p class="text-gray-400">Rp {{ number_format($order->total_harga, 0, ',', '.') }} → {{ $order->bank_refund }} - {{ $order->norek_refund }}</p>
                                                </div>
                                            </div>

                                        @elseif($order->status === 'investigasi')
                                            <div class="flex flex-col gap-2 text-left">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-900/60 text-rose-300 border border-rose-500/40 gap-1.5 w-fit">
                                                    <span class="w-1.5 h-1.5 bg-rose-400 rounded-full animate-pulse"></span>
                                                    🔍 Dalam Investigasi Admin & Ekspedisi
                                                </span>
                                                <div class="bg-slate-900 border border-rose-500/30 rounded-xl p-3 max-w-xs">
                                                    <p class="text-[11px] text-rose-300 font-bold">Terindikasi Kendala Ekspedisi / Barang Hilang</p>
                                                    <p class="text-[10px] text-gray-400 mt-1 leading-relaxed">Admin sedang memverifikasi dengan pihak kurir. Jika barang tidak ditemukan, dana Anda akan dikembalikan (Refund) 100%.</p>
                                                </div>
                                            </div>

                                        @elseif($order->status === 'keterlambatan')
                                            <div class="flex flex-col gap-2 text-left">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 border border-amber-500/40 gap-1.5 w-fit">
                                                    <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                                                    ⏳ Keterlambatan Transit
                                                </span>
                                                {{-- Pesan admin keterlambatan --}}
                                                @if($order->admin_note)
                                                    <div class="bg-slate-900 border border-amber-500/20 rounded-lg px-3 py-2 max-w-xs">
                                                        <p class="text-[10px] text-amber-300 font-bold uppercase mb-0.5">📨 Info Admin:</p>
                                                        <p class="text-[11px] text-gray-200 leading-relaxed">{{ $order->admin_note }}</p>
                                                        @if($order->estimasi_tiba)
                                                            <p class="text-[11px] text-teal-400 font-bold mt-1">📅 Est. tiba: {{ \Carbon\Carbon::parse($order->estimasi_tiba)->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                                                        @endif
                                                    </div>
                                                @endif
                                            </div>

                                        @elseif($order->status === 'selesai')
                                            <div class="flex items-center gap-3">
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 gap-1.5">
                                                    <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                    Selesai
                                                </span>
                                                <a href="{{ route('orders.receipt', $order->id) }}" target="_blank" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs font-bold transition">🧾 Cetak Struk</a>
                                            </div>

                                        @elseif($order->status === 'dibatalkan')
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-400 gap-1.5">
                                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span>
                                                Dibatalkan
                                            </span>

                                        @elseif(!in_array($order->status, ['komplain','refund_disetujui','barang_diretur','menunggu_konfirmasi_refund','investigasi','keterlambatan','selesai','dibatalkan']) && ($order->status === 'lunas' || $order->barang_dikirim || $order->status === 'dikirim'))
                                            {{-- BARANG DIKIRIM / LUNAS --}}
                                            <div class="flex flex-col gap-2">
                                                <div class="flex items-center gap-2 flex-wrap">
                                                    @if($order->barang_dikirim || $order->status === 'dikirim')
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-purple-50 dark:bg-purple-900/30 text-purple-700 dark:text-purple-400 gap-1.5">🚚 Barang Dikirim</span>
                                                    @else
                                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-400 gap-1.5">Lunas (Diproses Penjual)</span>
                                                    @endif
                                                    @if($order->no_resi)
                                                        <span class="text-xs font-mono font-bold text-teal-400 bg-slate-900 px-2 py-1 rounded border border-slate-700">Resi: {{ $order->no_resi }}</span>
                                                    @endif
                                                </div>
                                                {{-- Pesan admin (estimasi / keterlambatan) --}}
                                                @if($order->admin_note)
                                                    <div class="bg-slate-900 border border-indigo-500/20 rounded-lg px-3 py-2 max-w-xs">
                                                        <p class="text-[10px] text-indigo-300 font-bold uppercase mb-0.5">📨 Pesan Admin:</p>
                                                        <p class="text-[11px] text-gray-200 leading-relaxed">{{ $order->admin_note }}</p>
                                                        @if($order->estimasi_tiba)
                                                            <p class="text-[11px] text-teal-400 font-bold mt-1">📅 Est. tiba: {{ \Carbon\Carbon::parse($order->estimasi_tiba)->locale('id')->isoFormat('D MMMM YYYY') }}</p>
                                                        @endif
                                                    </div>
                                                @endif
                                                {{-- Timer --}}
                                                @if($order->waktu_sampai)
                                                    @php $deadline = \Carbon\Carbon::parse($order->waktu_sampai)->addHours(24); $isExpired = now()->greaterThanOrEqualTo($deadline); @endphp
                                                    @if(!$isExpired)
                                                        <div class="text-xs bg-amber-500/10 border border-amber-500/30 text-amber-300 p-2 rounded-lg" x-data="countdown('{{ $deadline->toIso8601String() }}')" x-init="start()">
                                                            ⏳ Konfirmasi Otomatis dlm: <span class="font-bold text-amber-400 font-mono" x-text="timeLeft"></span>
                                                        </div>
                                                    @else
                                                        <div class="text-xs bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 p-2 rounded-lg">⌛ Memproses penerimaan otomatis...</div>
                                                    @endif
                                                @endif
                                                {{-- Tombol aksi --}}
                                                @if($order->barang_dikirim || $order->status === 'dikirim')
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <form action="{{ route('orders.confirmReceipt', $order->id) }}" method="POST" onsubmit="return confirm('Konfirmasi barang sudah Anda terima?');">
                                                            @csrf
                                                            <button type="submit" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-bold transition">✅ Terima Barang</button>
                                                        </form>
                                                        <button type="button" onclick="openComplaintModal({{ $order->id }})" class="px-3 py-1.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-xs font-bold transition">⚠️ Ajukan Refund</button>
                                                    </div>
                                                @endif
                                            </div>

                                        @else
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-300">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        Anda belum pernah melakukan pembelian produk bekas.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL AJUKAN REFUND / KOMPLAIN -->
    <div id="complaintModal" class="fixed inset-0 z-50 hidden bg-black/70 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-gray-800 border border-gray-700 rounded-2xl max-w-lg w-full p-6 text-white shadow-2xl relative max-h-[90vh] overflow-y-auto">
            <div class="flex justify-between items-center border-b border-gray-700 pb-3 mb-4">
                <h3 class="text-lg font-bold text-rose-400">⚠️ Form Pengajuan Refund / Komplain</h3>
                <button type="button" onclick="closeComplaintModal()" id="btnCloseModal" class="text-gray-400 hover:text-white text-xl font-bold">&times;</button>
            </div>

            <!-- Alert Error dalam Modal -->
            <div id="modalErrorAlert" class="hidden mb-4 p-3 bg-rose-500/20 border border-rose-500/40 rounded-xl text-rose-300 text-xs"></div>
            <!-- Alert Success dalam Modal -->
            <div id="modalSuccessAlert" class="hidden mb-4 p-3 bg-emerald-500/20 border border-emerald-500/40 rounded-xl text-emerald-300 text-xs"></div>

            <form id="complaintForm" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-300 mb-1">Alasan Refund / Komplain Barang</label>
                        <textarea name="alasan_komplain" id="alasanKomplain" rows="3" required placeholder="Jelaskan secara detail kerusakan/kendala barang yang diterima..." class="w-full bg-slate-950 border border-slate-700 rounded-xl p-3 text-sm text-white focus:border-rose-500 focus:ring-rose-500"></textarea>
                    </div>

                    <div class="p-4 bg-rose-500/10 border border-rose-500/30 rounded-xl">
                        <label class="block text-xs font-bold text-rose-300 mb-1">📹 Upload Video Unboxing (WAJIB)</label>
                        <p class="text-[11px] text-gray-400 mb-2">Unggah bukti video saat pertama kali membuka paket tanpa jeda/edit (Format: MP4/MOV/AVI/WEBM, Maks. 200MB).</p>
                        <input type="file" id="videoInput" name="video_unboxing" accept="video/mp4,video/mov,video/avi,video/webm,video/x-matroska,.mkv" required onchange="handleVideoSelect(this)" class="block w-full text-xs text-slate-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-rose-950 file:text-rose-300 hover:file:bg-rose-900 border border-slate-700 rounded-xl cursor-pointer bg-slate-950" />
                        
                        <!-- Info file yang dipilih -->
                        <div id="videoNameDisplay" class="mt-2 text-xs font-bold text-teal-400 hidden"></div>
                        
                        <!-- Video Preview Kecil -->
                        <div id="videoPreviewContainer" class="mt-3 hidden">
                            <video id="videoPreview" controls class="w-full max-h-40 rounded-lg border border-slate-700 bg-black"></video>
                        </div>
                    </div>

                    <!-- Upload Progress Bar -->
                    <div id="uploadProgressContainer" class="hidden">
                        <div class="flex justify-between text-xs text-gray-400 mb-1">
                            <span>Mengunggah video...</span>
                            <span id="uploadPercent">0%</span>
                        </div>
                        <div class="w-full bg-slate-700 rounded-full h-3 overflow-hidden">
                            <div id="uploadProgressBar" class="h-3 bg-gradient-to-r from-rose-500 to-pink-500 rounded-full transition-all duration-300" style="width: 0%"></div>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-1">Jangan tutup halaman ini selama proses upload berlangsung.</p>
                    </div>

                    <div class="flex justify-end gap-3 pt-2">
                        <button type="button" onclick="closeComplaintModal()" class="px-4 py-2 bg-gray-700 hover:bg-gray-600 rounded-xl text-xs font-bold">Batal</button>
                        <button type="button" id="btnSubmitRefund" onclick="submitComplaintWithProgress()" class="px-4 py-2 bg-rose-600 hover:bg-rose-500 text-white rounded-xl text-xs font-bold flex items-center gap-2">
                            <span id="btnSubmitText">Kirim Pengajuan Refund</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        let currentOrderId = null;

        function handleVideoSelect(input) {
            const display = document.getElementById('videoNameDisplay');
            const previewContainer = document.getElementById('videoPreviewContainer');
            const preview = document.getElementById('videoPreview');
            const errorAlert = document.getElementById('modalErrorAlert');
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const sizeMb = (file.size / (1024 * 1024)).toFixed(2);
                const maxSizeMb = 200;
                
                // Validasi ukuran file sisi client
                if (file.size > maxSizeMb * 1024 * 1024) {
                    errorAlert.textContent = `❌ File terlalu besar! Ukuran video (${sizeMb} MB) melebihi batas maksimum ${maxSizeMb} MB.`;
                    errorAlert.classList.remove('hidden');
                    input.value = '';
                    display.classList.add('hidden');
                    previewContainer.classList.add('hidden');
                    return;
                }
                
                errorAlert.classList.add('hidden');
                display.textContent = `📹 File Dipilih: ${file.name} (${sizeMb} MB)`;
                display.classList.remove('hidden');
                
                // Tampilkan preview video
                const url = URL.createObjectURL(file);
                preview.src = url;
                previewContainer.classList.remove('hidden');
            } else {
                display.classList.add('hidden');
                previewContainer.classList.add('hidden');
            }
        }

        function openComplaintModal(orderId) {
            currentOrderId = orderId;
            const modal = document.getElementById('complaintModal');
            const form = document.getElementById('complaintForm');
            
            // Reset form
            form.reset();
            document.getElementById('videoNameDisplay').classList.add('hidden');
            document.getElementById('videoPreviewContainer').classList.add('hidden');
            document.getElementById('uploadProgressContainer').classList.add('hidden');
            document.getElementById('modalErrorAlert').classList.add('hidden');
            document.getElementById('modalSuccessAlert').classList.add('hidden');
            document.getElementById('btnSubmitText').textContent = 'Kirim Pengajuan Refund';
            document.getElementById('btnSubmitRefund').disabled = false;
            
            modal.classList.remove('hidden');
        }

        function closeComplaintModal() {
            document.getElementById('complaintModal').classList.add('hidden');
            currentOrderId = null;
        }

        function submitComplaintWithProgress() {
            const alasan = document.getElementById('alasanKomplain').value.trim();
            const videoInput = document.getElementById('videoInput');
            const errorAlert = document.getElementById('modalErrorAlert');
            const successAlert = document.getElementById('modalSuccessAlert');
            
            // Validasi manual sebelum submit
            if (!alasan) {
                errorAlert.textContent = '❌ Alasan komplain wajib diisi!';
                errorAlert.classList.remove('hidden');
                return;
            }
            if (!videoInput.files || videoInput.files.length === 0) {
                errorAlert.textContent = '❌ Video unboxing wajib diunggah!';
                errorAlert.classList.remove('hidden');
                return;
            }
            
            errorAlert.classList.add('hidden');
            
            // Buat FormData
            const formData = new FormData();
            formData.append('_token', document.querySelector('#complaintForm input[name=_token]').value);
            formData.append('alasan_komplain', alasan);
            formData.append('video_unboxing', videoInput.files[0]);
            
            // Tampilkan progress bar
            const progressContainer = document.getElementById('uploadProgressContainer');
            const progressBar = document.getElementById('uploadProgressBar');
            const progressPercent = document.getElementById('uploadPercent');
            const btnSubmit = document.getElementById('btnSubmitRefund');
            const btnSubmitText = document.getElementById('btnSubmitText');
            const btnClose = document.getElementById('btnCloseModal');
            
            progressContainer.classList.remove('hidden');
            btnSubmit.disabled = true;
            btnClose.disabled = true;
            btnSubmitText.textContent = 'Mengunggah...';
            
            const xhr = new XMLHttpRequest();
            
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percent = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percent + '%';
                    progressPercent.textContent = percent + '%';
                    if (percent < 100) {
                        btnSubmitText.textContent = `Mengunggah ${percent}%...`;
                    } else {
                        btnSubmitText.textContent = 'Memproses...';
                    }
                }
            });
            
            xhr.addEventListener('load', function() {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        progressBar.style.width = '100%';
                        progressPercent.textContent = '100%';
                        successAlert.textContent = '✅ Pengajuan refund berhasil dikirim! Halaman akan diperbarui...';
                        successAlert.classList.remove('hidden');
                        btnSubmitText.textContent = 'Berhasil!';
                        setTimeout(() => {
                            window.location.href = '/orders/history';
                        }, 1500);
                        return;
                    }
                } catch(e) {}
                
                if (xhr.status === 200 || xhr.status === 302) {
                    progressBar.style.width = '100%';
                    progressPercent.textContent = '100%';
                    successAlert.textContent = '✅ Pengajuan refund berhasil dikirim! Halaman akan diperbarui...';
                    successAlert.classList.remove('hidden');
                    btnSubmitText.textContent = 'Berhasil!';
                    // Redirect atau reload halaman
                    setTimeout(() => {
                        window.location.href = '/orders/history';
                    }, 1500);
                } else {
                    // Parse error message dari response
                    let errMsg = 'Terjadi kesalahan saat mengirim pengajuan. Coba lagi.';
                    try {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(xhr.responseText, 'text/html');
                        // Coba ambil pesan error dari Laravel validation
                        const validationErr = doc.querySelector('.text-sm.text-red-600');
                        if (validationErr) errMsg = validationErr.textContent.trim();
                    } catch(e) {}
                    
                    errorAlert.textContent = '❌ ' + errMsg;
                    errorAlert.classList.remove('hidden');
                    progressContainer.classList.add('hidden');
                    btnSubmit.disabled = false;
                    btnClose.disabled = false;
                    btnSubmitText.textContent = 'Kirim Pengajuan Refund';
                }
            });
            
            xhr.addEventListener('error', function() {
                errorAlert.textContent = '❌ Gagal terhubung ke server. Periksa koneksi internet Anda.';
                errorAlert.classList.remove('hidden');
                progressContainer.classList.add('hidden');
                btnSubmit.disabled = false;
                btnClose.disabled = false;
                btnSubmitText.textContent = 'Kirim Pengajuan Refund';
            });
            
            xhr.open('POST', `/orders/${currentOrderId}/complaint`);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.setRequestHeader('Accept', 'text/html,application/xhtml+xml');
            xhr.send(formData);
        }

        function countdown(targetDateIso) {
            return {
                timeLeft: '',
                start() {
                    const target = new Date(targetDateIso).getTime();
                    const update = () => {
                        const now = new Date().getTime();
                        const diff = target - now;
                        if (diff <= 0) {
                            this.timeLeft = '00j 00m 00s';
                            return;
                        }
                        const hours = Math.floor(diff / (1000 * 60 * 60));
                        const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                        const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                        this.timeLeft = `${String(hours).padStart(2, '0')}j ${String(minutes).padStart(2, '0')}m ${String(seconds).padStart(2, '0')}s`;
                    };
                    update();
                    setInterval(update, 1000);
                }
            }
        }
    </script>
</x-app-layout>

