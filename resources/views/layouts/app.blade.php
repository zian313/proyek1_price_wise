<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Price Wise') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&display=swap"
        rel="stylesheet">

    <!-- Scripts / Fallback Tailwind & Alpine -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @endif

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="font-sans antialiased bg-slate-950 text-slate-100 min-h-screen relative overflow-x-hidden">
    <!-- Glowing Ambient Background Effects -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-teal-600/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-[30%] -right-40 w-96 h-96 bg-indigo-600/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-emerald-600/5 rounded-full blur-3xl pointer-events-none">
    </div>

    <div class="min-h-screen relative z-10">
        @include('layouts.navigation')

        <!-- Page Heading -->
        @isset($header)
            <header class="bg-slate-900/40 border-b border-slate-900 shadow-lg shadow-slate-950/20 backdrop-blur-xl">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @stack('scripts')
    <script>
        // Global Helper for Form Confirmations
        function confirmSubmit(event, message, confirmText = 'Ya, Lanjutkan', icon = 'warning', confirmColor = '#4f46e5') {
            event.preventDefault();
            const form = event.target.closest('form') || event.target;
            Swal.fire({
                title: 'Konfirmasi',
                text: message,
                icon: icon,
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: 'Batal',
                background: '#1e293b',
                color: '#f8fafc',
                confirmButtonColor: confirmColor,
                cancelButtonColor: '#475569',
                customClass: {
                    popup: 'rounded-2xl border border-slate-700 shadow-2xl',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        }
    </script>
</body>

</html>