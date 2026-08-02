<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white">Buat akun baru</h2>
            <p class="mt-2 text-sm text-slate-400">Daftar untuk mulai membeli atau menjual barang bekas di Price Wise.</p>
        </div>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="name" :value="__('Name (Username)')" class="text-slate-200" />
                <x-text-input id="name" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-slate-200" />
                <x-text-input id="email" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-slate-200" />
                <x-text-input id="password" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" class="text-slate-200" />
                <x-text-input id="password_confirmation" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div>
                <x-input-label for="role" :value="__('Mendaftar Sebagai:')" class="text-slate-200" />
                <select id="role" name="role" onchange="toggleSellerFields()" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 px-4 py-3 text-white focus:border-teal-500 focus:ring-teal-500" required>
                    <option value="buyer" {{ old('role') === 'buyer' ? 'selected' : '' }}>Pembeli (Buyer)</option>
                    <option value="seller" {{ old('role') === 'seller' ? 'selected' : '' }}>Penjual (Seller)</option>
                </select>
                <x-input-error :messages="$errors->get('role')" class="mt-2 text-sm text-rose-300" />
            </div>

            <!-- Seller Specific Form Fields -->
            <div id="seller_fields" class="space-y-6 hidden p-4 rounded-xl bg-slate-900/60 border border-teal-500/30">
                <div class="border-b border-slate-800 pb-2">
                    <h3 class="text-sm font-bold text-teal-400 uppercase tracking-wider">Verifikasi Data Identitas Seller</h3>
                    <p class="text-xs text-slate-400 mt-0.5">Wajib dilengkapi untuk proses pengajuan persetujuan toko oleh Admin.</p>
                </div>

                <div>
                    <x-input-label for="nama_ktp" :value="__('Nama Sesuai KTP')" class="text-slate-200" />
                    <x-text-input id="nama_ktp" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" type="text" name="nama_ktp" :value="old('nama_ktp')" placeholder="Masukkan nama persis seperti di KTP" />
                    <x-input-error :messages="$errors->get('nama_ktp')" class="mt-2 text-sm text-rose-300" />
                </div>

                <div>
                    <x-input-label for="alamat_lengkap" :value="__('Alamat Lengkap Toko / Rumah Detail')" class="text-slate-200" />
                    <textarea id="alamat_lengkap" name="alamat_lengkap" rows="3" class="mt-1 block w-full rounded-xl border border-slate-700 bg-slate-950 p-3 text-white focus:border-teal-500 focus:ring-teal-500 text-sm" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten, Kode Pos">{{ old('alamat_lengkap') }}</textarea>
                    <x-input-error :messages="$errors->get('alamat_lengkap')" class="mt-2 text-sm text-rose-300" />
                </div>

                <div>
                    <x-input-label for="foto_ktp" :value="__('Upload Foto KTP')" class="text-slate-200" />
                    <input id="foto_ktp" name="foto_ktp" type="file" accept="image/*" class="mt-1 block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-950 file:text-teal-300 hover:file:bg-teal-900 border border-slate-700 rounded-xl cursor-pointer bg-slate-950" />
                    <x-input-error :messages="$errors->get('foto_ktp')" class="mt-2 text-sm text-rose-300" />
                </div>

                <div>
                    <x-input-label for="selfie_ktp" :value="__('Upload Selfie Memegang KTP')" class="text-slate-200" />
                    <input id="selfie_ktp" name="selfie_ktp" type="file" accept="image/*" class="mt-1 block w-full text-xs text-slate-400 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-teal-950 file:text-teal-300 hover:file:bg-teal-900 border border-slate-700 rounded-xl cursor-pointer bg-slate-950" />
                    <x-input-error :messages="$errors->get('selfie_ktp')" class="mt-2 text-sm text-rose-300" />
                </div>
            </div>

            <script>
                function toggleSellerFields() {
                    const role = document.getElementById('role').value;
                    const sellerFields = document.getElementById('seller_fields');
                    if (role === 'seller') {
                        sellerFields.classList.remove('hidden');
                    } else {
                        sellerFields.classList.add('hidden');
                    }
                }
                document.addEventListener('DOMContentLoaded', toggleSellerFields);
            </script>

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <a class="text-sm text-teal-300 hover:text-teal-100 transition" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>

                <x-primary-button class="w-full sm:w-auto bg-teal-600 hover:bg-teal-500 text-white">
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
