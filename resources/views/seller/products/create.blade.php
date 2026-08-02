<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah Produk Baru') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div
                class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div
                    class="p-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Jual Produk Baru</h3>
                        <p class="text-xs text-gray-400 mt-1">Masukkan rincian produk barang bekas yang akan dijual.</p>
                    </div>
                    <a href="{{ route('products.index') }}"
                        class="text-sm text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 font-semibold flex items-center gap-1 transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali
                    </a>
                </div>

                <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data"
                    class="p-6 space-y-6" x-data="{
                        prepareSubmit() {
                            const inputs = this.$el.querySelectorAll('input[type=file]');
                            for(let i=0; i<inputs.length; i++) {
                                if(inputs[i].files.length === 0) {
                                    inputs[i].disabled = true;
                                }
                            }
                        }
                    }" @submit="prepareSubmit()">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Product Name -->
                        <div class="md:col-span-2">
                            <label for="nama_produk"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama
                                Produk</label>
                            <input type="text" name="nama_produk" id="nama_produk" value="{{ old('nama_produk') }}"
                                placeholder="Contoh: Sepatu Sneakers Nike Airmax" required
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                            @error('nama_produk')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div>
                            <label for="category_id"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Kategori
                                Produk</label>
                            <select name="category_id" id="category_id" required
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                                <option value="" disabled selected>Pilih Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                            <label for="harga"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Harga
                                (Rupiah)</label>
                            <div class="relative">
                                <span
                                    class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400 font-bold text-sm select-none">Rp</span>
                                <input type="number" name="harga" id="harga" value="{{ old('harga') }}" placeholder="0"
                                    min="0" required
                                    class="w-full pl-12 pr-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                            </div>
                            @error('harga')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror

                            <div
                                class="mt-3 p-3.5 bg-gradient-to-r from-teal-50 to-emerald-50 dark:from-teal-900/20 dark:to-emerald-900/20 border border-teal-200 dark:border-teal-800/50 rounded-xl flex items-start gap-3 shadow-inner">
                                <svg class="w-5 h-5 text-teal-600 dark:text-teal-400 mt-0.5 shrink-0" width="20"
                                    height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                <p class="text-xs text-teal-800 dark:text-teal-300 leading-relaxed">
                                    <strong class="block mb-0.5">Strategi Promo Bebas Ongkir 🚚</strong>
                                    Masukkan harga yang <b class="text-teal-600 dark:text-teal-400">sudah termasuk
                                        estimasi ongkos kirim</b> (Misal rata-rata pengiriman domestik Rp 15.000 / Kg).
                                    Produk Anda akan ditandai dengan lencana "Bebas Ongkir" yang terbukti meningkatkan
                                    minat pembeli secara drastis!
                                </p>
                            </div>
                        </div>

                        <!-- Stock -->
                        <div>
                            <label for="stok"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Jumlah
                                Stok</label>
                            <input type="number" name="stok" id="stok" value="{{ old('stok', 1) }}" placeholder="1"
                                min="0" required
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                            @error('stok')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Photo Grid -->
                        <div>
                            <label class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-3">Album Foto
                                Produk (Maks 10, Utama Wajib) *</label>

                            <!-- 10 kotak foto ala Shopee menggunakan Alpine.js -->
                            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                                @for($i = 0; $i < 10; $i++)
                                    <div x-data="{ preview: null }"
                                        class="relative w-full aspect-square border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl flex flex-col items-center justify-center overflow-hidden transition cursor-pointer bg-gray-50/50 dark:bg-gray-800/30 hover:border-indigo-400 hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 group"
                                        @click="$refs.fileInput.click()">

                                        <!-- Preview Gambar -->
                                        <template x-if="preview">
                                            <div class="absolute inset-0 z-10 w-full h-full">
                                                <img :src="preview" class="w-full h-full object-cover">
                                                <div
                                                    class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition">
                                                    <span
                                                        class="text-white text-xs font-bold bg-black/50 px-2 py-1 rounded">Ubah</span>
                                                </div>
                                            </div>
                                        </template>

                                        <!-- Placeholder Kosong -->
                                        <div class="text-gray-400 dark:text-gray-500 flex flex-col items-center transition group-hover:text-indigo-500"
                                            x-show="!preview">
                                            <svg class="w-8 h-8 mb-2 opacity-50" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                            </svg>
                                            <span
                                                class="text-xs font-semibold">{{ $i == 0 ? 'Foto Utama' : 'Foto ' . ($i + 1) }}</span>
                                        </div>

                                        <!-- Hidden Input element -->
                                        <input x-ref="fileInput" type="file" name="foto[]" class="hidden" accept="image/*"
                                            {{ $i == 0 ? 'required' : '' }}
                                            @change="const file = $event.target.files[0]; if(file) preview = URL.createObjectURL(file);">
                                    </div>
                                @endfor
                            </div>

                            <p class="text-[11px] text-gray-500 dark:text-gray-400 mt-4 leading-relaxed">
                                <b>Panduan:</b> Klik kotak angka 1 untuk Foto Utama (Wajib). Klik kotak 2 sampai 10
                                secara manual dan pilih foto berbeda jika ingin menambah galeri (Opsional). Max 2MB per
                                file (JPG, PNG, WEBP).
                            </p>

                            @error('foto')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                            @error('foto.*')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Bank Details -->
                        <div class="md:col-span-2 border-t border-gray-100 dark:border-gray-700 pt-6">
                            <h4
                                class="text-sm font-bold text-indigo-600 dark:text-indigo-400 uppercase tracking-wider mb-2">
                                Rekening Penerimaan Dana Penjualan</h4>
                            <p class="text-xs text-gray-400 mb-4">Tambahkan rekening M-Banking Anda agar Admin dapat
                                mencairkan/mentransfer dana hasil penjualan kepada Anda setelah pembeli mengonfirmasi
                                penerimaan barang.</p>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="bank_name"
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nama
                                        Bank</label>
                                    <select name="bank_name" id="bank_name"
                                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200 cursor-pointer">
                                        <option value="" selected>-- Pilih Bank --</option>
                                        <option value="M-Banking BCA" {{ old('bank_name') == 'M-Banking BCA' ? 'selected' : '' }}>M-Banking BCA</option>
                                        <option value="M-Banking Mandiri" {{ old('bank_name') == 'M-Banking Mandiri' ? 'selected' : '' }}>M-Banking Mandiri</option>
                                        <option value="M-Banking BNI" {{ old('bank_name') == 'M-Banking BNI' ? 'selected' : '' }}>M-Banking BNI</option>
                                        <option value="M-Banking BRI" {{ old('bank_name') == 'M-Banking BRI' ? 'selected' : '' }}>M-Banking BRI</option>
                                    </select>
                                    @error('bank_name')
                                        <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="no_rekening"
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Nomor
                                        Rekening</label>
                                    <input type="text" name="no_rekening" id="no_rekening"
                                        value="{{ old('no_rekening') }}" placeholder="Contoh: 7140928122"
                                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                                    @error('no_rekening')
                                        <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="atas_nama"
                                        class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Atas Nama
                                        Rekening</label>
                                    <input type="text" name="atas_nama" id="atas_nama" value="{{ old('atas_nama') }}"
                                        placeholder="Contoh: Budi Santoso"
                                        class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">
                                    @error('atas_nama')
                                        <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="md:col-span-2">
                            <label for="deskripsi"
                                class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">Deskripsi
                                Produk</label>
                            <textarea name="deskripsi" id="deskripsi" rows="5"
                                placeholder="Tuliskan kondisi produk, kelengkapan, minus, dll..." required
                                class="w-full px-4 py-3 rounded-xl bg-gray-50 border border-gray-200 dark:bg-gray-900 dark:border-gray-700 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition text-gray-800 dark:text-gray-200">{{ old('deskripsi') }}</textarea>
                            @error('deskripsi')
                                <p class="text-sm text-rose-500 mt-2">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex justify-end pt-4 border-t border-gray-100 dark:border-gray-700">
                        <button type="submit"
                            class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold transition duration-200 shadow-sm shadow-indigo-600/10">
                            Simpan Produk
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>