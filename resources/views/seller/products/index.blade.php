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

            <!-- Welcome Banner -->
            <div class="mb-8 bg-gradient-to-r from-indigo-600 to-blue-500 rounded-3xl p-8 text-white shadow-xl shadow-indigo-500/20 relative overflow-hidden">
                <div class="relative z-10">
                    <h2 class="text-3xl font-black mb-2">Kelola Produk Anda 🚀</h2>
                    <p class="text-indigo-100 max-w-xl text-sm">Pantau performa penjualan, tambah produk baru, dan atur stok barang bekas Anda dengan mudah di satu tempat.</p>
                </div>
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-48 h-48 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 right-24 w-32 h-32 bg-blue-400 opacity-20 rounded-full blur-xl"></div>
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


</x-app-layout>
