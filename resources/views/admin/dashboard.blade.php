<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard Admin') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50 dark:bg-gray-900 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Alert Success -->
            @if(session('success'))
                <div
                    class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-900/30 border-l-4 border-emerald-500 rounded-xl text-emerald-800 dark:text-emerald-300 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            <!-- Alert Error -->
            @if(session('error'))
                <div
                    class="mb-6 p-4 bg-rose-50 dark:bg-rose-900/30 border-l-4 border-rose-500 rounded-xl text-rose-800 dark:text-rose-300 shadow-sm flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <span>{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Dashboard Overview -->
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 p-8 text-center">
                <div class="w-16 h-16 bg-teal-100 dark:bg-teal-900/30 text-teal-600 dark:text-teal-400 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
                <h3 class="text-2xl font-black text-gray-800 dark:text-white mb-2">Selamat Datang di Admin Panel</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-lg mx-auto mb-6">Anda dapat memverifikasi seller baru dan mengelola seluruh transaksi, komplain, serta penarikan dana melalui menu navigasi di atas.</p>
                <div class="flex items-center justify-center gap-4">
                    <a href="{{ route('admin.sellers') }}" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-sm font-bold transition shadow-md shadow-indigo-600/20">
                        🛡️ Verifikasi Seller
                    </a>
                    <a href="{{ route('admin.orders') }}" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-sm font-bold transition shadow-md shadow-teal-600/20">
                        📦 Kelola Order
                    </a>
                </div>
            </div>

        </div>
    </div>
    @push('scripts')
        <script>
            function confirmRejectSeller(id) {
                Swal.fire({
                    title: 'Tolak Seller',
                    text: 'Masukkan alasan penolakan seller ini:',
                    input: 'text',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Tolak Seller',
                    cancelButtonText: 'Batal',
                    background: '#1e293b',
                    color: '#f8fafc',
                    confirmButtonColor: '#e11d48',
                    cancelButtonColor: '#475569',
                    inputValidator: (value) => {
                        if (!value || value.trim() === "") {
                            return 'Alasan penolakan wajib diisi!'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('reason-seller-' + id).value = result.value;
                        document.getElementById('form-reject-seller-' + id).submit();
                    }
                });
            }
        </script>
    @endpush
</x-app-layout>