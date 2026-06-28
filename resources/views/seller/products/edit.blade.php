<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Produk') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Ubah Produk</h3>
                        <p class="text-xs text-gray-400 mt-1">Perbarui rincian produk barang bekas Anda.</p>
                    </div>
                    <a href="{{ route('products.index') }}" class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-semibold flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                </div>

                <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div class="md:col-span-2">
                            <label for="nama_produk" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Produk</label>
                            <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                            @error('nama_produk')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Kategori Produk</label>
                            <select name="category_id" id="category_id" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div>
                            <label for="harga" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Harga (Rupiah)</label>
                            <div class="relative">
                                <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold text-sm select-none">Rp</span>
                                <input type="number" name="harga" id="harga" value="{{ old('harga', $product->harga) }}" min="0" required class="w-full pl-12 pr-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                            </div>
                            @error('harga')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Stock -->
                        <div>
                            <label for="stok" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Jumlah Stok</label>
                            <input type="number" name="stok" id="stok" value="{{ old('stok', $product->stok) }}" min="0" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                            @error('stok')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photo -->
                        <div>
                            <label for="foto" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Foto Produk</label>
                            <div class="flex gap-4 items-center">
                                @if($product->foto)
                                    <div class="w-16 h-16 shrink-0 bg-gray-100 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl overflow-hidden shadow-sm flex items-center justify-center">
                                        <img src="{{ asset('storage/products/' . $product->foto) }}" alt="Preview" class="w-full h-full object-cover">
                                    </div>
                                @endif
                                <div class="flex-1">
                                    <input type="file" name="foto" id="foto" accept="image/*" class="w-full px-4 py-2.5 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200 text-sm file:mr-4 file:py-1.5 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 dark:file:bg-indigo-900/30 dark:file:text-indigo-300 hover:file:bg-indigo-100">
                                </div>
                            </div>
                            <p class="text-xs text-gray-400 mt-2">Format: JPG, JPEG, PNG, WEBP (Maksimal 2MB). Biarkan kosong jika tidak diubah.</p>
                            @error('foto')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank Details -->
                        <div class="md:col-span-2 border-t border-gray-100 dark:border-gray-700 pt-6">
                            <h4 class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-2">Rekening Pembayaran M-Banking</h4>
                            <p class="text-xs text-gray-400 mb-4">Ubah rekening M-Banking Anda agar pembeli dapat mentransfer langsung ke rekening ini saat memesan produk ini.</p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="bank_name" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama Bank</label>
                                    <select name="bank_name" id="bank_name" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200 cursor-pointer">
                                        <option value="" {{ old('bank_name', $product->bank_name) === '' ? 'selected' : '' }}>-- Pilih Bank --</option>
                                        <option value="M-Banking BCA" {{ old('bank_name', $product->bank_name) === 'M-Banking BCA' ? 'selected' : '' }}>M-Banking BCA</option>
                                        <option value="M-Banking Mandiri" {{ old('bank_name', $product->bank_name) === 'M-Banking Mandiri' ? 'selected' : '' }}>M-Banking Mandiri</option>
                                        <option value="M-Banking BNI" {{ old('bank_name', $product->bank_name) === 'M-Banking BNI' ? 'selected' : '' }}>M-Banking BNI</option>
                                        <option value="M-Banking BRI" {{ old('bank_name', $product->bank_name) === 'M-Banking BRI' ? 'selected' : '' }}>M-Banking BRI</option>
                                    </select>
                                    @error('bank_name')
                                        <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                                
                                <div>
                                    <label for="no_rekening" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor Rekening</label>
                                    <input type="text" name="no_rekening" id="no_rekening" value="{{ old('no_rekening', $product->no_rekening) }}" placeholder="Contoh: 7140928122" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                                    @error('no_rekening')
                                        <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="atas_nama" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Atas Nama Rekening</label>
                                    <input type="text" name="atas_nama" id="atas_nama" value="{{ old('atas_nama', $product->atas_nama) }}" placeholder="Contoh: Budi Santoso" class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                                    @error('atas_nama')
                                        <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="deskripsi" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Deskripsi Produk</label>
                            <textarea name="deskripsi" id="deskripsi" rows="5" required class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                            @error('deskripsi')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition duration-200 shadow-sm shadow-indigo-600/10">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
