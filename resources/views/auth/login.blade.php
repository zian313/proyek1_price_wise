<x-guest-layout>
    <div class="space-y-8">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white">Welcome back</h2>
            <p class="mt-2 text-sm text-slate-400">Masuk untuk melanjutkan ke akun Price Wise Anda.</p>
        </div>

        <x-auth-session-status class="rounded-2xl bg-emerald-500/10 border border-emerald-500/20 p-4 text-sm text-emerald-200" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-6">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Email')" class="text-slate-200" />
                <x-text-input id="email" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" class="text-slate-200" />
                <x-text-input id="password" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" type="password" name="password" required autocomplete="current-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm text-rose-300" />
            </div>

            <div class="flex items-center justify-between gap-4">
                <label for="remember_me" class="inline-flex items-center gap-2 text-sm text-slate-300">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-700 bg-slate-950 text-teal-500 shadow-sm focus:border-teal-500 focus:ring-teal-500" name="remember">
                    {{ __('ingatkan saya') }}
                </label>
        
            </div>

            <x-primary-button class="w-full bg-teal-600 hover:bg-teal-500 text-white">
                {{ __('Masuk') }}
            </x-primary-button>
        </form>
    </div>
</x-guest-layout>
