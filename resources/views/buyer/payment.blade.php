<x-app-layout>
    <script src="https://cdn.tailwindcss.com"></script>

    <div class="bg-[#0B132B] min-h-screen text-white flex flex-col">
        <main class="flex-1 p-6 md:p-12 overflow-y-auto max-w-7xl mx-auto w-full space-y-8">
            <!-- Header and Navigation -->
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 border-b border-gray-800 pb-6">
                <div class="border-l-4 border-[#00B4D8] pl-3">
                    <h2 class="text-2xl font-black tracking-wide text-white">Selesaikan Pembayaran</h2>
                    <p class="text-xs text-gray-400">Silakan transfer sesuai nominal ke rekening bersama kami di bawah ini.</p>
                </div>
                <a href="{{ route('orders.history') }}" class="inline-flex items-center text-xs font-bold text-gray-400 hover:text-[#00B4D8] transition gap-1.5 bg-[#1C2541]/40 border border-gray-800 px-4 py-2 rounded-xl">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Batal & Kembali ke Riwayat
                </a>
            </div>

            <!-- Progress Tracker (Stepper) -->
            <div class="bg-[#1C2541]/30 border border-gray-800 rounded-2xl p-6">
                <div class="flex flex-col md:flex-row items-center justify-between gap-6 max-w-3xl mx-auto">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-emerald-500/20 text-emerald-400 flex items-center justify-center font-bold text-sm border border-emerald-500/30">✓</div>
                        <div>
                            <p class="text-xs font-black text-white uppercase tracking-wider">Langkah 1</p>
                            <p class="text-xs text-gray-400">Informasi Checkout</p>
                        </div>
                    </div>
                    <div class="hidden md:block flex-1 h-0.5 bg-gradient-to-r from-emerald-500 to-[#00B4D8]"></div>
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-[#00B4D8]/20 text-[#00B4D8] flex items-center justify-center font-bold text-sm border border-[#00B4D8]">2</div>
                        <div>
                            <p class="text-xs font-black text-white uppercase tracking-wider">Langkah 2</p>
                            <p class="text-xs text-[#00B4D8]">Transfer & Upload Bukti</p>
                        </div>
                    </div>
                    <div class="hidden md:block flex-1 h-0.5 bg-gray-800"></div>
                    <div class="flex items-center gap-3 opacity-50">
                        <div class="w-8 h-8 rounded-full bg-gray-900 text-gray-400 flex items-center justify-center font-bold text-sm border border-gray-800">3</div>
                        <div>
                            <p class="text-xs font-black text-gray-400 uppercase tracking-wider">Langkah 3</p>
                            <p class="text-xs text-gray-500">Verifikasi Admin</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- SISI KIRI: METODE TRANSFER & DETAIL PENERIMA -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Rekening Bank Card -->
                    <div class="bg-[#1C2541]/50 border border-gray-800 rounded-2xl p-6 shadow-xl backdrop-blur-md space-y-6">
                        <h3 class="text-sm font-bold text-[#00B4D8] tracking-widest uppercase pb-2 border-b border-gray-800/60">Tujuan Transfer</h3>
                        
                        <!-- Visual Card Bank -->
                        <div class="bg-gradient-to-br from-[#1D3557] to-[#457B9D] p-6 rounded-2xl shadow-lg relative overflow-hidden max-w-md border border-white/10 space-y-6">
                            <div class="absolute -right-16 -top-16 w-44 h-44 rounded-full bg-white/5 pointer-events-none"></div>
                            
                            <div class="flex justify-between items-start">
                                <div>
                                    <p class="text-xs text-white/60 font-medium uppercase tracking-widest">Metode Pembayaran</p>
                                    <p class="text-lg font-black text-white mt-0.5">
                                        @if($product && $product->bank_name && $product->no_rekening)
                                            {{ $product->bank_name }}
                                        @else
                                            {{ $order->metode_pembayaran }}
                                        @endif
                                    </p>
                                </div>
                                <span class="text-2xl">🏦</span>
                            </div>

                            <div class="space-y-1">
                                <p class="text-[10px] text-white/50 uppercase tracking-wider font-semibold">
                                    @if($product && $product->bank_name && $product->no_rekening)
                                        Nomor Rekening Penjual
                                    @else
                                        Nomor Rekening Rekber
                                    @endif
                                </p>
                                <div class="flex items-center gap-3">
                                    <span id="rekening-num" class="text-xl font-bold font-mono tracking-wider text-white">
                                        @if($product && $product->bank_name && $product->no_rekening)
                                            {{ $product->no_rekening }}
                                        @else
                                            @if(str_contains(strtolower($order->metode_pembayaran), 'bca'))
                                                7140928122
                                            @elseif(str_contains(strtolower($order->metode_pembayaran), 'mandiri'))
                                                1320098234231
                                            @elseif(str_contains(strtolower($order->metode_pembayaran), 'bni'))
                                                0983248324
                                            @else
                                                002301009823301
                                            @endif
                                        @endif
                                    </span>
                                    <button type="button" id="rekening-num-btn" onclick="copyText('rekening-num')" class="text-xs bg-white/10 hover:bg-white/20 border border-white/10 rounded-lg px-2 py-1 transition flex items-center gap-1 font-sans font-bold">
                                        📋 Salin
                                    </button>
                                </div>
                            </div>

                            <div class="flex justify-between items-end pt-2">
                                <div>
                                    <p class="text-[10px] text-white/50 uppercase tracking-wider font-semibold">Atas Nama Rekening</p>
                                    <p class="text-sm font-bold text-white uppercase">
                                        @if($product && $product->bank_name && $product->no_rekening)
                                            {{ $product->atas_nama ?? ($product->user->name ?? 'Penjual') }}
                                        @else
                                            Price Wise Rekber
                                        @endif
                                    </p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-white/50 uppercase tracking-wider font-semibold">Total Tagihan</p>
                                    <p class="text-lg font-black text-emerald-300 font-mono">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Info Transfer details -->
                        <div class="bg-gray-900/60 rounded-xl p-4 border border-gray-800 space-y-2 text-xs text-gray-400">
                            <p class="font-bold text-white text-sm mb-1">💡 Petunjuk Transfer:</p>
                            <ul class="list-disc pl-4 space-y-1">
                                <li>Gunakan M-Banking atau ATM sesuai bank pilihan Anda.</li>
                                <li>Transfer tepat sesuai total nominal <strong class="text-emerald-400 font-mono">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong> (tidak ada biaya tambahan).</li>
                                <li>Setelah transfer berhasil, pastikan Anda men-screenshot atau memfoto bukti pembayaran yang sah.</li>
                                <li>Unggah bukti tersebut di kolom unggah di sebelah kanan untuk mempercepat verifikasi oleh Admin.</li>
                            </ul>
                        </div>
                    </div>

                    <!-- Detail Alamat & Produk -->
                    <div class="bg-[#1C2541]/50 border border-gray-800 rounded-2xl p-6 shadow-xl backdrop-blur-md grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Rincian Pengiriman</h4>
                            <div class="bg-gray-900/40 rounded-xl p-4 border border-gray-800 text-sm space-y-2">
                                <div>
                                    <span class="text-xs text-gray-500 block">Penerima:</span>
                                    <span class="font-bold text-white">{{ $order->nama }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Email:</span>
                                    <span class="text-gray-300 text-xs">{{ $order->email }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Ekspedisi:</span>
                                    <span class="inline-flex items-center px-2 py-0.5 bg-indigo-500/10 text-indigo-400 text-xs font-bold rounded-md border border-indigo-500/20 mt-0.5">{{ $order->ekspedisi }}</span>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Alamat:</span>
                                    <span class="text-gray-300 text-xs leading-relaxed">{{ $order->alamat }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <h4 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Produk Yang Dibeli</h4>
                            @if($product)
                                <div class="bg-gray-900/40 rounded-xl p-4 border border-gray-800 flex gap-4">
                                    <div class="w-16 h-16 bg-gray-950 border border-gray-800 rounded-lg overflow-hidden shrink-0 flex items-center justify-center">
                                        @if($product->foto)
                                            <img src="{{ asset('storage/products/' . $product->foto) }}" alt="{{ $product->nama_produk }}" class="w-full h-full object-cover">
                                        @else
                                            <span class="text-2xl">📦</span>
                                        @endif
                                    </div>
                                    <div class="space-y-1">
                                        <h5 class="text-sm font-bold text-white line-clamp-1">{{ $product->nama_produk }}</h5>
                                        <p class="text-xs text-gray-500">Harga Satuan:</p>
                                        <p class="text-sm font-bold text-emerald-400 font-mono">Rp {{ number_format($product->harga, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- SISI KANAN: FORM UPLOAD BUKTI -->
                <div class="space-y-6">
                    <div class="bg-[#1C2541]/80 border border-[#00B4D8]/20 rounded-2xl p-6 shadow-2xl space-y-6">
                        <div>
                            <h3 class="text-sm font-bold text-white tracking-widest uppercase mb-4 pb-2 border-b border-gray-800">Upload Bukti Transfer</h3>
                            
                            <form action="{{ route('orders.pay', $order->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                
                                <div class="space-y-2">
                                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Foto / Gambar Bukti</label>
                                    
                                    <!-- File upload dropzone-like style -->
                                    <div class="relative group border border-dashed border-gray-800 hover:border-[#00B4D8] rounded-xl p-4 bg-gray-950/50 text-center transition duration-300">
                                        <input type="file" name="bukti_transfer" required accept="image/*" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" onchange="previewImage(this)">
                                        <div class="space-y-2">
                                            <div class="text-2xl text-gray-500 group-hover:text-[#00B4D8] transition">📸</div>
                                            <p id="upload-placeholder-text" class="text-xs text-gray-400 font-bold">Pilih file atau seret gambar ke sini</p>
                                            <p class="text-[10px] text-gray-600">JPG, PNG, WebP (Maks. 2MB)</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Image Preview Container -->
                                <div id="preview-container" class="hidden space-y-2 border border-gray-800 rounded-xl p-2 bg-gray-900/40">
                                    <p class="text-[10px] text-gray-500 uppercase tracking-wider font-bold">Pratinjau Bukti:</p>
                                    <img id="image-preview" src="#" alt="Pratinjau" class="max-w-full h-40 object-contain rounded-lg mx-auto">
                                </div>

                                <button type="submit" class="w-full text-center py-3.5 rounded-xl bg-gradient-to-r from-[#00B4D8] to-[#0077B6] text-black font-black text-sm hover:opacity-90 transition duration-300 shadow-lg mt-2 uppercase tracking-wider">
                                    🔒 Kirim Bukti Transfer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- Script Clipboard and Preview -->
    <script>
        function copyText(id) {
            var text = document.getElementById(id).innerText;
            // Clean up RP formatting for simple numeric copy if it's price
            if (id === 'rekening-num') {
                text = text.replace(/[^0-9]/g, '');
            }
            navigator.clipboard.writeText(text).then(function() {
                var btn = document.getElementById(id + '-btn');
                var originalText = btn.innerHTML;
                btn.innerHTML = '✅ Disalin!';
                setTimeout(function() {
                    btn.innerHTML = originalText;
                }, 2000);
            }).catch(function(err) {
                console.error('Gagal menyalin: ', err);
            });
        }

        function previewImage(input) {
            var file = input.files[0];
            if (file) {
                // Update placeholder text with file name
                document.getElementById('upload-placeholder-text').innerText = file.name;
                
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('image-preview').src = e.target.result;
                    document.getElementById('preview-container').classList.remove('hidden');
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
</x-app-layout>
