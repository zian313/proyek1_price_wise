<x-app-layout>
    <div class="min-h-screen bg-[#0B132B] text-white p-8">
        <div class="max-w-6xl mx-auto">
            
            <!-- HEADER KONTEN -->
            <div class="mb-8">
                <h1 class="text-3xl font-black tracking-tight bg-gradient-to-r from-teal-400 to-emerald-400 bg-clip-text text-transparent">
                    Selamat Datang, {{ Auth::user()->name }}!
                </h1>
                <p class="text-gray-400 text-sm mt-1">
                    @if(Auth::user()->role === 'seller')
                        Dashboard Penjual - Pantau aktivitas penjualan dan pesanan Anda di Price Wise.
                    @else
                        Pilih produk terbaik di bawah ini untuk melakukan simulasi pembelian manual.
                    @endif
                </p>
            </div>

            @if(Auth::user()->role === 'seller')
                <!-- STATS GRID FOR SELLER -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <!-- Total Produk -->
                    <div class="bg-[#1C2541] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wider block font-bold">Total Produk Saya</span>
                            <span class="text-3xl font-black text-teal-400 mt-2 block">{{ $totalProducts }}</span>
                        </div>
                        <div class="text-4xl">📦</div>
                    </div>
                    
                    <!-- Total Pendapatan -->
                    <div class="bg-[#1C2541] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wider block font-bold">Total Pendapatan (Lunas)</span>
                            <span class="text-3xl font-black text-emerald-400 mt-2 block">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-4xl">💰</div>
                    </div>

                    <!-- Pesanan Menunggu Verifikasi -->
                    <div class="bg-[#1C2541] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wider block font-bold">Pesanan Baru (Pending)</span>
                            <span class="text-3xl font-black text-amber-400 mt-2 block">{{ $recentOrdersCount }}</span>
                        </div>
                        <div class="text-4xl">🔔</div>
                    </div>
                </div>
            @endif

            <!-- KATALOG BARANG -->
            <h3 class="font-bold border-l-4 border-teal-500 pl-3 mb-6">
                @if(Auth::user()->role === 'seller')
                    Katalog Semua Barang Aktif
                @else
                    Katalog Barang Premium
                @endif
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($products as $product)
                <div class="bg-[#1C2541] p-6 rounded-xl border border-gray-700 flex flex-col justify-between">
                    <div>
                        <div class="w-full h-40 bg-slate-900 rounded-lg mb-4 overflow-hidden border border-slate-800 flex items-center justify-center">
                            @if($product->foto)
                                <img src="{{ asset('storage/products/' . $product->foto) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                            @else
                                <span class="text-5xl">📦</span>
                            @endif
                        </div>
                        
                        <!-- Detail Produk -->
                        <h4 class="font-bold text-lg text-white">{{ $product->nama_produk }}</h4>
                        <span class="inline-block bg-teal-900/50 text-teal-300 text-xs px-2.5 py-0.5 rounded-full mt-1 mb-2 font-semibold">
                            {{ $product->category->name }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1 mb-4">{{ Str::limit($product->deskripsi, 80) }}</p>
                    </div>

                    <!-- Harga dan Tombol -->
                    <div class="flex justify-between items-center pt-4 border-t border-slate-800">
                        <div class="flex flex-col">
                            <span class="text-xs text-gray-500">Harga</span>
                            <span class="text-teal-400 font-black">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                        </div>
                        @if(Auth::user()->role === 'buyer')
                            <a href="{{ route('checkout', $product->id) }}" class="bg-gradient-to-r from-[#00B4D8] to-[#0077B6] hover:opacity-90 text-black text-xs font-bold px-4 py-2 rounded-lg shadow-md transition">Beli</a>
                        @else
                            @if($product->user_id === Auth::id())
                                <a href="{{ route('products.edit', $product->id) }}" class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-md transition">Edit Milik Anda</a>
                            @else
                                <span class="text-xs text-gray-500 italic">Milik Seller Lain</span>
                            @endif
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-center text-gray-500 py-10">
                    Belum ada produk yang tersedia.
                </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>