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

            <!-- SECTION: PERSETUJUAN VERIFIKASI AKUN SELLER -->
            <div
                class="mb-10 bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div
                    class="p-6 border-b border-gray-100 dark:border-gray-700 bg-amber-500/10 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white flex items-center gap-2">
                            <span>🛡️ Pengajuan Verifikasi Seller</span>
                            @if(count($pendingSellers) > 0)
                                <span
                                    class="px-2.5 py-0.5 rounded-full text-xs font-black bg-amber-500 text-black animate-pulse">
                                    {{ count($pendingSellers) }} Pending
                                </span>
                            @endif
                        </h3>
                        <p class="text-xs text-gray-400 mt-1">Periksa data identitas KTP, foto selfie, dan alamat
                            lengkap toko seller sebelum memberikan persetujuan.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr
                                class="border-b border-gray-100 dark:border-gray-700 bg-gray-50/30 dark:bg-gray-900/30 text-xs font-bold text-gray-400 uppercase tracking-wider">
                                <th class="px-6 py-4">Seller & Email</th>
                                <th class="px-6 py-4">Nama KTP</th>
                                <th class="px-6 py-4">Alamat Lengkap Toko/Rumah</th>
                                <th class="px-6 py-4">Dokumen KTP</th>
                                <th class="px-6 py-4">Status</th>
                                <th class="px-6 py-4 text-center">Aksi Verifikasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                            @forelse($pendingSellers as $seller)
                                <tr
                                    class="hover:bg-gray-50/50 dark:hover:bg-gray-900/20 text-sm text-gray-700 dark:text-gray-300 transition duration-150">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-gray-800 dark:text-white">{{ $seller->name }}</div>
                                        <div class="text-xs text-gray-400 font-mono">{{ $seller->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 font-semibold text-teal-600 dark:text-teal-400">
                                        {{ $seller->nama_ktp ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4 text-xs max-w-xs leading-relaxed text-gray-300">
                                        {{ $seller->alamat_lengkap ?? '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col gap-1.5">
                                            @if($seller->foto_ktp)
                                                <a href="{{ asset('storage/seller_docs/' . $seller->foto_ktp) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center text-xs text-indigo-400 hover:underline gap-1">
                                                    📄 Lihat Foto KTP
                                                </a>
                                            @endif
                                            @if($seller->selfie_ktp)
                                                <a href="{{ asset('storage/seller_docs/' . $seller->selfie_ktp) }}"
                                                    target="_blank"
                                                    class="inline-flex items-center text-xs text-emerald-400 hover:underline gap-1">
                                                    📸 Lihat Selfie + KTP
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-amber-500/20 text-amber-300 gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-amber-400 rounded-full animate-pulse"></span>
                                            Menunggu Approval
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <div class="flex justify-center items-center gap-2">
                                            <form action="{{ route('admin.seller.approve', $seller->id) }}" method="POST"
                                                class="inline"
                                                onsubmit="confirmSubmit(event, 'Apakah Anda yakin ingin menyetujui seller ini?', 'Ya, Setujui', 'info', '#10b981')">
                                                @csrf
                                                <button type="submit"
                                                    class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-500 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                                    Setujui (Approve)
                                                </button>
                                            </form>
                                            <form action="{{ route('admin.seller.reject', $seller->id) }}" method="POST"
                                                id="form-reject-seller-{{ $seller->id }}">
                                                @csrf
                                                <input type="hidden" name="rejection_reason"
                                                    id="reason-seller-{{ $seller->id }}" value="">
                                                <button type="button" onclick="confirmRejectSeller({{ $seller->id }})"
                                                    class="px-3 py-1.5 bg-rose-600 hover:bg-rose-500 text-white rounded-lg text-xs font-bold transition shadow-sm">
                                                    Tolak
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-gray-400 text-xs">
                                        Tidak ada pendaftaran seller yang sedang menunggu persetujuan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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