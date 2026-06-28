<section>
    <header>
        <h2 class="text-lg font-bold text-slate-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-slate-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <x-input-label for="name" :value="__('Name')" class="text-slate-100" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2 text-sm text-rose-300" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" class="text-slate-100" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2 text-sm text-rose-300" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div>
                    <p class="text-sm mt-2 text-slate-300">
                        {{ __('Your email address is unverified.') }}

                        <button form="send-verification" class="underline text-sm text-teal-300 hover:text-teal-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-teal-500">
                            {{ __('Click here to re-send the verification email.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-emerald-400">
                            {{ __('A new verification link has been sent to your email address.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        @if ($user->role === 'seller')
            <div class="border-t border-slate-800 pt-6 space-y-6">
                <h3 class="text-sm font-bold text-teal-400 uppercase tracking-wider">Informasi Rekening M-Banking (Penjual)</h3>
                <p class="text-xs text-slate-400">Informasi ini akan ditampilkan kepada pembeli untuk melakukan transfer pembayaran atas produk Anda.</p>

                <div>
                    <x-input-label for="bank_name" :value="__('Nama Bank')" class="text-slate-100" />
                    <select id="bank_name" name="bank_name" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500 rounded-md shadow-sm border px-3 py-2">
                        <option value="" {{ old('bank_name', $user->bank_name) === '' ? 'selected' : '' }}>-- Pilih Bank --</option>
                        <option value="M-Banking BCA" {{ old('bank_name', $user->bank_name) === 'M-Banking BCA' ? 'selected' : '' }}>M-Banking BCA</option>
                        <option value="M-Banking Mandiri" {{ old('bank_name', $user->bank_name) === 'M-Banking Mandiri' ? 'selected' : '' }}>M-Banking Mandiri</option>
                        <option value="M-Banking BNI" {{ old('bank_name', $user->bank_name) === 'M-Banking BNI' ? 'selected' : '' }}>M-Banking BNI</option>
                        <option value="M-Banking BRI" {{ old('bank_name', $user->bank_name) === 'M-Banking BRI' ? 'selected' : '' }}>M-Banking BRI</option>
                    </select>
                    <x-input-error class="mt-2 text-sm text-rose-300" :messages="$errors->get('bank_name')" />
                </div>

                <div>
                    <x-input-label for="no_rekening" :value="__('Nomor Rekening')" class="text-slate-100" />
                    <x-text-input id="no_rekening" name="no_rekening" type="text" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" :value="old('no_rekening', $user->no_rekening)" placeholder="Contoh: 7140928122" />
                    <x-input-error class="mt-2 text-sm text-rose-300" :messages="$errors->get('no_rekening')" />
                </div>

                <div>
                    <x-input-label for="atas_nama" :value="__('Atas Nama Pemilik Rekening')" class="text-slate-100" />
                    <x-text-input id="atas_nama" name="atas_nama" type="text" class="mt-1 block w-full bg-slate-950 text-white border-slate-700 focus:border-teal-500 focus:ring-teal-500" :value="old('atas_nama', $user->atas_nama)" placeholder="Contoh: Budi Santoso" />
                    <x-input-error class="mt-2 text-sm text-rose-300" :messages="$errors->get('atas_nama')" />
                </div>
            </div>
        @endif

        <div class="flex items-center gap-4">
            <x-primary-button class="bg-teal-600 hover:bg-teal-500">{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-slate-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>
