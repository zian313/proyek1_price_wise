<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white">Lupa Password?</h2>
            <p class="mt-2 text-sm text-slate-400">Tidak masalah. Cukup beritahu kami alamat email Anda dan kami akan
                mengizinkan Anda untuk mengatur ulang kata sandi baru.</p>
        </div>

        <x-auth-session-status
            class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-sm text-emerald-200"
            :status="session('status')" />

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" class="text-slate-200" />
                <x-text-input id="email"
                    class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500"
                    type="email" name="email" :value="old('email')" required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div class="flex items-center justify-end mt-4 gap-4">
                <a href="{{ route('login') }}" class="text-sm text-teal-400 hover:text-teal-300">Kembali ke login</a>

                <x-primary-button class="bg-teal-600 hover:bg-teal-500 text-white">
                    {{ __('Cari Akun Saya') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>