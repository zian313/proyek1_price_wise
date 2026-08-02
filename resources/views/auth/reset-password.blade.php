<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white">Atur Ulang Sandi</h2>
            <p class="mt-2 text-sm text-slate-400">Akun ditemukan ({{ $email }}). Silakan buat kata sandi baru Anda di
                bawah ini.</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
            @csrf

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password Baru')" class="text-slate-200" />
                <x-text-input id="password"
                    class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500"
                    type="password" name="password" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-300" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Konfirmasi Password')" class="text-slate-200" />
                <x-text-input id="password_confirmation"
                    class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500"
                    type="password" name="password_confirmation" required autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-primary-button class="w-full bg-teal-600 hover:bg-teal-500 text-white">
                    {{ __('Simpan & Ganti Password') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>