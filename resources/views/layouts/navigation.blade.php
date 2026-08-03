<nav x-data="{ open: false }"
    class="bg-slate-900/80 border-b border-slate-800 backdrop-blur sticky top-0 z-50 shadow-md shadow-slate-950/15">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div
                            class="w-8 h-8 bg-teal-600 rounded-lg flex items-center justify-center text-white shadow shadow-teal-600/20 group-hover:scale-105 transition duration-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                            </svg>
                        </div>
                        <span
                            class="font-black text-lg tracking-tight bg-gradient-to-r from-teal-400 to-emerald-400 bg-clip-text text-transparent">Price
                            Wise</span>
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                        class="text-slate-400 hover:text-white transition">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'seller')
                        <x-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')"
                            class="text-slate-400 hover:text-white transition">
                            {{ __('Kelola Kategori') }}
                        </x-nav-link>
                        <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')"
                            class="text-slate-400 hover:text-white transition">
                            {{ __('Kelola Produk') }}
                        </x-nav-link>
                        <x-nav-link :href="route('seller.orders')" :active="request()->routeIs('seller.orders')"
                            class="text-slate-400 hover:text-white transition relative">
                            {{ __('Pesanan Masuk') }}
                            @php
                                $newOrdersCount = \App\Models\OrderDetail::whereHas('product', function($q) {
                                    $q->where('user_id', Auth::id());
                                })->whereHas('order', function($q) {
                                    $q->whereIn('status', ['menunggu_pembayaran', 'menunggu_verifikasi', 'lunas'])->where('barang_dikirim', false);
                                })->count();
                            @endphp
                            @if($newOrdersCount > 0)
                                <span class="absolute top-4 -right-2 flex h-2 w-2">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-2 w-2 bg-rose-500"></span>
                                </span>
                            @endif
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'buyer')
                        <x-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')"
                            class="text-slate-400 hover:text-white transition">
                            {{ __('Riwayat Pembelian') }}
                        </x-nav-link>
                    @elseif(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.sellers')" :active="request()->routeIs('admin.sellers')"
                            class="text-slate-400 hover:text-white transition">
                            {{ __('Verifikasi Seller') }}
                        </x-nav-link>
                        <x-nav-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')"
                            class="text-slate-400 hover:text-white transition">
                            {{ __('Kelola Order') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48" contentClasses="py-1 bg-slate-900 border border-slate-800">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-lg text-slate-400 bg-slate-900/60 hover:text-white focus:outline-none transition ease-in-out duration-150">
                            <div>{{ Auth::user()->name }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')" class="text-slate-300 hover:bg-slate-800">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')" class="text-slate-300 hover:bg-slate-800" onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-xl text-slate-400 hover:text-white hover:bg-slate-800 focus:outline-none focus:bg-slate-800 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-slate-900 border-b border-slate-800">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')"
                class="text-slate-400 hover:text-white hover:bg-slate-800">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'seller')
                <x-responsive-nav-link :href="route('categories.index')" :active="request()->routeIs('categories.*')"
                    class="text-slate-400 hover:text-white hover:bg-slate-800">
                    {{ __('Kelola Kategori') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')"
                    class="text-slate-400 hover:text-white hover:bg-slate-800">
                    {{ __('Kelola Produk') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('seller.orders')" :active="request()->routeIs('seller.orders')"
                    class="text-slate-400 hover:text-white hover:bg-slate-800 flex items-center justify-between">
                    {{ __('Pesanan Masuk') }}
                    @if($newOrdersCount > 0)
                        <span class="flex h-2.5 w-2.5 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-rose-500"></span>
                        </span>
                    @endif
                </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'buyer')
                <x-responsive-nav-link :href="route('orders.history')" :active="request()->routeIs('orders.history')"
                    class="text-slate-400 hover:text-white hover:bg-slate-800">
                    {{ __('Riwayat Pembelian') }}
                </x-responsive-nav-link>
            @elseif(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.sellers')" :active="request()->routeIs('admin.sellers')"
                    class="text-slate-400 hover:text-white hover:bg-slate-800">
                    {{ __('Verifikasi Seller') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.orders')" :active="request()->routeIs('admin.orders')"
                    class="text-slate-400 hover:text-white hover:bg-slate-800">
                    {{ __('Kelola Order') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-slate-800">
            <div class="px-4">
                <div class="font-medium text-base text-slate-200">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-400">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')"
                    class="text-slate-400 hover:text-white hover:bg-slate-800">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                        class="text-slate-400 hover:text-white hover:bg-slate-800" onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>