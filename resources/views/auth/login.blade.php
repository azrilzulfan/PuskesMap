<x-layout title="Login - PuskesMap" body-class="min-h-dvh bg-white font-sans p-4 lg:p-6 text-ink flex flex-col justify-center">
    <main class="mx-auto flex w-full max-w-[1680px] flex-1 lg:flex-none flex-col justify-center lg:h-[calc(100dvh-3rem)] lg:max-h-[920px] lg:min-h-[540px] lg:flex-row lg:items-stretch">
        {{-- Bagian Kiri: Card Ilustrasi (Mengisi penuh tinggi viewport desktop dengan proporsi seimbang) --}}
        <aside class="relative hidden overflow-hidden rounded-xl bg-surface-mint lg:block lg:h-full lg:w-[38%] lg:max-w-[560px] lg:shrink-0">
            <img
                src="{{ asset('images/login-bg.png') }}"
                alt="Ilustrasi Tugu Kujang, Kota Bogor"
                class="absolute inset-0 h-full w-full object-cover object-bottom"
            >
        </aside>

        {{-- Bagian Kanan: Logo, Judul Login & Form --}}
        <section class="flex flex-1 min-w-0 flex-col items-center justify-center bg-white px-2 py-6 sm:px-10 sm:py-12 lg:px-14">
            <div class="w-full max-w-md lg:max-w-lg">
                {{-- Header: Logo di Atas Sebelum Form & Teks Login Rata Tengah --}}
                <div class="text-center">
                    <a href="{{ route('home') }}" class="inline-block focus:outline-none" aria-label="Kembali ke Beranda PuskesMap">
                        <img
                            src="{{ asset('images/logo-sm.png') }}"
                            alt="Logo PuskesMap"
                            class="mx-auto mb-3 h-14 w-auto object-contain sm:mb-4 sm:h-16"
                        >
                    </a>
                    <h1 class="font-sans text-center text-3xl font-bold tracking-tight leading-tight text-ink sm:text-4xl lg:text-5xl">
                        Login
                    </h1>
                </div>

                {{-- Form Login --}}
                <form action="{{ route('login') }}" method="POST" class="mt-8 space-y-5 sm:mt-10 sm:space-y-6">
                    @csrf

                    {{-- Input Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-ink">
                            Email
                        </label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            autocomplete="email"
                            required
                            value="{{ old('email') }}"
                            placeholder="Masukkan email admin..."
                            class="mt-2 h-13 sm:h-15 lg:h-16 w-full rounded-md border border-gray-200 bg-input pl-5 pr-4 sm:pl-8 lg:pl-9 text-sm sm:text-base text-ink placeholder:text-placeholder transition-colors focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                        >
                        @error('email')
                            <p class="mt-1.5 text-xs font-medium text-accent-strong">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Input Password dengan Toggle Show/Hide (Alpine.js) --}}
                    <div x-data="{ showPassword: false }">
                        <label for="password" class="block text-sm font-medium text-ink">
                            Password
                        </label>
                        <div class="relative mt-2">
                            <input
                                id="password"
                                name="password"
                                :type="showPassword ? 'text' : 'password'"
                                type="password"
                                autocomplete="current-password"
                                required
                                placeholder="Masukkan kata sandi..."
                                class="h-13 sm:h-15 lg:h-16 w-full rounded-md border border-gray-200 bg-input pl-5 pr-12 sm:pl-8 sm:pr-14 lg:pl-9 text-sm sm:text-base text-ink placeholder:text-placeholder transition-colors focus:bg-white focus:border-primary focus:ring-2 focus:ring-primary focus:outline-none"
                            >
                            <button
                                type="button"
                                @click="showPassword = !showPassword"
                                :aria-label="showPassword ? 'Sembunyikan kata sandi' : 'Tampilkan kata sandi'"
                                :aria-pressed="showPassword.toString()"
                                class="absolute inset-y-0 right-0 flex items-center justify-center px-3.5 sm:px-4 text-muted hover:text-primary transition-colors cursor-pointer focus:outline-none"
                            >
                                {{-- Ikon Mata Terbuka (Tampil saat password disembunyikan) --}}
                                <svg x-show="!showPassword" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>

                                {{-- Ikon Mata Dicoret (Tampil saat password terlihat) --}}
                                <svg x-show="showPassword" style="display: none;" class="size-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="mt-1.5 text-xs font-medium text-accent-strong">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol Submit Menggunakan Komponen Blade (Warna Primary #007979) --}}
                    <div class="pt-3 sm:pt-4">
                        <x-button variant="accent" type="submit" class="w-full h-12 lg:h-13">Log In</x-button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</x-layout>
