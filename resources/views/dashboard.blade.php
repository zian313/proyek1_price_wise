<x-app-layout>
    <div x-data class="min-h-screen bg-[#0B132B] text-white p-8">
        <div class="max-w-6xl mx-auto">

            <!-- HEADER KONTEN -->
            <div class="mb-8">
                <h1
                    class="text-3xl font-black tracking-tight bg-gradient-to-r from-teal-400 to-emerald-400 bg-clip-text text-transparent">
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
                @if(Auth::user()->seller_status === 'pending')
                    <!-- BANNER PENDING APPROVAL SELLER -->
                    <div
                        class="mb-8 p-6 bg-amber-500/10 border-2 border-amber-500/50 rounded-2xl text-amber-200 shadow-xl backdrop-blur-sm">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-amber-500/20 rounded-xl text-amber-400 text-3xl">
                                ⏳
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-lg font-bold text-amber-400">Pendaftaran Akun Seller Sedang Ditinjau Admin</h3>
                                <p class="text-sm text-slate-300 leading-relaxed">
                                    Terima kasih telah mendaftar sebagai Seller di Price Wise. Berkas <strong>Foto KTP</strong>,
                                    <strong>Selfie KTP</strong>, dan <strong>Alamat Lengkap Toko</strong> Anda sedang dalam
                                    tahap verifikasi oleh tim Admin. Fitur penambahan produk dan penjualan akan otomatis aktif
                                    setelah disetujui.
                                </p>
                            </div>
                        </div>
                    </div>
                @elseif(Auth::user()->seller_status === 'rejected')
                    <!-- BANNER REJECTED SELLER -->
                    <div
                        class="mb-8 p-6 bg-rose-500/10 border-2 border-rose-500/50 rounded-2xl text-rose-200 shadow-xl backdrop-blur-sm">
                        <div class="flex items-start gap-4">
                            <div class="p-3 bg-rose-500/20 rounded-xl text-rose-400 text-3xl">
                                ❌
                            </div>
                            <div class="space-y-1">
                                <h3 class="text-lg font-bold text-rose-400">Pengajuan Akun Seller Ditolak</h3>
                                <p class="text-sm text-slate-300 leading-relaxed">
                                    Mohon maaf, pengajuan pendaftaran seller Anda ditolak oleh Admin. 
                                </p>
                                @if(Auth::user()->rejection_reason)
                                <div class="mt-2 p-3 bg-rose-900/40 rounded-xl border-l-4 border-rose-500">
                                    <p class="text-xs text-rose-200"><strong class="uppercase tracking-wider text-[10px]">Alasan Penolakan:</strong><br/>
                                    {{ Auth::user()->rejection_reason }}</p>
                                </div>
                                <p class="text-xs text-rose-400/80 mt-2 italic">
                                    Silakan hubungi tim dukungan Price Wise untuk mempertanyakan lebih lanjut atau mengajukan ulang.
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                <!-- STATS GRID FOR SELLER -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Total Produk -->
                    <div
                        class="bg-[#1C2541] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wider block font-bold">Total Produk
                                Saya</span>
                            <span class="text-3xl font-black text-teal-400 mt-2 block">{{ $totalProducts }}</span>
                        </div>
                        <div class="text-4xl">📦</div>
                    </div>

                    <!-- Total Pendapatan -->
                    <div
                        class="bg-[#1C2541] p-6 rounded-2xl border border-slate-800 shadow-xl flex items-center justify-between">
                        <div>
                            <span class="text-xs text-gray-400 uppercase tracking-wider block font-bold">Total Pendapatan
                                (Lunas)</span>
                            <span class="text-3xl font-black text-emerald-400 mt-2 block">Rp
                                {{ number_format($totalEarnings, 0, ',', '.') }}</span>
                        </div>
                        <div class="text-4xl">💰</div>
                    </div>
                </div>
            @endif

            <!-- KATALOG BARANG -->
            <div class="flex flex-col md:flex-row md:items-center justify-between mb-6 gap-4">
                <h3 class="font-bold border-l-4 border-teal-500 pl-3 m-0">
                    @if(Auth::user()->role === 'seller')
                        Katalog Semua Barang Aktif
                    @else
                        Katalog Barang Premium
                    @endif
                </h3>

                <!-- Fitur Pencarian -->
                <form action="{{ route('dashboard') }}" method="GET" class="relative max-w-md w-full">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Temukan barang bekas yang Anda cari..."
                        class="w-full bg-[#1C2541] border border-gray-700 text-white text-sm rounded-xl pl-10 pr-10 py-2.5 focus:border-teal-500 focus:ring-teal-500 transition-all placeholder-gray-500 shadow-lg shadow-black/20">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    @if(request('search'))
                        <!-- Tombol Hapus / Silang -->
                        <a href="{{ route('dashboard') }}"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-rose-400 transition"
                            title="Hapus Pencarian">
                            <svg class="w-5 h-5 bg-gray-800 rounded-full p-0.5" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </a>
                    @endif
                </form>
            </div>

            @if(request('search'))
                <div
                    class="mb-6 px-4 py-2.5 bg-teal-900/20 border border-teal-500/30 rounded-lg text-sm text-teal-300 flex items-center gap-2 shadow-sm">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>
                        Menampilkan hasil pencarian untuk: <strong class="text-white">"{{ request('search') }}"</strong>
                        <span class="text-gray-400 font-mono ml-1">({{ $products->count() }} hasil ditemukan)</span>
                    </span>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @forelse($products as $product)
                    <div class="bg-[#1C2541] p-6 rounded-xl border border-gray-700 flex flex-col justify-between relative group hover:border-indigo-500/50 hover:shadow-xl hover:shadow-indigo-500/10 transition-all duration-300">
                        <div>
                            <div class="w-full h-40 bg-slate-900 rounded-lg mb-4 overflow-hidden border border-slate-800 flex items-center justify-center relative group-hover:border-emerald-500/30 transition-colors">
                                @if($product->foto)
                                    <img src="{{ asset('storage/products/' . $product->foto) }}"
                                        alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-5xl">📦</span>
                                @endif
                            </div>

                            <h4 class="font-bold text-lg text-white mb-2">{{ $product->nama_produk }}</h4>
                            
                            <!-- Seller Info Card -->
                            <div class="flex items-center gap-2 mb-3">
                                <div class="w-6 h-6 rounded-full bg-gradient-to-tr from-indigo-500 to-purple-500 flex items-center justify-center text-[10px] font-bold text-white shadow-md shrink-0 border border-indigo-400/30">
                                    {{ strtoupper(substr($product->user->name, 0, 1)) }}
                                </div>
                                <span class="text-xs font-semibold text-indigo-200 truncate">{{ $product->user->name }}</span>
                            </div>

                            <div class="flex flex-wrap items-center gap-1.5 mb-2">
                                <span class="inline-block bg-teal-900/50 text-teal-300 text-[11px] px-2.5 py-0.5 rounded-full font-semibold">
                                    {{ $product->category->name }}
                                </span>
                                <span class="inline-flex items-center gap-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-black px-2 py-0.5 rounded-full">
                                    <svg class="w-2.5 h-2.5" width="10" height="10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                    BEBAS ONGKIR
                                </span>
                            </div>
                            <p class="text-[11px] text-gray-400 mt-1 mb-4 leading-relaxed">{{ Str::limit($product->deskripsi, 80) }}</p>
                        </div>

                        <!-- Harga dan Tombol -->
                        <div class="flex justify-between items-center pt-4 border-t border-slate-800">
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-500">Harga</span>
                                <span class="text-teal-400 font-black">Rp
                                    {{ number_format($product->harga, 0, ',', '.') }}</span>
                            </div>
                            @if(Auth::user()->role === 'buyer')
                                <div class="flex gap-2">
                                    <button type="button" @click="$dispatch('open-modal', { id: '{{ $product->id }}' })" class="flex items-center gap-1.5 bg-white/5 hover:bg-white/10 border border-white/10 text-gray-300 hover:text-white text-xs font-bold px-4 py-2.5 rounded-xl backdrop-blur-sm transition-all duration-300">
                                        <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                        Detail
                                    </button>
                                    <a href="{{ route('checkout', $product->id) }}" class="flex items-center gap-1.5 bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white text-xs font-black px-5 py-2.5 rounded-xl shadow-lg shadow-teal-500/30 transition-all duration-300 transform hover:scale-105">
                                        <svg class="w-4 h-4" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        Beli
                                    </a>
                                </div>
                            @else
                                @if($product->user_id === Auth::id())
                                    <a href="{{ route('products.edit', $product->id) }}"
                                        class="bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold px-3 py-2 rounded-lg shadow-md transition">Edit
                                        Milik Anda</a>
                                @else
                                    <span class="text-xs text-gray-500 italic">Milik Seller Lain</span>
                                @endif
                            @endif
                        </div>

                        <!-- Product Detail Modal dipindahkan ke luar Grid -->
                    </div>
                @empty
                    <div class="col-span-3 text-center text-gray-500 py-10">
                        Belum ada produk yang tersedia.
                    </div>
                @endforelse
            </div>

            <!-- Wadah Pop-up Modal Terpusat Menggunakan Alpine Events -->
            <div x-data="{ activeModal: null }" @open-modal.window="activeModal = $event.detail.id" @close-modal.window="activeModal = null">
                @foreach($products as $product)
                    <div x-show="activeModal === '{{ $product->id }}'" style="display: none;" class="product-modal fixed inset-0 z-[999999] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4">
                        <div x-show="activeModal === '{{ $product->id }}'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-90" @click.outside="activeModal = null" class="bg-[#0B132B] w-full max-w-4xl max-h-[90vh] overflow-y-auto rounded-3xl border border-gray-700 shadow-[0_0_50px_rgba(20,184,166,0.15)] flex flex-col md:flex-row relative">
                            
                            <!-- Close Button -->
                            <button @click="activeModal = null" class="absolute top-4 right-4 bg-gray-800/80 hover:bg-rose-500 text-white w-10 h-10 rounded-full flex items-center justify-center transition-all duration-300 z-50 shadow-lg backdrop-blur-md hover:rotate-90">
                                <svg class="w-5 h-5" width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            
                            <!-- Image Gallery Slider -->
                            <div class="w-full md:w-1/2 p-6 md:p-8 md:border-r border-gray-800 bg-gray-900/30">
                                <div class="w-full flex flex-col gap-4" x-data="{
                                    activeImage: '{{ count($product->fotos) > 0 ? asset('storage/products/' . $product->fotos[0]) : '' }}',
                                    images: [
                                        @foreach($product->fotos as $f)
                                            '{{ asset('storage/products/' . $f) }}',
                                        @endforeach
                                    ]
                                }">
                                    <div class="w-full aspect-square bg-gray-900 rounded-2xl overflow-hidden border border-gray-800 shadow-2xl relative group">
                                        @if(count($product->fotos) > 0)
                                            <img :src="activeImage" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-6xl opacity-30">📦</div>
                                        @endif
                                    </div>
                                    
                                    @if(count($product->fotos) > 1)
                                        <div class="flex gap-2.5 overflow-x-auto pb-3 pt-2 snap-x scrollbar-thin scrollbar-thumb-teal-500 scrollbar-track-gray-800/50 px-1">
                                            <template x-for="(img, index) in images" :key="index">
                                                <button type="button" @click="activeImage = img" class="w-20 h-20 shrink-0 rounded-xl overflow-hidden border-2 transition-all duration-300 snap-center" :class="activeImage === img ? 'border-teal-400 shadow-[0_0_15px_rgba(45,212,191,0.5)] scale-105' : 'border-transparent opacity-50 hover:opacity-100 hover:scale-95'">
                                                    <img :src="img" class="w-full h-full object-cover">
                                                </button>
                                            </template>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Product Info & Checkout Action -->
                            <div class="w-full md:w-1/2 p-6 md:p-8 lg:p-10 flex flex-col">
                                <h2 class="text-3xl font-black text-white mb-2">{{ $product->nama_produk }}</h2>
                                
                                <!-- Seller Info Modal -->
                                <div class="flex items-center gap-3 mb-5 p-3 bg-white/5 border border-white/10 rounded-2xl w-fit pr-6 backdrop-blur-sm shadow-sm hover:bg-white/10 transition-colors">
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center text-lg font-bold text-white shadow-inner border border-indigo-400/50">
                                        {{ strtoupper(substr($product->user->name, 0, 1)) }}
                                    </div>
                                    <div class="flex flex-col justify-center">
                                        <p class="text-[9px] text-gray-400 uppercase tracking-widest font-bold mb-0.5">Dijual Oleh</p>
                                        <p class="text-sm text-indigo-100 font-bold leading-none flex items-center gap-1">
                                            {{ $product->user->name }}
                                            <svg class="w-3.5 h-3.5 text-blue-400" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        </p>
                                    </div>
                                </div>

                                <span class="inline-flex items-center gap-1.5 self-start bg-teal-900/40 text-teal-300 text-xs px-3 py-1.5 rounded-full mb-6 font-bold border border-teal-500/20 shadow-sm">
                                    <svg class="w-3.5 h-3.5" width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    {{ $product->category->name }}
                                </span>
                                <h3 class="text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-teal-400 to-emerald-400 mb-6 drop-shadow-sm">Rp {{ number_format($product->harga, 0, ',', '.') }}</h3>
                                
                                <div class="flex-1 mb-8">
                                    <h4 class="text-sm font-bold text-gray-400 uppercase tracking-widest mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-500" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                                        Deskripsi Produk
                                    </h4>
                                    <div class="p-5 bg-gradient-to-br from-gray-900/80 to-gray-800/30 border border-gray-700/50 rounded-2xl shadow-inner">
                                        <p class="text-sm text-gray-300 leading-relaxed whitespace-pre-line">{{ $product->deskripsi }}</p>
                                    </div>
                                </div>
                                
                                <div class="pt-6 mt-auto">
                                    <a href="{{ route('checkout', $product->id) }}" class="flex items-center justify-center gap-3 w-full bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-400 hover:to-emerald-400 text-white font-black px-6 py-4 rounded-2xl shadow-xl shadow-teal-500/20 transition-all duration-300 transform hover:-translate-y-1 text-lg uppercase tracking-wider relative overflow-hidden group">
                                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                                        <svg class="w-6 h-6 relative z-10" width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                                        <span class="relative z-10">Beli Sekarang</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>