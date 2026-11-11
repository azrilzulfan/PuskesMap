<x-layout title="Login - PuskesMap" body-class="min-h-screen bg-white p-4 lg:p-6">
    <main class="mx-auto grid min-h-[calc(100vh-2rem)] max-w-[1435px] overflow-hidden rounded-xl bg-white lg:min-h-[calc(100vh-3rem)] lg:grid-cols-2">
        {{-- Panel ilustrasi --}}
        <aside class="relative hidden overflow-hidden rounded-xl bg-surface-mint lg:block">
            <img src="{{ asset('images/login-bg.png') }}" alt="Ilustrasi Tugu Kujang, Kota Bogor"
                 class="absolute inset-0 h-full w-full object-cover object-bottom">
            <a href="{{ url('/') }}" class="absolute left-8 top-8 flex items-center gap-2" aria-label="PuskesMap, kembali ke beranda">
                <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-6 w-auto">
                <span class="text-xl font-bold text-primary-dark">PuskesMap</span>
            </a>
        </aside>

        {{-- Form --}}
        <section class="flex flex-col justify-center px-6 py-12 sm:px-16">
            <a href="{{ url('/') }}" class="mb-10 flex items-center gap-2 lg:hidden" aria-label="PuskesMap, kembali ke beranda">
                <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-6 w-auto">
                <span class="text-xl font-bold text-primary-dark">PuskesMap</span>
            </a>

            <h1 class="text-center text-4xl font-semibold leading-tight text-ink lg:text-5xl">Login</h1>

            <form action="#" method="POST" class="mx-auto mt-12 w-full max-w-[600px] space-y-6">
                @csrf
                <div>
                    <label for="email" class="text-sm font-medium text-ink">Email</label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           placeholder="nama@email.com" value="{{ old('email') }}"
                           class="mt-2 h-16 w-full rounded-md border-0 bg-input pl-9 pr-4 text-base text-ink placeholder:text-placeholder focus:ring-2 focus:ring-primary">
                </div>

                <div>
                    <label for="password" class="text-sm font-medium text-ink">Password</label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           placeholder="Masukkan password"
                           class="mt-2 h-16 w-full rounded-md border-0 bg-input pl-9 pr-4 text-base text-ink placeholder:text-placeholder focus:ring-2 focus:ring-primary">
                </div>

                <div class="flex justify-center pt-4">
                    <x-button type="submit" variant="accent" class="h-[45px] w-[296px] text-xl font-medium">Log In</x-button>
                </div>
            </form>
        </section>
    </main>
</x-layout>
