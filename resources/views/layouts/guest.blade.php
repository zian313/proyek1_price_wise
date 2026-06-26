<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Price Wise') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @if (file_exists(public_path('build/manifest.json')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            tailwind.config = {
                darkMode: 'class',
            }
        </script>
    @endif

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="font-sans text-white antialiased bg-blue-950 min-h-screen relative overflow-hidden">

    <!-- Blue Ambient Glow -->
    <div class="absolute -top-40 -left-40 w-96 h-96 bg-blue-500/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -right-40 w-96 h-96 bg-sky-500/30 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-40 left-1/4 w-96 h-96 bg-indigo-500/30 rounded-full blur-3xl pointer-events-none"></div>

    <div class="min-h-screen flex flex-col justify-center items-center px-4 py-8 relative z-10">

        <!-- Logo -->
        <div class="mb-6">
            <a href="/" class="flex items-center gap-2 group">
                <div class="w-12 h-12 bg-blue-600 rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-600/30 group-hover:scale-105 transition">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6">
                        </path>
                    </svg>
                </div>

                <span class="font-extrabold text-3xl tracking-tight bg-gradient-to-r from-blue-400 to-sky-300 bg-clip-text text-transparent">
                    Price Wise
                </span>
            </a>
        </div>

        <!-- Glass Card -->
        <div class="w-full sm:max-w-md mt-4 p-8 bg-blue-900/40 backdrop-blur-xl border border-blue-700 rounded-3xl shadow-2xl shadow-blue-900/50 relative overflow-hidden">
            {{ $slot }}
        </div>

    </div>

</body>
</html>