<!DOCTYPE html>
<html lang="en" class="h-full bg-[#0B132B]">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Price Wise - Premium Marketplace</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    animation: {
                        'blob': 'blob 7s infinite',
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        blob: {
                            '0%': { transform: 'translate(0px, 0px) scale(1)' },
                            '33%': { transform: 'translate(30px, -50px) scale(1.1)' },
                            '66%': { transform: 'translate(-20px, 20px) scale(0.9)' },
                            '100%': { transform: 'translate(0px, 0px) scale(1)' }
                        },
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .animate-delay-100 {
            animation-delay: 100ms;
        }

        .animate-delay-200 {
            animation-delay: 200ms;
        }

        .animate-delay-300 {
            animation-delay: 300ms;
        }
    </style>
</head>

<body
    class="h-full flex flex-col justify-between text-white font-sans selection:bg-[#00B4D8] relative overflow-hidden bg-slate-950">

    <!-- Ambient Animated Background Blobs -->
    <div class="absolute inset-0 z-0 overflow-hidden pointer-events-none">
        <div
            class="absolute top-0 left-1/4 w-72 h-72 bg-purple-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob">
        </div>
        <div
            class="absolute top-0 right-1/4 w-72 h-72 bg-[#00B4D8] rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob animate-delay-100">
        </div>
        <div
            class="absolute -bottom-8 left-1/3 w-72 h-72 bg-emerald-600 rounded-full mix-blend-multiply filter blur-[100px] opacity-40 animate-blob animate-delay-200">
        </div>
    </div>

    <!-- Navbar Minimalis -->
    <header
        class="relative z-10 p-6 flex justify-between items-center max-w-7xl w-full mx-auto opacity-0 animate-fade-in-up">
        <div class="flex items-center space-x-3 cursor-pointer group">
            <!-- Logo Toko Kamu -->
            <div
                class="bg-gradient-to-tr from-[#00B4D8] to-cyan-300 p-2.5 rounded-xl shadow-[0_0_20px_rgba(0,180,216,0.5)] transform group-hover:scale-110 group-hover:rotate-3 transition duration-300">
                <svg class="w-6 h-6 text-slate-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <span
                class="text-2xl font-extrabold tracking-tight bg-gradient-to-r from-white via-gray-200 to-gray-500 bg-clip-text text-transparent group-hover:from-[#00B4D8] group-hover:to-white transition-all duration-300">
                PriceWise.
            </span>
        </div>
    </header>

    <!-- Konten Utama Tengah (Hanya Teks & Tombol Auth) -->
    <main
        class="relative z-10 flex-1 flex flex-col items-center justify-center px-4 text-center max-w-3xl mx-auto mt-[-5vh]">
        <h1
            class="text-5xl md:text-7xl font-black tracking-tighter mb-6 leading-[1.1] opacity-0 animate-fade-in-up animate-delay-100">
            Jual Beli Barang Bekas <br>
            <span
                class="bg-gradient-to-r from-[#00B4D8] via-indigo-400 to-[#7209B7] bg-clip-text text-transparent opacity-90 drop-shadow-sm">
                Lebih Hidup & Aman
            </span>
        </h1>

        <p
            class="text-slate-300 text-lg md:text-xl mb-12 leading-relaxed max-w-2xl mx-auto font-medium opacity-0 animate-fade-in-up animate-delay-200">
            PriceWise adalah marketplace revolusioner yang menghubungkan penjual dan pembeli. Dilengkapi pembayaran
            <strong class="text-indigo-400">Rekening Bersama otomatis</strong> untuk transaksi 100% sangat aman tanpa
            kendala.
        </p>

        <!-- Pintu Masuk / Daftar -->
        <div class="flex flex-col sm:flex-row gap-5 w-full sm:w-auto opacity-0 animate-fade-in-up animate-delay-300">
            <a href="{{ route('login') }}"
                class="group relative px-8 py-4 rounded-2xl bg-gradient-to-r from-[#00B4D8] to-cyan-500 text-slate-900 font-extrabold text-lg transition-all duration-300 hover:scale-105 hover:shadow-[0_0_30px_rgba(0,180,216,0.4)] isolation-auto z-10 overflow-hidden">
                <span class="relative z-10">Mulai Berselancar</span>
                <div
                    class="absolute inset-0 w-full h-full bg-white/20 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
                </div>
            </a>
            <a href="{{ route('register') }}"
                class="px-8 py-4 rounded-2xl bg-slate-900 border-2 border-slate-700/50 text-white font-extrabold text-lg hover:bg-slate-800 hover:border-slate-600 transition-all duration-300 hover:scale-105 hover:shadow-xl shadow-black/50">
                Buat Akun Gratis
            </a>
        </div>
    </main>

    <footer class="relative z-10 py-6 text-center text-slate-500 text-sm font-medium">
        &copy; 2026 PriceWise Platform. All rights reserved.
    </footer>
</body>

</html>