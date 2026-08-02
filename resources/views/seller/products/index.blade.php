<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Kelola Produk Saya') }}
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

            <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Card Saldo -->
                <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 rounded-2xl p-6 text-white shadow-lg shadow-indigo-600/20 md:col-span-1 flex flex-col justify-between">
                    <div>
                        <p class="text-indigo-200 text-sm font-semibold mb-1">Total Saldo Aktif</p>
                        <h3 class="text-3xl font-black mb-4">Rp {{ number_format($user->saldo, 0, ',', '.') }}</h3>
                        
                        <div class="space-y-1">
                            <p class="text-xs text-indigo-300">Info Rekening Pencairan:</p>
                            @if($user->bank_name && $user->no_rekening)
                                <p class="text-sm font-bold">{{ $user->bank_name }} - {{ $user->no_rekening }}</p>
                                <p class="text-xs">{{ $user->atas_nama }}</p>
                            @else
                                <a href="{{ route('profile.edit') }}" class="inline-flex items-center text-xs font-bold bg-amber-500/20 text-amber-300 px-2.5 py-1 rounded-lg border border-amber-500/30 hover:bg-amber-500/30 transition">
                                    ⚠️ Belum Diisi, Klik untuk Setting
                                </a>
                            @endif
                        </div>
                    </div>
                    
                    <button type="button" onclick="openWithdrawModal()" class="mt-5 w-full py-2.5 bg-white text-indigo-700 hover:bg-indigo-50 rounded-xl text-sm font-bold shadow transition">
                        Tarik Saldo Ke Rekening
                    </button>
                </div>

                <!-- Riwayat Penarikan -->
                <div class="bg-white dark:bg-gray-800 rounded-2xl border border-gray-100 dark:border-gray-700 shadow-sm md:col-span-2 overflow-hidden flex flex-col">
                    <div class="p-4 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                        <h4 class="font-bold text-gray-800 dark:text-white">Riwayat Penarikan Terakhir</h4>
                    </div>
                    <div class="p-0 flex-1 overflow-y-auto overflow-x-auto max-h-[220px]">
                        @if($withdrawals->count() > 0)
                            <table class="w-full text-left text-sm min-w-[500px]">
                                <thead class="bg-gray-50 dark:bg-gray-900/40 text-xs text-gray-500 uppercase">
                                    <tr>
                                        <th class="px-4 py-3">Tanggal</th>
                                        <th class="px-4 py-3">Nominal</th>
                                        <th class="px-4 py-3">Status</th>
                                        <th class="px-4 py-3 text-right">Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($withdrawals as $wd)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/60 transition">
                                            <td class="px-4 py-3 text-gray-600 dark:text-gray-300 text-xs">
                                                {{ $wd->created_at->format('d M Y') }}
                                            </td>
                                            <td class="px-4 py-3 font-bold text-gray-800 dark:text-white">
                                                Rp {{ number_format($wd->amount, 0, ',', '.') }}
                                            </td>
                                            <td class="px-4 py-3">
                                                @if($wd->status === 'pending')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-700 dark:bg-amber-900/30 dark:text-amber-400">Menunggu Admin</span>
                                                @elseif($wd->status === 'completed')
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-700 dark:bg-emerald-900/30 dark:text-emerald-400">Selesai / Terkirim</span>
                                                @else
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-rose-100 text-rose-700 dark:bg-rose-900/30 dark:text-rose-400">Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="px-4 py-3 text-right text-[11px] text-gray-500">
                                                @if($wd->status === 'rejected')
                                                    <div class="inline-block p-2 bg-rose-900/40 border-l-[3px] border-rose-500 rounded text-left max-w-[180px]">
                                                        <span class="font-bold text-rose-300 block mb-0.5 uppercase text-[9px] tracking-wider">Alasan Ditolak:</span>
                                                        <span class="text-rose-100/90 leading-tight">{{ $wd->admin_note ?? '-' }}</span>
                                                    </div>
                                                @elseif($wd->bukti_transfer)
                                                    <a href="{{ asset('storage/bukti_withdrawal/'.$wd->bukti_transfer) }}" target="_blank" class="text-indigo-600 dark:text-indigo-400 font-bold hover:underline">Lihat Bukti</a>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        @else
                            <div class="h-full flex items-center justify-center p-6 pb-8 text-gray-400 text-sm flex-col">
                                <svg class="w-8 h-8 mb-2 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Belum ada riwayat penarikan dana
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex justify-between items-center bg-gray-50/50 dark:bg-gray-800/50">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Daftar Produk Bekas Saya</h3>
                        <p class="text-xs text-gray-400 mt-1">Kelola barang bekas yang ingin Anda tawarkan kepada pembeli.</p>
                    </div>
                    <a href="{{ route('products.create') }}" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-bold transition duration-200 flex items-center gap-2 shadow-sm shadow-indigo-600/10">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                        Tambah Produk
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse min-w-[800px]">
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/30 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">Foto</th>
                                <th class="px-6 py-4">Nama Produk</th>
                                <th class="px-6 py-4">Kategori</th>
                                <th class="px-6 py-4">Harga</th>
                                <th class="px-6 py-4">Stok</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($products as $product)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 text-sm text-gray-700 dark:text-gray-300 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="w-16 h-16 bg-gray-100 dark:bg-gray-950 rounded-xl overflow-hidden shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center">
                                            @if($product->foto)
                                                <img src="{{ asset('storage/products/' . $product->foto) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                                            @else
                                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-gray-800 dark:text-white">{{ $product->nama_produk }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                            {{ $product->category->name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 font-bold text-indigo-600 dark:text-indigo-400">
                                        Rp {{ number_format($product->harga, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($product->stok > 0)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-50 dark:bg-green-900/30 text-green-700 dark:text-green-300">
                                                {{ $product->stok }} Tersedia
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-rose-50 dark:bg-rose-900/30 text-rose-700 dark:text-rose-300">
                                                Habis
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <div class="flex justify-end gap-3">
                                            <a href="{{ route('products.edit', $product->id) }}" class="p-2 bg-amber-50 hover:bg-amber-100 dark:bg-amber-900/30 text-amber-600 dark:text-amber-400 rounded-xl transition duration-150" title="Edit Produk">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" onsubmit="confirmSubmit(event, 'Semua data terkait produk ini akan dihapus permanen. Apakah Anda yakin?', 'Ya, Hapus', 'error', '#e11d48')" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-2 bg-rose-50 hover:bg-rose-100 dark:bg-rose-900/30 text-rose-600 dark:text-rose-400 rounded-xl transition duration-150" title="Hapus Produk">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                        <svg class="w-12 h-12 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                                        Anda belum mengupload produk apapun.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal Tarik Saldo -->
    <div id="withdrawModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="fixed inset-0 bg-gray-900/75 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-2xl bg-[#1C2541] text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-slate-700">
                    <div class="px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-indigo-900/50 sm:mx-0 sm:h-10 sm:w-10">
                                <svg class="h-6 w-6 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818l.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182C13.536 12.219 12.768 12 12 12c-.725 0-1.45-.22-2.003-.659-1.106-.879-1.106-2.303 0-3.182s2.9-.879 4.006 0l.415.33M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg font-bold leading-6 text-white" id="modal-title">Tarik Saldo ke Rekening</h3>
                                <div class="mt-2 text-sm text-gray-400">
                                    <p>Saldo Tersedia: <strong class="text-indigo-400">Rp {{ number_format($user->saldo, 0, ',', '.') }}</strong></p>
                                    @if(empty($user->bank_name) || empty($user->no_rekening))
                                        <p class="text-rose-500 mt-2 text-xs font-bold">⚠️ Anda belum melengkapi data bank di Profil.</p>
                                    @else
                                        <p class="text-xs mt-1">Akan ditransfer ke: <strong>{{ $user->bank_name }} - {{ $user->no_rekening }}</strong> ({{ $user->atas_nama }})</p>
                                    @endif
                                </div>

                                <form id="withdrawForm" action="{{ route('seller.withdraw') }}" method="POST" class="mt-4">
                                    @csrf
                                    <div>
                                        <label for="amount" class="block text-sm font-medium text-gray-300">Nominal Penarikan (Rp)</label>
                                        <div class="relative mt-2 rounded-lg shadow-sm border border-slate-700 bg-slate-900/50">
                                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                                <span class="text-gray-400 sm:text-sm font-bold">Rp</span>
                                            </div>
                                            <!-- Gunakan pl-10 (jarak 40px) cukup karena "Rp" tidak terlalu lebar, beri spasi extra pada placeholder agar tidak saling tabrak -->
                                            <input type="number" name="amount" id="amount" class="block w-full rounded-lg border-0 bg-transparent text-white pl-10 py-2.5 focus:ring-0 focus:outline-none sm:text-sm" placeholder="Contoh: 50.000" min="10000" max="{{ $user->saldo }}" required>
                                        </div>
                                        <p class="text-xs text-gray-500 mt-2">Minimal penarikan Rp 10.000</p>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="bg-slate-900/60 px-4 py-4 sm:flex sm:flex-row-reverse sm:px-6 border-t border-slate-700">
                        <button type="submit" form="withdrawForm" class="inline-flex w-full justify-center rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-md hover:bg-indigo-500 sm:ml-3 sm:w-auto transition-transform active:scale-95" {{ empty($user->bank_name) ? 'disabled' : '' }}>Ajukan Penarikan</button>
                        <button type="button" onclick="closeWithdrawModal()" class="mt-3 inline-flex w-full justify-center rounded-xl bg-slate-800 px-4 py-2.5 text-sm font-semibold text-gray-300 hover:text-white shadow-sm ring-1 ring-inset ring-slate-700 hover:bg-slate-700 sm:mt-0 sm:w-auto transition-colors">Batal</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openWithdrawModal() {
            document.getElementById('withdrawModal').classList.remove('hidden');
        }
        function closeWithdrawModal() {
            document.getElementById('withdrawModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
