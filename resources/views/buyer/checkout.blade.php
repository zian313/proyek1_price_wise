<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="bg-[#0B132B] min-h-screen text-white flex flex-col md:flex-row">
        <main class="flex-1 p-6 md:p-12 overflow-y-auto space-y-8">
            <div class="border-l-4 border-[#00B4D8] pl-3">
                <h2 class="text-2xl font-black tracking-wide text-white">Rincian Pembelian</h2>
                <p class="text-xs text-gray-400">Pastikan barang yang Anda beli sudah sesuai sebelum melakukan konfirmasi.</p>
            </div>

            <!-- Tambahkan tag form di sini agar bisa kirim file bukti transfer -->
            <form action="{{ route('checkout.store', $product->id) }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                
                <!-- 1. SISI KIRI: KARTU RINCIAN PRODUK & INFORMASI PENGIRIMAN -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Rincian Produk -->
                    <div class="bg-[#1C2541]/50 border border-gray-800 rounded-2xl overflow-hidden p-6 shadow-xl backdrop-blur-md">
                        <h3 class="text-sm font-bold text-[#00B4D8] tracking-widest uppercase mb-4">Informasi Produk</h3>
                        <div class="flex flex-col sm:flex-row gap-6">
                            <div class="w-full sm:w-64 shrink-0 flex flex-col gap-3" x-data="{
                                activeImage: '{{ count($product->fotos) > 0 ? asset('storage/products/' . $product->fotos[0]) : '' }}',
                                images: [
                                    @foreach($product->fotos as $f)
                                        '{{ asset('storage/products/' . $f) }}',
                                    @endforeach
                                ]
                            }">
                                <div class="w-full h-64 bg-gray-900 rounded-xl overflow-hidden border border-gray-800 shadow-xl relative">
                                    @if(count($product->fotos) > 0)
                                        <img :src="activeImage" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover transition duration-300" x-transition.opacity>
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-5xl">📦</div>
                                    @endif
                                </div>
                                
                                @if(count($product->fotos) > 1)
                                    <div class="flex gap-2 overflow-x-auto pb-2 snap-x scrollbar-thin scrollbar-thumb-teal-500 scrollbar-track-gray-800">
                                        <template x-for="(img, index) in images" :key="index">
                                            <button type="button" @click="activeImage = img" class="w-16 h-16 shrink-0 rounded-lg overflow-hidden border-2 transition-all duration-300 snap-center" :class="activeImage === img ? 'border-[#00B4D8] shadow-md shadow-[#00B4D8]/30 scale-105' : 'border-transparent opacity-60 hover:opacity-100'">
                                                <img :src="img" class="w-full h-full object-cover">
                                            </button>
                                        </template>
                                    </div>
                                @endif
                            </div>

                            <div class="flex-1 space-y-2">
                                <h4 class="text-xl font-black text-white">{{ $product->nama_produk }}</h4>
                                
                                <!-- Seller Info in Checkout -->
                                <div class="flex items-center gap-2 mb-2 mt-2 p-2 bg-[#0B132B]/50 rounded-lg border border-gray-800/50 w-fit">
                                    <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-xs font-bold text-white shadow-md border border-indigo-400/30">
                                        {{ strtoupper(substr($product->user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="text-[9px] text-gray-500 uppercase tracking-widest font-bold leading-none">Penjual</span>
                                        <span class="text-xs font-bold text-indigo-200">{{ $product->user->name }}</span>
                                    </div>
                                </div>

                                <p class="text-sm text-gray-400 leading-relaxed">{{ $product->deskripsi }}</p>
                                <div class="pt-4 mt-2 border-t border-gray-800/60 flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Harga Satuan:</span>
                                    <span class="text-xl font-black text-emerald-400">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informasi Pengiriman -->
                    <div class="bg-[#1C2541]/50 border border-gray-800 rounded-2xl overflow-hidden p-6 shadow-xl backdrop-blur-md space-y-4">
                        <h3 class="text-sm font-bold text-[#00B4D8] tracking-widest uppercase mb-2">Informasi Pengiriman</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Nama Penerima</label>
                                <input type="text" name="nama" value="{{ old('nama', Auth::user()->name) }}" required class="w-full text-sm text-white bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#00B4D8] transition">
                            </div>
                            
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required class="w-full text-sm text-white bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#00B4D8] transition">
                            </div>
                        </div>

                        <div class="space-y-1.5">
                            <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Alamat Lengkap Pengiriman</label>
                            <textarea name="alamat" rows="3" required placeholder="Masukkan alamat lengkap (jalan, nomor rumah, RT/RW, kecamatan, kota, kode pos)" class="w-full text-sm text-white bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#00B4D8] transition">{{ old('alamat') }}</textarea>
                        </div>
                    </div>
                </div>
 
                <!-- 2. SISI KANAN: PENGIRIMAN, METODE PEMBAYARAN, & HARGA -->
                <div class="space-y-6">
                    <div class="bg-[#1C2541]/80 border border-[#00B4D8]/20 rounded-2xl p-6 shadow-2xl space-y-6">
                        <div>
                            <h3 class="text-sm font-bold text-white tracking-widest uppercase mb-4 pb-2 border-b border-gray-800">Pengiriman & Pembayaran</h3>
                            
                            <div class="space-y-4">
                                <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Pilih Ekspedisi</label>
                                    <select name="ekspedisi" required class="w-full text-sm text-gray-300 bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#00B4D8] transition cursor-pointer">
                                        <option value="" disabled selected>-- Pilih Ekspedisi --</option>
                                        <option value="JNE Express">JNE Express</option>
                                        <option value="J&T Express">J&T Express</option>
                                        <option value="SiCepat Express">SiCepat Express</option>
                                        <option value="POS Indonesia">POS Indonesia</option>
                                    </select>
                                </div>

                                 <div class="space-y-1.5">
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Metode Pembayaran</label>
                                    <select name="metode_pembayaran" required class="w-full text-sm text-gray-300 bg-gray-900 border border-gray-800 rounded-xl px-4 py-3 focus:outline-none focus:border-[#00B4D8] transition cursor-pointer">
                                        <option value="" disabled selected>-- Pilih Bank (Rekber) --</option>
                                        <option value="M-Banking BCA">M-Banking BCA</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-sm font-bold text-white tracking-widest uppercase mb-4 pb-2 border-b border-gray-800 flex items-center gap-2">
                                <svg class="w-4 h-4 text-emerald-400" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Ringkasan Harga
                            </h3>
                            
                            <div class="space-y-3 test-sm bg-gray-900/50 p-4 rounded-xl border border-gray-800 text-sm">
                                <div class="flex justify-between items-center text-gray-400">
                                    <span>Subtotal Produk</span>
                                    <span class="font-bold text-gray-200">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center text-emerald-400">
                                    <span class="flex items-center gap-1.5">
                                        <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                        Bebas Ongkir
                                    </span>
                                    <span class="font-black">GRATIS</span>
                                </div>
                                <div class="pt-3 mt-3 border-t border-gray-800 flex justify-between items-center">
                                    <span class="font-bold text-white">Total Pembayaran:</span>
                                    <span class="text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                            
                            <p class="text-[10px] text-gray-500 mt-3 text-center px-4 leading-relaxed">
                                Harga yang tertera sudah termasuk <b class="text-emerald-500">100% biaya pengiriman domestik</b>. Anda tidak akan dipungut biaya apapun oleh kurir.
                            </p>

                            <button type="submit" class="w-full text-center py-4 rounded-xl bg-gradient-to-r from-teal-500 to-emerald-500 text-white font-black text-sm hover:from-teal-400 hover:to-emerald-400 transition-all duration-300 shadow-xl shadow-teal-500/20 mt-6 uppercase tracking-widest transform hover:-translate-y-1">
                                Proses Pembayaran Sekarang
                            </button>            
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</x-app-layout>