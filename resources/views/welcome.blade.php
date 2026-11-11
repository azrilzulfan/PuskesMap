@php
    $navLinks = [
        ['label' => 'Tentang', 'href' => '#wilayah'],
        ['label' => 'Fitur', 'href' => '#rekomendasi'],
        ['label' => 'Puskesmas', 'href' => '#cari'],
    ];

    $highlights = [
        [
            'title' => 'IGD 24 Jam',
            'desc' => 'Puskesmas yang melayani kegawatdaruratan sepanjang hari.',
            'img' => 'images/igd24jam.png',
            'badge' => 'Siaga Darurat',
        ],
        [
            'title' => 'Rawat Inap',
            'desc' => 'Puskesmas dengan layanan rawat inap & persalinan PONED.',
            'img' => 'images/rawat-inap.png',
            'badge' => 'Kamar Perawatan',
        ],
        [
            'title' => 'Buka Sekarang',
            'desc' => 'Puskesmas yang sedang beroperasi melayani masyarakat hari ini.',
            'img' => 'images/buka-sekarang.png',
            'badge' => 'Operasional Aktif',
        ],
    ];

    $boardNotices = [
        ['tag' => 'IGD 24 Jam', 'title' => '11 Puskesmas Induk Siaga Gawat Darurat 24 Jam', 'meta' => 'Kota Bogor', 'highlight' => true],
        ['tag' => 'Permenkes 43', 'title' => 'Evaluasi Kepatuhan 9 Profesi Tenaga Kesehatan Puskesmas', 'meta' => 'Standar Nasional', 'highlight' => false],
        ['tag' => 'Metode MAUT', 'title' => 'Rekomendasi Multikriteria: Jarak, IGD, Jam Buka & Keramaian', 'meta' => '5 Kriteria SPK', 'highlight' => false],
        ['tag' => 'Radius Spasial', 'title' => 'Filter Jangkauan Geodetik 1 km, 3 km, hingga 5 km dari Lokasimu', 'meta' => 'WebGIS Interaktif', 'highlight' => false],
        ['tag' => 'Analitik SDSS', 'title' => 'Pemetaan Indeks Beban Wilayah (PSW-Index) 6 Kecamatan', 'meta' => 'Dinas Kesehatan', 'highlight' => false],
    ];

    $districts = [
        ['name' => 'Semua', 'label' => 'Semua Wilayah', 'count' => '25 Unit', 'icon' => 'M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['name' => 'Tanah Sareal', 'label' => 'Tanah Sareal', 'count' => '5 Unit', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        ['name' => 'Bogor Tengah', 'label' => 'Bogor Tengah', 'count' => '5 Unit', 'icon' => 'M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.36 48.36 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z'],
        ['name' => 'Bogor Barat', 'label' => 'Bogor Barat', 'count' => '5 Unit', 'icon' => 'M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342'],
        ['name' => 'Bogor Selatan', 'label' => 'Bogor Selatan', 'count' => '5 Unit', 'icon' => 'M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25'],
        ['name' => 'Bogor Utara', 'label' => 'Bogor Utara', 'count' => '3 Unit', 'icon' => 'M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z'],
        ['name' => 'Bogor Timur', 'label' => 'Bogor Timur', 'count' => '2 Unit', 'icon' => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0z M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z'],
    ];

    $cardColors = ['bg-card-mint', 'bg-card-peach', 'bg-card-green', 'bg-card-salmon', 'bg-card-blue'];

    $puskesmas = [
        ['name' => 'Puskesmas Tanah Sareal', 'district' => 'Tanah Sareal', 'address' => 'Jl. Kesehatan No. 1, Tanah Sareal', 'distance' => '1,1 km', 'hours' => '07.30 – 16.00 WIB', 'igd' => true, 'inap' => true, 'nakes' => '100%', 'crowd' => 'Sedang'],
        ['name' => 'Puskesmas Sempur', 'district' => 'Bogor Tengah', 'address' => 'Jl. Sempur Kaler No. 12, Bogor Tengah', 'distance' => '1,2 km', 'hours' => '07.30 – 14.30 WIB', 'igd' => false, 'inap' => false, 'nakes' => '89%', 'crowd' => 'Sepi'],
        ['name' => 'Puskesmas Kedung Badak', 'district' => 'Tanah Sareal', 'address' => 'Jl. Kol. Enjo Martadisastra, Kedung Badak', 'distance' => '1,5 km', 'hours' => 'Buka 24 Jam (IGD)', 'igd' => true, 'inap' => false, 'nakes' => '100%', 'crowd' => 'Sedang'],
        ['name' => 'Puskesmas Mekarwangi', 'district' => 'Tanah Sareal', 'address' => 'Perum Bukit Cimanggu City, Mekarwangi', 'distance' => '2,0 km', 'hours' => 'Buka 24 Jam (IGD)', 'igd' => true, 'inap' => true, 'nakes' => '100%', 'crowd' => 'Ramai'],
        ['name' => 'Puskesmas Pasir Mulya', 'district' => 'Bogor Barat', 'address' => 'Jl. Gunung Batu No. 45, Pasir Mulya', 'distance' => '2,3 km', 'hours' => 'Buka 24 Jam (IGD)', 'igd' => true, 'inap' => true, 'nakes' => '100%', 'crowd' => 'Sepi'],
        ['name' => 'Puskesmas Bogor Timur', 'district' => 'Bogor Timur', 'address' => 'Jl. Pakuan No. 8, Baranangsiang', 'distance' => '2,4 km', 'hours' => 'Buka 24 Jam (IGD)', 'igd' => true, 'inap' => true, 'nakes' => '100%', 'crowd' => 'Sedang'],
        ['name' => 'Puskesmas Bogor Utara', 'district' => 'Bogor Utara', 'address' => 'Jl. Kresna Raya No. 14, Bantarjati', 'distance' => '2,6 km', 'hours' => 'Buka 24 Jam (IGD)', 'igd' => true, 'inap' => true, 'nakes' => '100%', 'crowd' => 'Sedang'],
        ['name' => 'Puskesmas Bogor Selatan', 'district' => 'Bogor Selatan', 'address' => 'Jl. Batutulis No. 40, Bogor Selatan', 'distance' => '2,8 km', 'hours' => 'Buka 24 Jam (IGD)', 'igd' => true, 'inap' => true, 'nakes' => '100%', 'crowd' => 'Sedang'],
    ];

    $hashtags = ['#Semua', '#IGD24Jam', '#RawatInap', '#BukaSekarang'];
    $footerLinks = [
        ['label' => 'Tentang PuskesMap', 'href' => '#wilayah'],
        ['label' => 'Peta Interaktif SDSS', 'href' => route('peta')],
        ['label' => 'Rekomendasi MAUT', 'href' => '#rekomendasi'],
        ['label' => 'Standar Permenkes 43/2019', 'href' => route('peta')],
    ];
@endphp

<x-layout>
    {{-- ================= FLOATING QUICK-ACTION SIDE DOCK (Terinspirasi Referensi UI) ================= --}}
    <aside aria-label="Pintasan Cepat" class="fixed right-5 top-1/2 -translate-y-1/2 z-40 hidden lg:flex flex-col gap-3">
        <a href="{{ route('peta') }}"
           class="group flex size-[68px] flex-col items-center justify-center rounded-full bg-primary text-white shadow-[0_12px_28px_rgba(0,121,121,0.32)] transition-all hover:scale-105 hover:bg-primary-dark">
            <svg class="size-5 mb-0.5 transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/></svg>
            <span class="text-[10px] font-extrabold tracking-tight leading-none">Peta GIS</span>
        </a>
        <a href="#rekomendasi"
           class="group flex size-[68px] flex-col items-center justify-center rounded-full bg-white text-primary-dark border border-primary/20 shadow-[0_12px_28px_rgba(11,92,92,0.14)] transition-all hover:scale-105 hover:bg-surface-mint">
            <svg class="size-5 mb-0.5 text-accent transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z"/></svg>
            <span class="text-[10px] font-extrabold tracking-tight leading-none">SPK MAUT</span>
        </a>
    </aside>

    {{-- ================= HERO SECTION ================= --}}
    <header id="beranda" class="relative bg-white">
        <div class="relative aspect-[1170/760] min-h-[500px] w-full overflow-hidden [clip-path:ellipse(92%_100%_at_50%_0%)]">
            {{-- Ilustrasi Utama Kota Bogor --}}
            <img src="{{ asset('images/main-hero.svg') }}" alt="Ilustrasi Kota Bogor"
                 class="absolute left-[-11.54%] top-0 h-[115%] w-[123.08%] max-w-none object-cover object-top">

            {{-- Floating Decorative Badges di Sekitar Judul Hero --}}
            <div class="absolute inset-x-0 top-[36%] z-10 flex flex-col items-center px-4 text-center">
                <div class="relative inline-flex items-center gap-3">
                    {{-- Badge Dekoratif Kiri --}}
                    <span class="hidden sm:flex absolute -left-14 -top-4 size-10 items-center justify-center rounded-2xl bg-primary/85 text-white shadow-md -rotate-12 backdrop-blur-xs" aria-hidden="true">
                        <svg class="size-5" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>
                    </span>

                    <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-14 w-auto sm:h-[74px] drop-shadow-sm">
                    <h1 class="text-4xl font-extrabold leading-tight text-primary-dark sm:text-5xl lg:text-6xl drop-shadow-xs">PuskesMap</h1>

                    {{-- Badge Dekoratif Kanan --}}
                    <span class="hidden sm:flex absolute -right-14 -bottom-2 size-10 items-center justify-center rounded-2xl bg-accent/90 text-white shadow-md rotate-12 backdrop-blur-xs" aria-hidden="true">
                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </span>
                </div>

                <p class="mt-3 rounded-full bg-white/75 px-5 py-1.5 text-sm font-bold text-primary-dark shadow-xs backdrop-blur-md sm:text-base border border-white">
                    Peta puskesmas Kota Bogor, di satu tempat
                </p>
            </div>

            {{-- Floating Pill Navbar (Sesuai Referensi Tanpa Tombol Login) --}}
            <nav class="absolute left-1/2 top-6 z-20 w-[calc(100%-2rem)] max-w-[1040px] -translate-x-1/2" aria-label="Navigasi utama">
                <div class="flex h-[68px] items-center justify-between rounded-full bg-white pl-6 pr-3 shadow-nav sm:pl-8 sm:pr-4">
                    <a href="#beranda" class="flex items-center gap-2.5" aria-label="PuskesMap">
                        <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-9 w-auto">
                        <span class="text-xl font-extrabold tracking-tight text-primary">PuskesMap</span>
                    </a>

                    <div class="flex items-center gap-4 sm:gap-8">
                        <ul class="hidden items-center gap-9 text-base font-bold text-body md:flex">
                            @foreach ($navLinks as $link)
                                <li>
                                    <a href="{{ $link['href'] }}" data-nav-link
                                       class="transition-colors hover:text-primary">
                                        {{ $link['label'] }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>

                        <a href="{{ route('peta') }}"
                           class="inline-flex h-11 items-center justify-center rounded-full bg-accent px-7 text-base font-bold text-white shadow-xs transition-all hover:bg-accent-strong active:scale-95">
                            Peta
                        </a>

                        <button type="button" id="nav-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="nav-menu"
                                class="flex size-10 items-center justify-center rounded-full text-ink hover:bg-surface-mint md:hidden">
                            <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                        </button>
                    </div>
                </div>

                <ul id="nav-menu" class="mt-2 hidden rounded-2xl bg-white p-4 text-sm font-semibold shadow-nav md:hidden">
                    @foreach ($navLinks as $link)
                        <li><a href="{{ $link['href'] }}" class="block rounded-lg px-3 py-2.5 hover:bg-surface-mint hover:text-primary">{{ $link['label'] }}</a></li>
                    @endforeach
                    <li class="mt-2 border-t border-gray-100 pt-2">
                        <a href="{{ route('peta') }}" class="flex h-10 items-center justify-center rounded-full bg-accent font-bold text-white">Buka Peta</a>
                    </li>
                </ul>
            </nav>
        </div>

        {{-- ================= SECTION WILAYAH (OVERLAPPING / NIMPA SESUAI REFERENSI TERRITOIRE) ================= --}}
        <div id="wilayah" class="relative z-20 mx-auto max-w-[1140px] px-6 pt-4 pb-10 lg:pt-8 lg:pb-14">
            <div class="grid items-center gap-8 lg:grid-cols-[1.05fr_1fr] lg:gap-0">
                {{-- Kartu Biru Muda "Wilayah" di Kiri (Tertimpa Peta di Sisi Kanan) --}}
                <div class="relative z-10 rounded-[20px] bg-[#8fd0ec] p-8 sm:p-11 lg:py-14 lg:pl-12 lg:pr-44 lg:-mr-36 shadow-[0_14px_35px_rgba(136,205,232,0.28)]">
                    <h2 class="text-4xl sm:text-[44px] font-extrabold tracking-tight text-[#112433] leading-none">
                        Wilayah
                    </h2>

                    {{-- Garis Gelombang Putih (Wavy Underline ala Referensi Territoire) --}}
                    <svg class="mt-3 h-3 w-24 text-white" viewBox="0 0 100 12" fill="none" aria-hidden="true">
                        <path d="M2 8 C22 1, 42 13, 64 5 C78 0, 88 8, 98 4" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>

                    <p class="mt-5 text-sm sm:text-[15px] leading-relaxed text-[#163042] font-medium">
                        PuskesMap memetakan 25 puskesmas induk di enam kecamatan Kota Bogor: Tanah Sareal, Bogor Utara,
                        Bogor Barat, Bogor Tengah, Bogor Timur, dan Bogor Selatan, dilengkapi informasi kesiapan IGD 24 jam,
                        fasilitas rawat inap, kelengkapan 9 profesi tenaga kesehatan, serta jam operasional harian.
                    </p>

                    <div class="mt-8">
                        <a href="{{ route('peta') }}"
                           class="inline-flex h-11 items-center justify-center rounded-full bg-white px-8 text-xs font-extrabold uppercase tracking-wider text-[#112433] shadow-sm transition-all hover:bg-surface-mint hover:scale-105">
                            Jelajahi Wilayah
                        </a>
                    </div>
                </div>

                {{-- Peta Kota Bogor (.webp) Menimpa Kartu Biru di Kiri & Lengkungan Hero di Atas --}}
                <div class="relative z-20 lg:-mt-32 lg:-ml-8 flex flex-col items-center">
                    <x-bogor-map class="mx-auto w-full max-w-[500px] drop-shadow-[0_18px_28px_rgba(11,92,92,0.18)]" />

                    {{-- Ornamen Daun Hijau di Bawah Peta (Sesuai Referensi Territoire) --}}
                    <svg class="-mt-2 h-12 w-16 text-[#8cc63f]" viewBox="0 0 64 48" fill="currentColor" aria-hidden="true">
                        <path d="M28 42 C18 32, 16 16, 28 6 C36 16, 36 32, 28 42 Z" opacity="0.85"/>
                        <path d="M32 42 C42 30, 54 26, 60 34 C50 42, 40 44, 32 42 Z" opacity="0.95"/>
                        <path d="M24 44 C14 38, 6 40, 4 46 C12 48, 20 46, 24 44 Z" opacity="0.75"/>
                    </svg>
                </div>
            </div>
        </div>
    </header>

    {{-- ================= SECTION TENGAH: PILIH KECAMATAN DENGAN 7 IKON BULAT ESTETIK ================= --}}
    <section id="cari" class="relative bg-white pt-10 pb-14">
        <div class="mx-auto max-w-[1140px] px-6">
            {{-- Heading Tengah dengan Ornamen Senyum / Lengkung di Atasnya --}}
            <div class="text-center">
                <div class="mx-auto mb-2 flex flex-col items-center justify-center text-primary/40" aria-hidden="true">
                    <div class="flex gap-2 mb-1">
                        <span class="size-1.5 rounded-full bg-primary/40"></span>
                        <span class="size-1.5 rounded-full bg-primary/40"></span>
                    </div>
                    <svg class="w-8 h-3" viewBox="0 0 32 12" fill="none"><path d="M2 2C9 10 23 10 30 2" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/></svg>
                </div>

                <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight text-ink">
                    Jangan Bingung Cari Faskes! <span class="text-primary">PUSKESMAP</span>
                </h2>
                <p class="mt-1.5 text-xs sm:text-sm font-medium text-muted">
                    Pilih kecamatan di bawah ini untuk memfilter puskesmas terdekat sesuai kebutuhan layananmu
                </p>
            </div>

            {{-- Baris Ikon Bulat Kecamatan (Circular Icon Tabs ala Referensi) --}}
            <div id="district-row" class="no-scrollbar mt-9 flex items-center justify-start sm:justify-center gap-5 sm:gap-7 overflow-x-auto py-2 px-2" role="tablist" aria-label="Pilih Kecamatan">
                @foreach ($districts as $d)
                    <button type="button"
                            role="tab"
                            data-district-btn
                            data-district="{{ $d['name'] }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            class="group flex shrink-0 flex-col items-center gap-2.5 cursor-pointer focus:outline-none">
                        <div class="relative flex size-16 sm:size-[74px] items-center justify-center rounded-full border-2 border-gray-200 bg-white text-ink shadow-xs transition-all duration-200 group-hover:border-primary group-hover:-translate-y-1 group-aria-selected:border-transparent group-aria-selected:bg-gradient-to-br group-aria-selected:from-surface-sky group-aria-selected:to-primary group-aria-selected:text-white group-aria-selected:shadow-[0_10px_25px_rgba(0,121,121,0.28)]">
                            <svg class="size-7 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d['icon'] }}"/>
                            </svg>
                            <span class=" -bottom-1 -right-1 rounded-full bg-surface-mint px-1.5 py-0.5 text-[9px] font-extrabold text-primary-dark border border-primary/20 shadow-2xs group-aria-selected:bg-accent group-aria-selected:text-white group-aria-selected:border-white">
                                {{ $d['count'] }}
                            </span>
                        </div>
                        <span class="text-xs font-bold text-muted transition-colors group-hover:text-primary group-aria-selected:text-primary-dark group-aria-selected:font-extrabold">
                            {{ $d['label'] }}
                        </span>
                    </button>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= WAVE SECTION BIRU/TEAL: CAROUSEL KARTU PUSKESMAS & STATUS STRIP ================= --}}
    <section class="relative bg-gradient-to-b from-[#138282] via-primary to-primary-dark text-white">
        {{-- Top Organic Wave Divider --}}
        <div class="w-full overflow-hidden leading-none bg-white" aria-hidden="true">
            <svg class="relative block w-full h-[54px] sm:h-[86px]" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path d="M0,15 C320,105 680,-25 1120,65 C1280,95 1380,80 1440,55 L1440,100 L0,100 Z" fill="#138282"></path>
            </svg>
        </div>

        <div class="mx-auto max-w-[1180px] px-6 pt-6 pb-16 sm:pb-20">
            {{-- Carousel 4 Kartu Putih Vertikal (Terinspirasi Referensi UI) --}}
            <div class="relative">
                {{-- Tombol Kiri --}}
                <button type="button" data-scroll-target="#card-track" data-scroll-dir="-1" aria-label="Kartu sebelumnya"
                        class="absolute -left-3 sm:-left-5 top-1/2 -translate-y-1/2 z-20 flex size-11 cursor-pointer items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md border border-white/40 hover:bg-white hover:text-primary-dark transition-all shadow-lg">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                </button>

                <div id="card-track" class="no-scrollbar flex snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth py-3 px-1">
                    @foreach ($puskesmas as $item)
                        @php
                            $pastel = $cardColors[$loop->index % count($cardColors)];
                        @endphp
                        <article data-district-card="{{ $item['district'] }}"
                                 class="w-[262px] shrink-0 snap-start rounded-2xl bg-white p-4 text-ink shadow-[0_16px_40px_rgba(7,52,52,0.22)] transition-all duration-200 hover:-translate-y-1.5 flex flex-col justify-between">
                            <div>
                                {{-- Ilustrasi Header Kartu (Tidak Kosong!) --}}
                                <div class="relative h-[142px] w-full rounded-xl {{ $pastel }} overflow-hidden p-3.5 flex flex-col justify-between">
                                    <div class="flex items-center justify-between z-10">
                                        <span class="rounded-full bg-white/90 px-2.5 py-0.5 text-[10px] font-extrabold text-primary-dark shadow-2xs">
                                            Kec. {{ $item['district'] }}
                                        </span>
                                        <span class="rounded-full bg-primary-dark/90 px-2.5 py-0.5 text-[10px] font-bold text-white">
                                            {{ $item['distance'] }}
                                        </span>
                                    </div>

                                    {{-- Ilustrasi Gedung Puskesmas Vektor di Dalam Kartu --}}
                                    <div class="my-auto flex items-center justify-center">
                                        <div class="flex size-14 items-center justify-center rounded-2xl bg-white/85 shadow-xs backdrop-blur-xs">
                                            <svg class="size-8 text-primary" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M12 7v4m-2-2h4"/>
                                            </svg>
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-between text-[10px] font-bold text-primary-dark/80 z-10">
                                        <span>9 Nakes: {{ $item['nakes'] }}</span>
                                        <span>{{ $item['igd'] ? '🚨 IGD 24 Jam' : '🩺 Rawat Jalan' }}</span>
                                    </div>
                                </div>

                                {{-- Informasi Teks Kartu --}}
                                <div class="mt-3.5 px-1">
                                    <span class="text-[11px] font-extrabold text-primary uppercase tracking-wider">Puskesmas Induk</span>
                                    <h3 class="mt-0.5 text-base font-extrabold leading-snug text-ink line-clamp-1">{{ $item['name'] }}</h3>
                                    <p class="mt-1 text-xs text-muted line-clamp-1">📍 {{ $item['address'] }}</p>
                                    <p class="mt-1 text-[11px] font-semibold text-muted">⏰ {{ $item['hours'] }}</p>

                                    <x-crowd-bar :level="$item['crowd']" :label="'Keramaian: ' . $item['crowd']" />
                                </div>
                            </div>

                            {{-- Tombol Pill di Bawah Kartu --}}
                            <div class="mt-4 pt-1">
                                <a href="{{ route('peta') }}"
                                   class="flex h-9 w-full items-center justify-center gap-1.5 rounded-full bg-primary text-xs font-extrabold text-white shadow-xs transition-colors hover:bg-primary-dark">
                                    <span>Lihat di Peta</span>
                                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/></svg>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>

                {{-- Tombol Kanan --}}
                <button type="button" data-scroll-target="#card-track" data-scroll-dir="1" aria-label="Kartu berikutnya"
                        class="absolute -right-3 sm:-right-5 top-1/2 -translate-y-1/2 z-20 flex size-11 cursor-pointer items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md border border-white/40 hover:bg-white hover:text-primary-dark transition-all shadow-lg">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                </button>
            </div>

            {{-- Horizontal White Pill Strip (Terinspirasi Bar Jadwal di Bawah Kartu pada Referensi) --}}
            <div class="relative mt-12">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4 rounded-3xl lg:rounded-full bg-white px-6 py-4 text-ink shadow-[0_14px_34px_rgba(7,52,52,0.2)]">
                    <div class="flex items-center gap-3">
                        <span class="rounded-full bg-primary px-3.5 py-1 text-xs font-extrabold text-white">Sebaran Wilayah</span>
                        <span class="text-xs font-bold text-muted">Kesiapan 25 Puskesmas di 6 Kecamatan:</span>
                    </div>

                    <div class="flex flex-wrap items-center justify-center gap-2 sm:gap-3 text-xs">
                        @foreach (array_slice($districts, 1) as $distItem)
                            <button type="button"
                                    onclick="document.querySelector('[data-district=&quot;{{ $distItem['name'] }}&quot;]')?.click()"
                                    class="group relative flex items-center gap-1.5 rounded-full bg-surface-mint/70 hover:bg-primary hover:text-white px-3 py-1 font-bold text-primary-dark transition-colors cursor-pointer">
                                <span class="size-1.5 rounded-full bg-accent"></span>
                                <span>{{ $distItem['name'] }}</span>
                                <span class="text-[10px] opacity-75">({{ $distItem['count'] }})</span>
                            </button>
                        @endforeach
                    </div>

                    <a href="{{ route('peta') }}"
                       class="inline-flex items-center gap-1 rounded-full bg-accent px-4 py-1.5 text-xs font-extrabold text-white hover:bg-accent-strong transition-colors shrink-0">
                        <span>Eksplorasi Peta</span>
                        <span>+</span>
                    </a>
                </div>
                <p class="mt-3 text-center text-xs font-medium text-white/80">
                    * Klik salah satu kecamatan di atas untuk memfilter kartu puskesmas atau langsung buka halaman Peta WebGIS.
                </p>
            </div>
        </div>

        {{-- Bottom Organic Wave Divider Menuju Sekilas di Peta --}}
        <div class="w-full overflow-hidden leading-none" aria-hidden="true">
            <svg class="relative block w-full h-[50px] sm:h-[76px]" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path d="M0,60 C380,110 820,-10 1440,50 L1440,100 L0,100 Z" fill="#e8f4f2"></path>
            </svg>
        </div>
    </section>

    {{-- ================= SEKILAS DI PETA & REKOMENDASI MAUT ================= --}}
    <section id="peta" class="bg-gradient-to-b from-surface-mint via-[#e2f2f5] to-white pt-10 pb-16 sm:pb-24">
        <div class="mx-auto max-w-[1180px] px-6">
            <div class="text-center">
                <span class="inline-block rounded-full bg-white px-4 py-1 text-xs font-extrabold text-primary shadow-2xs border border-primary/15">
                     PRATINJAU SPASIAL KOTA BOGOR
                </span>
                <h2 class="mt-2 text-3xl font-extrabold leading-tight text-primary-dark sm:text-4xl">Sekilas di Peta</h2>
                <div class="mt-5 flex flex-wrap justify-center gap-2">
                    @foreach ($hashtags as $tag)
                        <button type="button" data-chip aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                class="h-8 cursor-pointer rounded-full border border-primary/20 bg-white px-4 text-xs font-bold text-ink shadow-2xs transition-colors aria-pressed:border-primary aria-pressed:bg-primary aria-pressed:text-white">
                            {{ $tag }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Container Pratinjau Peta Vektor --}}
            <div class="relative mt-8 h-[640px] rounded-2xl border-[3px] border-primary bg-[#e4f2f7] shadow-[0_20px_50px_rgba(0,121,121,0.12)] sm:h-[540px] overflow-hidden">
                <x-bogor-map :labels="false" class="absolute left-1/2 top-3 h-[calc(100%-1.5rem)] -translate-x-1/2" />

                {{-- Kartu Kontrol Radius Kiri Atas --}}
                <div class="absolute left-5 top-5 w-[275px] max-w-[calc(100%-2.5rem)] rounded-xl bg-white/95 backdrop-blur-md p-4 shadow-float border border-primary/15">
                    <div class="flex items-center justify-between">
                        <p class="text-sm font-extrabold text-ink">Radius pencarian: 3 km</p>
                        <span class="size-2.5 rounded-full bg-emerald-500 animate-ping"></span>
                    </div>
                    <p class="mt-1 text-[11px] text-muted">Hanya puskesmas dalam radius geodetik ini yang dipertimbangkan.</p>
                    <div class="mt-3.5 space-y-2">
                        <x-button :href="route('peta')" variant="primary" class="w-full font-bold">Cari rekomendasi MAUT</x-button>
                        <x-button :href="route('peta')" variant="outline" class="w-full font-bold">Gunakan lokasi saya</x-button>
                    </div>
                </div>

                {{-- Kartu Info Puskesmas Kanan --}}
                <div id="map-info-card" class="absolute bottom-4 left-4 w-[270px] max-w-[calc(100%-2rem)] rounded-xl bg-white/95 backdrop-blur-md p-4 shadow-float border border-primary/15 transition-all duration-200 sm:bottom-auto sm:left-auto sm:right-20 sm:top-1/2 sm:-translate-y-1/2">
                    <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[10px] font-extrabold text-primary-dark mb-1">
                        Sorotan Peta
                    </span>
                    <h3 id="info-card-name" class="text-[15px] font-extrabold text-ink">Puskesmas Tanah Sareal</h3>
                    <p id="info-card-distance" class="text-xs text-muted">Jarak 1,1 km dari pusat referensi</p>
                    <div id="info-card-tags" class="mt-2.5 flex flex-wrap gap-1.5">
                        <x-tag>1,1 km</x-tag><x-tag>Buka</x-tag><x-tag>IGD 24 jam</x-tag><x-tag>Rawat inap</x-tag>
                    </div>
                    <div id="info-card-crowd">
                        <x-crowd-bar level="Sedang" class="mt-3" />
                    </div>
                    <div class="mt-3.5 space-y-2">
                        <x-button :href="route('peta')" variant="primary" class="w-full font-bold">Buka di Peta GIS</x-button>
                        <x-button href="https://kotabogor.go.id" target="_blank" variant="outline" class="w-full font-bold">Portal resmi</x-button>
                    </div>
                </div>

                {{-- Kontrol Zoom Bulat Kanan Bawah --}}
                <div class="absolute bottom-5 right-5 flex flex-col gap-2.5">
                    <a href="{{ route('peta') }}" aria-label="Buka Peta Penuh" title="Buka Peta Interaktif Penuh" class="flex size-10 cursor-pointer items-center justify-center rounded-full bg-primary text-white shadow-float hover:bg-primary-dark transition-colors">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                    </a>
                </div>
            </div>

            {{-- Tombol CTA di Bawah Pratinjau Peta --}}
            <div class="mt-8 flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('peta') }}" class="sm:w-[280px] text-center rounded-full bg-primary text-white py-3.5 font-extrabold text-sm hover:bg-primary-dark transition-all shadow-md">
                    Buka Dasbor Peta Interaktif &rarr;
                </a>
                <a href="#rekomendasi" class="sm:w-[280px] text-center rounded-full bg-white border-2 border-primary text-primary py-3.5 font-extrabold text-sm hover:bg-surface-mint transition-all shadow-xs">
                    Lihat Rekomendasi Terbaik
                </a>
            </div>
        </div>

        {{-- ================= REKOMENDASI UNTUKMU (MAUT PREVIEW) ================= --}}
        <div id="rekomendasi" class="mt-16 flex flex-col items-stretch gap-6 lg:flex-row lg:items-center">
            <div class="mx-6 rounded-2xl bg-white p-8 shadow-float border border-primary/15 lg:mx-0 lg:w-[400px] lg:shrink-0 lg:rounded-l-none lg:rounded-r-3xl">
                <span class="inline-block rounded-full bg-accent/15 px-3 py-1 text-xs font-extrabold text-accent-strong">
                    METODE SPK MAUT
                </span>
                <h2 class="mt-2 text-[28px] font-extrabold leading-tight text-primary-dark">Rekomendasi untukmu</h2>
                <p class="mt-2 text-sm text-muted leading-relaxed">
                    Dihitung otomatis dari normalisasi jarak geodetik, kesiapan IGD 24 jam, kelengkapan 9 profesi nakes, dan tingkat keramaian.
                </p>
                <div class="mt-6 flex items-center gap-4">
                    <button type="button" data-scroll-target="#rec-track" data-scroll-dir="-1" aria-label="Rekomendasi sebelumnya"
                            class="flex size-11 cursor-pointer items-center justify-center rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-white transition-colors">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                    </button>
                    <span class="text-sm font-extrabold text-ink" id="rec-counter" aria-live="polite">1 / {{ count($puskesmas) }}</span>
                    <button type="button" data-scroll-target="#rec-track" data-scroll-dir="1" aria-label="Rekomendasi berikutnya"
                            class="flex size-11 cursor-pointer items-center justify-center rounded-full border-2 border-primary text-primary hover:bg-primary hover:text-white transition-colors">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                    </button>
                </div>
            </div>

            <div id="rec-track" class="no-scrollbar flex min-w-0 flex-1 snap-x snap-mandatory gap-6 overflow-x-auto scroll-smooth px-6 py-4 lg:px-0">
                @foreach ($puskesmas as $item)
                    <article data-rec-card class="w-[265px] shrink-0 snap-start rounded-2xl bg-white p-3.5 shadow-card border border-gray-100 hover:shadow-float transition-all">
                        <div class="relative h-[145px] rounded-xl {{ $cardColors[$loop->index % count($cardColors)] }} p-3 flex flex-col justify-between overflow-hidden">
                            <div class="flex items-center justify-between">
                                <span class="flex size-8 items-center justify-center rounded-full bg-accent text-xs font-extrabold text-white shadow-xs">
                                    #{{ $loop->iteration }}
                                </span>
                                <span class="rounded-full bg-white/90 px-2.5 py-0.5 text-[10px] font-extrabold text-primary-dark">
                                    Kec. {{ $item['district'] }}
                                </span>
                            </div>

                            <div class="my-auto flex items-center justify-center">
                                <div class="flex size-12 items-center justify-center rounded-full bg-white/80 text-primary shadow-2xs">
                                    <svg class="size-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                </div>
                            </div>

                            <div class="text-right">
                                <span class="rounded-full bg-primary-dark/85 px-2 py-0.5 text-[10px] font-bold text-white">
                                    Nakes {{ $item['nakes'] }}
                                </span>
                            </div>
                        </div>

                        <h3 class="mt-3 truncate px-1 text-sm font-extrabold text-ink">{{ $item['name'] }}</h3>
                        <div class="mt-1.5 flex flex-wrap gap-1 px-1">
                            <x-tag>{{ $item['distance'] }}</x-tag>
                            <x-tag>Buka</x-tag>
                            @if ($item['igd'])
                                <x-tag class="bg-accent/15 text-accent-strong">IGD 24 jam</x-tag>
                            @endif
                        </div>
                        <x-crowd-bar :level="$item['crowd']" class="mt-2.5 px-1" />
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= FOOTER BERSIH ALA PORTAL ESTETIK ================= --}}
    <footer class="border-t border-gray-200 bg-white">
        {{-- Baris Partner / Instansi Terkait --}}
        <div class="border-b border-gray-100 bg-gray-50/60 py-3.5">
            <div class="mx-auto flex max-w-[1180px] flex-wrap items-center justify-between gap-4 px-6 text-xs font-semibold text-muted">
                <div class="flex flex-wrap items-center gap-6">
                    <span class="font-extrabold text-primary-dark">Pemerintah Kota Bogor</span>
                    <span>Dinas Kesehatan Kota Bogor</span>
                    <span>25 UPTD Puskesmas Induk</span>
                    <span>Standar Permenkes No. 43 Tahun 2019</span>
                </div>
                <a href="{{ route('peta') }}" class="text-primary font-bold hover:underline">Buka Portal WebGIS &rarr;</a>
            </div>
        </div>

        <div class="mx-auto max-w-[1180px] px-6 py-9">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-10 w-auto">
                    <div>
                        <span class="text-xl font-extrabold text-primary-dark block leading-none">PuskesMap</span>
                        <span class="text-xs text-muted mt-1 block">Sistem Pendukung Keputusan Spasial Puskesmas Kota Bogor</span>
                    </div>
                </div>

                <div class="flex flex-wrap items-center gap-x-7 gap-y-2">
                    @foreach ($footerLinks as $link)
                        <a href="{{ $link['href'] }}" class="text-xs sm:text-sm font-bold text-ink hover:text-primary transition-colors">
                            {{ $link['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>

            <div class="mt-6 pt-5 border-t border-gray-100 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 text-xs text-muted">
                <p>Cakupan Spasial: 25 Puskesmas Induk · 6 Kecamatan · Kota Bogor, Jawa Barat.</p>
                <p class="font-extrabold uppercase text-primary">Copyright &copy; {{ date('Y') }} PuskesMap Kota Bogor. All rights reserved.</p>
            </div>
        </div>
    </footer>
</x-layout>
