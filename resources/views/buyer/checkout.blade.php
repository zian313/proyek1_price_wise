<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="bg-[#0B132B] min-h-screen text-white flex flex-col md:flex-row">
        <main class="flex-1 p-6 md:p-12 overflow-y-auto space-y-8">
            <div class="border-l-4 border-[#00B4D8] pl-3">
                <h2 class="text-2xl font-black tracking-wide text-white">Rincian Pembelian</h2>
                <p class="text-xs text-gray-400">Pastikan barang yang Anda beli sudah sesuai sebelum melakukan konfirmasi.</p>
            </div>

            <!-- Tambahkan tag form di sini agar bisa kirim file bukti transfer -->
            <form action="{{ route('checkout.store', $product->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                @csrf
                
                <!-- 1. SISI KIRI: KARTU RINCIAN PRODUK -->
                <div class="lg:col-span-2 space-y-6">
                    <div class="bg-[#1C2541]/50 border border-gray-800 rounded-2xl overflow-hidden p-6 shadow-xl backdrop-blur-md">
                        <h3 class="text-sm font-bold text-[#00B4D8] tracking-widest uppercase mb-4">Informasi Produk</h3>
                        <div class="flex flex-col sm:flex-row gap-6">
                            <div class="w-full sm:w-44 h-44 bg-gray-900 rounded-xl overflow-hidden border border-gray-800">
                                @if($product->foto)
                                    <img src="{{ asset('storage/products/' . $product->foto) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-5xl">📦</div>
                                @endif
                            </div>

                            <div class="flex-1 space-y-2">
                                <h4 class="text-xl font-black text-white">{{ $product->nama_produk }}</h4>
                                <p class="text-sm text-gray-400 leading-relaxed">{{ $product->deskripsi }}</p>
                                <div class="pt-4 border-t border-gray-800/60 flex justify-between items-center">
                                    <span class="text-xs text-gray-500">Harga Satuan:</span>
                                    <span class="text-xl font-black text-emerald-400">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 2. SISI KANAN: FORM KONFIRMASI -->
                <div class="space-y-6">
                    <div class="bg-[#1C2541]/80 border border-[#00B4D8]/20 rounded-2xl p-6 shadow-2xl">
                        <h3 class="text-sm font-bold text-white tracking-widest uppercase mb-4 pb-2 border-b border-gray-800">Ringkasan Harga</h3>
                        
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between items-center pt-2">
                                <span class="font-bold text-gray-300">Total Pembayaran:</span>
                                <span class="text-2xl font-black text-emerald-400">Rp {{ number_format($product->harga, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <div class="mt-6 space-y-4">
                            <div class="space-y-1.5">
                                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Upload Bukti Transfer</label>
                                <input type="file" name="bukti_transfer" required class="w-full text-xs text-gray-400 bg-gray-900 border border-gray-800 rounded-xl px-3 py-2.5">
                            </div>

                            <button type="submit" class="w-full text-center py-3 rounded-xl bg-gradient-to-r from-[#00B4D8] to-[#0077B6] text-black font-black text-sm hover:opacity-90 transition duration-300 shadow-lg mt-2 uppercase">
                                🔒 Konfirmasi & Kirim
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </main>
    </div>
</x-app-layout>