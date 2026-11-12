@php
    $navLinks = [
        ['label' => 'Tentang', 'href' => '#wilayah'],
        ['label' => 'Fitur', 'href' => '#peta'],
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
        ['label' => 'Beranda', 'href' => '#beranda'],
        ['label' => 'Wilayah Cakupan', 'href' => '#wilayah'],
        ['label' => 'Daftar 25 Puskesmas', 'href' => '#cari'],
        ['label' => 'Pratinjau Peta', 'href' => '#peta'],
        ['label' => 'WebGIS & SDSS MAUT', 'href' => route('peta')],
        ['label' => 'Standar Permenkes 43/2019', 'href' => route('peta')],
    ];

    $footerTabs = [
        [
            'id' => 'dinkes',
            'subtitle' => 'Instansi Utama',
            'title' => 'Dinkes Kota Bogor',
            'address' => 'Jl. Kesehatan No. 3, Kel. Tanah Sareal, Kec. Tanah Sareal, 16161 Kota Bogor, Jawa Barat.',
            'phone' => '(0251) 8331753',
            'hours' => '07.30 – 16.00 WIB (Senin – Jumat) · Layanan Administrasi & Pengendalian Mutu Faskes',
        ],
        [
            'id' => 'utara-tengah',
            'subtitle' => 'Wilayah Layanan I',
            'title' => 'Utara, Tengah & Tanah Sareal',
            'address' => 'Mencakup 13 UPTD Puskesmas Induk di Kec. Tanah Sareal (5), Bogor Tengah (5), dan Bogor Utara (3).',
            'phone' => '112 / (0251) 8344000',
            'hours' => '07.30 – 14.30 WIB (Rawat Jalan) / 24 Jam Nonstop (Puskesmas Induk dengan IGD & PONED)',
        ],
        [
            'id' => 'barat-selatan',
            'subtitle' => 'Wilayah Layanan II',
            'title' => 'Barat, Timur & Selatan',
            'address' => 'Mencakup 12 UPTD Puskesmas Induk di Kec. Bogor Barat (5), Bogor Selatan (5), dan Bogor Timur (2).',
            'phone' => '112 / (0251) 8321196',
            'hours' => '07.30 – 14.30 WIB (Rawat Jalan) / 24 Jam Nonstop (Puskesmas Induk dengan IGD & Rawat Inap)',
        ],
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
        <a href="#peta"
           class="group flex size-[68px] flex-col items-center justify-center rounded-full bg-white text-primary-dark border border-primary/20 shadow-[0_12px_28px_rgba(11,92,92,0.14)] transition-all hover:scale-105 hover:bg-surface-mint">
            <svg class="size-5 mb-0.5 text-accent transition-transform group-hover:-translate-y-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
            <span class="text-[10px] font-extrabold tracking-tight leading-none">Sekilas</span>
        </a>
    </aside>

    {{-- ================= HERO SECTION ================= --}}
    <header id="beranda" class="relative bg-white">
        {{-- Floating Pill Navbar (Dikeluarkan dari clip-path agar dropdown HP tidak pernah terpotong!) --}}
        <nav class="absolute left-1/2 top-3 sm:top-6 z-40 w-[calc(100%-1.5rem)] sm:w-[calc(100%-2rem)] max-w-[1040px] -translate-x-1/2" aria-label="Navigasi utama">
            <div class="flex h-13 sm:h-[68px] items-center justify-between rounded-full bg-white pl-3.5 pr-2 sm:pl-8 sm:pr-4 shadow-nav">
                <a href="#beranda" class="flex items-center gap-2 sm:gap-2.5 shrink-0" aria-label="PuskesMap">
                    <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-7 sm:h-9 w-auto">
                    <span class="text-[15px] sm:text-xl font-extrabold tracking-tight text-primary">PuskesMap</span>
                </a>

                <div class="flex items-center gap-1.5 sm:gap-8">
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
                       class="inline-flex h-8 sm:h-11 items-center justify-center rounded-full bg-accent px-4 sm:px-7 text-xs sm:text-base font-bold text-white shadow-xs transition-all hover:bg-accent-strong active:scale-95">
                        Peta
                    </a>

                    <button type="button" id="nav-toggle" aria-label="Buka menu" aria-expanded="false" aria-controls="nav-menu"
                            class="flex size-8 sm:size-10 items-center justify-center rounded-full text-ink hover:bg-surface-mint md:hidden cursor-pointer">
                        <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                    </button>
                </div>
            </div>

            <ul id="nav-menu" class="mt-2 hidden rounded-2xl bg-white/95 backdrop-blur-md p-3 text-sm font-bold shadow-float border border-primary/15 md:hidden">
                @foreach ($navLinks as $link)
                    <li><a href="{{ $link['href'] }}" data-mobile-nav-link class="block rounded-xl px-3.5 py-2.5 text-ink hover:bg-surface-mint hover:text-primary">{{ $link['label'] }}</a></li>
                @endforeach
                <li class="mt-2 border-t border-gray-100 pt-2">
                    <a href="{{ route('peta') }}" class="flex h-10 items-center justify-center rounded-full bg-accent font-extrabold text-white shadow-xs">Buka Dasbor Peta GIS &rarr;</a>
                </li>
            </ul>
        </nav>

        <div class="relative h-[380px] sm:h-auto sm:aspect-[1170/760] sm:min-h-[520px] w-full overflow-hidden [clip-path:ellipse(140%_100%_at_50%_0%)] sm:[clip-path:ellipse(92%_100%_at_50%_0%)]">
            {{-- Ilustrasi Utama Kota Bogor --}}
            <img src="{{ asset('images/main-hero.svg') }}" alt="Ilustrasi Kota Bogor"
                 class="absolute left-1/2 -translate-x-1/2 w-[155%] sm:left-[-11.54%] sm:translate-x-0 top-0 h-[112%] sm:h-[115%] sm:w-[123.08%] max-w-none object-cover object-top">

            {{-- Floating Decorative Badges & Judul Hero di Area Langit/Awan --}}
            <div class="absolute inset-x-0 top-[21%] sm:top-[19%] lg:top-[20%] z-10 flex flex-col items-center px-4 text-center">
                <div class="relative inline-flex items-center gap-2 sm:gap-5">
                    {{-- Badge Dekoratif Kiri --}}
                    <span class="flex absolute -left-7 -top-2 sm:-left-16 sm:-top-5 size-6 sm:size-12 items-center justify-center rounded-lg sm:rounded-2xl bg-primary/90 text-white shadow-lg -rotate-12 backdrop-blur-xs" aria-hidden="true">
                        <svg class="size-3.5 sm:size-6" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>
                    </span>

                    <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-9 w-auto sm:h-[86px] lg:h-[96px] drop-shadow-md">
                    <h1 class="text-3xl font-extrabold tracking-tight leading-none text-primary-dark sm:text-6xl lg:text-[74px] drop-shadow-xs">
                        PuskesMap
                    </h1>

                    {{-- Badge Dekoratif Kanan --}}
                    <span class="flex absolute -right-7 -bottom-1.5 sm:-right-16 sm:-bottom-3 size-6 sm:size-12 items-center justify-center rounded-lg sm:rounded-2xl bg-accent/95 text-white shadow-lg rotate-12 backdrop-blur-xs" aria-hidden="true">
                        <svg class="size-3.5 sm:size-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </span>
                </div>

                <p class="mt-2.5 sm:mt-4 max-w-[92vw] rounded-full bg-white/88 px-3.5 py-1 sm:px-7 sm:py-2 text-[11px] sm:text-lg lg:text-xl font-extrabold text-primary-dark shadow-sm backdrop-blur-md border border-white">
                    Peta puskesmas Kota Bogor, di satu tempat
                </p>
            </div>
        </div>

        {{-- ================= SECTION WILAYAH (OVERLAPPING / NIMPA DENGAN PALET PUSKESMAP) ================= --}}
        <div id="wilayah" class="relative z-20 mx-auto max-w-[1140px] px-4 sm:px-6 pt-3 pb-8 lg:pt-8 lg:pb-14">
            {{-- Garis Lengkung Halus di Latar Belakang (Sesuai Referensi Peta) --}}
            <svg class="pointer-events-none absolute inset-x-0 top-1/2 -translate-y-1/2 w-full h-48 text-[#e5f2f0] -z-10 hidden lg:block" viewBox="0 0 1200 200" fill="none" preserveAspectRatio="none" aria-hidden="true">
                <path d="M0 160 Q600 -30 1200 140" stroke="currentColor" stroke-width="5" stroke-linecap="round"/>
            </svg>

            <div class="grid items-center gap-0 lg:grid-cols-[1.05fr_1fr]">
                {{-- Kartu "Wilayah" di Kiri (Tertimpa Peta di Bawah pada HP & di Kanan pada Desktop) --}}
                <div class="relative z-10 rounded-[24px] bg-surface-sky p-6 pb-11 sm:p-11 sm:pb-14 lg:py-14 lg:pl-12 lg:pr-44 lg:-mr-36 shadow-[0_16px_40px_rgba(0,121,121,0.12)] border border-white/60">
                    <h2 class="text-3xl sm:text-[44px] font-extrabold tracking-tight text-primary-dark leading-none">
                        Wilayah
                    </h2>

                    {{-- Garis Gelombang Putih (Wavy Underline ala Referensi Territoire) --}}
                    <svg class="mt-2.5 sm:mt-3 h-3 w-20 sm:w-24 text-white" viewBox="0 0 100 12" fill="none" aria-hidden="true">
                        <path d="M2 8 C22 1, 42 13, 64 5 C78 0, 88 8, 98 4" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    </svg>

                    <p class="mt-4 sm:mt-5 text-xs sm:text-[15px] leading-relaxed text-body font-medium">
                        PuskesMap memetakan 25 puskesmas induk di enam kecamatan Kota Bogor: Tanah Sareal, Bogor Utara,
                        Bogor Barat, Bogor Tengah, Bogor Timur, dan Bogor Selatan, dilengkapi informasi kesiapan IGD 24 jam,
                        fasilitas rawat inap, kelengkapan 9 profesi tenaga kesehatan, serta jam operasional harian.
                    </p>

                    <div class="mt-6 sm:mt-8">
                        <a href="{{ route('peta') }}"
                           class="inline-flex h-10 sm:h-11 items-center justify-center rounded-full bg-white px-6 sm:px-8 text-xs font-extrabold uppercase tracking-wider text-primary-dark shadow-sm transition-all hover:bg-primary hover:text-white hover:scale-105">
                            Jelajahi Wilayah
                        </a>
                    </div>
                </div>

                {{-- Peta Vektor Kota Bogor (Palet Mint-Teal-Sage) Menimpa Kartu --}}
                <div class="relative z-20 -mt-7 sm:-mt-10 lg:-mt-32 lg:-ml-8 flex flex-col items-center px-1 sm:px-0">
                    <x-bogor-map class="mx-auto w-full max-w-[460px] sm:max-w-[510px]" />

                    <span class="mt-1 lg:hidden inline-flex items-center gap-1.5 rounded-full bg-surface-mint px-3.5 py-1 text-[11px] font-bold text-primary-dark">
                        <span>👆</span>
                        <span>Ketuk kecamatan di peta untuk info faskes</span>
                    </span>

                    {{-- Ornamen Daun Hijau-Teal di Bawah Peta --}}
                    <svg class="-mt-1 sm:-mt-2 h-10 w-14 sm:h-12 sm:w-16 text-[#68c196]" viewBox="0 0 64 48" fill="currentColor" aria-hidden="true">
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

            {{-- Baris Ikon Bulat Kecamatan (Swipeable di HP & Terpusat di Desktop) --}}
            <div id="district-row" class="no-scrollbar mt-7 sm:mt-9 -mx-4 px-4 sm:mx-0 sm:px-2 flex items-center justify-start sm:justify-center gap-4 sm:gap-7 overflow-x-auto snap-x snap-mandatory py-2" role="tablist" aria-label="Pilih Kecamatan">
                @foreach ($districts as $d)
                    <button type="button"
                            role="tab"
                            data-district-btn
                            data-district="{{ $d['name'] }}"
                            aria-selected="{{ $loop->first ? 'true' : 'false' }}"
                            class="group snap-start flex shrink-0 flex-col items-center gap-2 cursor-pointer focus:outline-none">
                        <div class="relative flex size-15 sm:size-[74px] items-center justify-center rounded-full border-2 border-gray-200 bg-white text-ink shadow-xs transition-all duration-200 group-hover:border-primary group-hover:-translate-y-1 group-aria-selected:border-transparent group-aria-selected:bg-gradient-to-br group-aria-selected:from-surface-sky group-aria-selected:to-primary group-aria-selected:text-white group-aria-selected:shadow-[0_10px_25px_rgba(0,121,121,0.28)]">
                            <svg class="size-6 sm:size-7 transition-transform group-hover:scale-110" fill="none" stroke="currentColor" stroke-width="1.75" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $d['icon'] }}"/>
                            </svg>
                            <span class="absolute -bottom-1 -right-1 rounded-full bg-surface-mint px-1.5 py-0.5 text-[9px] font-extrabold text-primary-dark border border-primary/20 shadow-2xs group-aria-selected:bg-accent group-aria-selected:text-white group-aria-selected:border-white">
                                {{ $d['count'] }}
                            </span>
                        </div>
                        <span class="text-[11px] sm:text-xs font-bold text-muted transition-colors group-hover:text-primary group-aria-selected:text-primary-dark group-aria-selected:font-extrabold">
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
            <svg class="relative block w-full h-[42px] sm:h-[86px]" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path d="M0,15 C320,105 680,-25 1120,65 C1280,95 1380,80 1440,55 L1440,100 L0,100 Z" fill="#138282"></path>
            </svg>
        </div>

        <div class="mx-auto max-w-[1180px] px-4 sm:px-6 pt-4 sm:pt-6 pb-14 sm:pb-20">
            {{-- Carousel Kartu Putih Vertikal (Peek Card UX di HP) --}}
            <div class="relative">
                {{-- Tombol Kiri --}}
                <button type="button" data-scroll-target="#card-track" data-scroll-dir="-1" aria-label="Kartu sebelumnya"
                        class="hidden sm:flex absolute -left-5 top-1/2 -translate-y-1/2 z-20 size-11 cursor-pointer items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md border border-white/40 hover:bg-white hover:text-primary-dark transition-all shadow-lg">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                </button>

                <div id="card-track" class="no-scrollbar -mx-4 px-4 sm:mx-0 sm:px-1 flex snap-x snap-mandatory gap-4 sm:gap-6 overflow-x-auto scroll-smooth py-3">
                    @foreach ($puskesmas as $item)
                        @php
                            $pastel = $cardColors[$loop->index % count($cardColors)];
                        @endphp
                        <article data-district-card="{{ $item['district'] }}"
                                 class="w-[78vw] max-w-[270px] sm:w-[262px] shrink-0 snap-start rounded-2xl bg-white p-4 text-ink shadow-[0_16px_40px_rgba(7,52,52,0.22)] transition-all duration-200 hover:-translate-y-1.5 flex flex-col justify-between">
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
                        class="hidden sm:flex absolute -right-5 top-1/2 -translate-y-1/2 z-20 size-11 cursor-pointer items-center justify-center rounded-full bg-white/20 text-white backdrop-blur-md border border-white/40 hover:bg-white hover:text-primary-dark transition-all shadow-lg">
                    <svg class="size-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                </button>

                {{-- Kontrol Geser Kartu Khusus HP --}}
                <div class="mt-2 flex sm:hidden items-center justify-between px-1">
                    <span class="text-[11px] font-semibold text-white/80">&larr; Geser kartu untuk melihat lainnya &rarr;</span>
                    <div class="flex items-center gap-2">
                        <button type="button" data-scroll-target="#card-track" data-scroll-dir="-1" aria-label="Sebelumnya"
                                class="flex size-8 items-center justify-center rounded-full bg-white/20 text-white border border-white/30">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m15 18-6-6 6-6"/></svg>
                        </button>
                        <button type="button" data-scroll-target="#card-track" data-scroll-dir="1" aria-label="Berikutnya"
                                class="flex size-8 items-center justify-center rounded-full bg-white/20 text-white border border-white/30">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Horizontal White Pill Strip (Responsif Grid 2 Kolom di HP) --}}
            <div class="relative mt-8 sm:mt-12">
                <div class="flex flex-col lg:flex-row items-center justify-between gap-4 rounded-3xl lg:rounded-full bg-white p-4 sm:px-6 sm:py-4 text-ink shadow-[0_14px_34px_rgba(7,52,52,0.2)]">
                    <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 sm:gap-3 text-center sm:text-left">
                        <span class="rounded-full bg-primary px-3.5 py-1 text-xs font-extrabold text-white">Sebaran Wilayah</span>
                        <span class="text-xs font-bold text-muted">Kesiapan 25 Puskesmas di 6 Kecamatan:</span>
                    </div>

                    <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center justify-center gap-2 sm:gap-3 w-full lg:w-auto text-xs">
                        @foreach (array_slice($districts, 1) as $distItem)
                            <button type="button"
                                     onclick="document.querySelector('[data-district=&quot;{{ $distItem['name'] }}&quot;]')?.click()"
                                    class="group relative flex items-center justify-center sm:justify-start gap-1.5 rounded-full bg-surface-mint/70 hover:bg-primary hover:text-white px-3 py-2 sm:py-1 font-bold text-primary-dark transition-colors cursor-pointer">
                                <span class="size-1.5 rounded-full bg-accent shrink-0"></span>
                                <span class="truncate">{{ $distItem['name'] }}</span>
                                <span class="text-[10px] opacity-75 shrink-0">({{ $distItem['count'] }})</span>
                            </button>
                        @endforeach
                    </div>

                    <a href="{{ route('peta') }}"
                       class="inline-flex w-full sm:w-auto items-center justify-center gap-1 rounded-full bg-accent px-5 py-2.5 sm:py-1.5 text-xs font-extrabold text-white hover:bg-accent-strong transition-colors shrink-0">
                        <span>Eksplorasi Peta</span>
                        <span>+</span>
                    </a>
                </div>
                <p class="mt-3 text-center text-xs font-medium text-white/80">
                    * Ketuk salah satu kecamatan di atas untuk memfilter kartu puskesmas atau langsung buka halaman Peta WebGIS.
                </p>
            </div>
        </div>

        {{-- Bottom Organic Wave Divider Menuju Sekilas di Peta --}}
        <div class="w-full overflow-hidden leading-none" aria-hidden="true">
            <svg class="relative block w-full h-[40px] sm:h-[76px]" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path d="M0,60 C380,110 820,-10 1440,50 L1440,100 L0,100 Z" fill="#e8f4f2"></path>
            </svg>
        </div>
    </section>

    {{-- ================= SEKILAS DI PETA (REAL INTERACTIVE STREET MAP — RESPONSIF UNTUK HP) ================= --}}
    <section id="peta" class="bg-gradient-to-b from-surface-mint via-[#eef7f6] to-white pt-8 sm:pt-10 pb-14 sm:pb-24">
        <div class="mx-auto max-w-[1180px] px-4 sm:px-6">
            <div class="text-center">
                <span class="inline-block rounded-full bg-white px-4 py-1 text-xs font-extrabold text-primary shadow-2xs border border-primary/15">
                     PRATINJAU SPASIAL KOTA BOGOR
                </span>
                <h2 class="mt-2 text-2xl sm:text-4xl font-extrabold leading-tight text-primary-dark">Sekilas di Peta</h2>
                <p class="mt-1.5 text-xs sm:text-sm font-medium text-muted">
                    Peta jalan interaktif real-time 25 Puskesmas Induk di 6 Kecamatan Kota Bogor
                </p>
                <div class="mt-4 sm:mt-5 flex flex-wrap justify-center gap-2">
                    @foreach ($hashtags as $tag)
                        <button type="button" data-chip aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                class="h-8 cursor-pointer rounded-full border border-primary/20 bg-white px-3.5 sm:px-4 text-xs font-bold text-ink shadow-2xs transition-colors aria-pressed:border-primary aria-pressed:bg-primary aria-pressed:text-white">
                            {{ $tag }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Container Peta Jalan Interaktif (Stacked Bersih di HP agar Peta 100% Terbuka, Floating Overlay di Desktop!) --}}
            <div class="relative mt-6 sm:mt-8 flex flex-col sm:block sm:h-[560px] rounded-3xl border-2 border-primary/20 bg-white sm:bg-[#f3f6f4] shadow-[0_24px_60px_rgba(0,121,121,0.14)] overflow-hidden">
                {{-- Kartu Kontrol Radius: Header Ringkas di HP, Floating Card Kiri Atas di Desktop --}}
                <div class="relative sm:absolute sm:left-5 sm:top-5 z-20 w-full sm:w-[275px] sm:rounded-2xl bg-white sm:bg-white/95 sm:backdrop-blur-md p-3.5 sm:p-4 sm:shadow-float border-b sm:border border-gray-100">
                    <div class="flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="size-2.5 rounded-full bg-emerald-500 animate-ping shrink-0"></span>
                            <p class="text-xs sm:text-sm font-extrabold text-ink">Radius pencarian: 3 km</p>
                        </div>
                        <a href="{{ route('peta') }}" class="sm:hidden rounded-full bg-surface-mint px-3 py-1 text-[11px] font-extrabold text-primary-dark">
                            Layar Penuh &rarr;
                        </a>
                    </div>
                    <p class="mt-1 text-[11px] text-muted">Hanya puskesmas dalam radius geodetik ini yang dipertimbangkan.</p>
                    <div class="mt-2.5 sm:mt-3.5 grid grid-cols-2 sm:grid-cols-1 gap-2">
                        <x-button :href="route('peta')" variant="primary" class="w-full font-bold text-[11px] sm:text-xs h-9 sm:h-10">Rekomendasi MAUT</x-button>
                        <button type="button" id="landing-btn-locate"
                                class="inline-flex h-9 sm:h-10 w-full items-center justify-center rounded-full border border-primary bg-white px-3 sm:px-5 text-[11px] sm:text-xs font-bold text-primary transition-colors hover:bg-surface-mint cursor-pointer">
                            Gunakan lokasi saya
                        </button>
                    </div>
                </div>

                {{-- Kanvas Peta Jalan Asli (Leaflet) — 100% Terbuka Tanpa Tertutup Kartu di HP --}}
                <div class="relative h-[360px] sm:h-[560px] w-full">
                    <div id="landing-leaflet-map" class="absolute inset-0 h-full w-full z-10" aria-label="Peta Jalan Interaktif Puskesmas Kota Bogor"></div>

                    {{-- Kontrol Zoom & Reset di Kiri Bawah Peta (Tampil di HP & Desktop) --}}
                    <div class="flex absolute bottom-3 left-3 sm:bottom-5 sm:left-5 z-20 flex-col gap-1.5 sm:gap-2">
                        <div class="flex flex-col rounded-xl bg-white/95 backdrop-blur-md shadow-float border border-gray-100 overflow-hidden">
                            <button type="button" id="landing-zoom-in" aria-label="Perbesar Peta" class="flex size-8 sm:size-9 items-center justify-center text-ink hover:bg-surface-mint hover:text-primary font-bold text-base border-b border-gray-100 cursor-pointer">+</button>
                            <button type="button" id="landing-zoom-out" aria-label="Perkecil Peta" class="flex size-8 sm:size-9 items-center justify-center text-ink hover:bg-surface-mint hover:text-primary font-bold text-base cursor-pointer">&minus;</button>
                        </div>
                        <button type="button" id="landing-zoom-reset" aria-label="Pusatkan Peta" title="Pusatkan ke Kota Bogor" class="flex size-8 sm:size-9 cursor-pointer items-center justify-center rounded-xl bg-white/95 backdrop-blur-md text-ink shadow-float border border-gray-100 hover:bg-primary hover:text-white transition-colors">
                            <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" d="M12 2v3m0 14v3M2 12h3m14 0h3"/></svg>
                        </button>
                    </div>
                </div>

                {{-- Tombol Fullscreen / Buka Dashboard di Kanan Atas Peta (Desktop) --}}
                <a href="{{ route('peta') }}" title="Buka Dashboard Peta Penuh"
                   class="hidden sm:flex absolute right-5 top-5 z-20 size-10 items-center justify-center rounded-xl bg-white text-ink shadow-float border border-gray-100 hover:bg-primary hover:text-white transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75v4.5m0-4.5h4.5m-4.5 0L9 9M3.75 20.25v-4.5m0 4.5h4.5m-4.5 0L9 15M20.25 3.75h-4.5m4.5 0v4.5m0-4.5L15 9m5.25 11.25h-4.5m4.5 0v-4.5m0 4.5L15 15"/></svg>
                </a>

                {{-- Kartu Info Puskesmas: Panel Bawah Peta di HP, Kartu Melayang Kanan di Desktop --}}
                <div id="map-info-card" class="relative sm:absolute z-20 w-full sm:w-[275px] sm:rounded-2xl bg-white sm:bg-white/95 sm:backdrop-blur-md p-4 shadow-float border-t sm:border border-gray-100 transition-all duration-200 sm:right-6 sm:top-1/2 sm:-translate-y-1/2">
                    <div class="flex items-center justify-between mb-1">
                        <span class="inline-block rounded-full bg-surface-mint px-2.5 py-0.5 text-[10px] font-extrabold text-primary-dark">
                            Sorotan Peta
                        </span>
                        <span id="info-card-score" class="inline-flex items-center gap-1 text-xs font-extrabold text-primary">
                            <svg class="size-3.5 text-primary fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            <span>Nakes 100%</span>
                        </span>
                    </div>
                    <h3 id="info-card-name" class="text-sm sm:text-[15px] font-extrabold text-ink">Puskesmas Tanah Sareal</h3>
                    <p id="info-card-distance" class="text-[11px] sm:text-xs text-muted">Kec. Tanah Sareal · Jarak 1,1 km</p>
                    <div id="info-card-tags" class="mt-2 flex flex-wrap gap-1.5">
                        <x-tag>1,1 km</x-tag><x-tag>Buka</x-tag><x-tag>IGD 24 jam</x-tag><x-tag>Rawat inap</x-tag>
                    </div>
                    <div id="info-card-crowd">
                        <x-crowd-bar level="Sedang" class="mt-3" />
                    </div>
                    <div class="mt-3.5 grid grid-cols-2 sm:grid-cols-1 gap-2">
                        <x-button :href="route('peta')" variant="primary" class="w-full font-bold text-[11px] sm:text-xs h-9 sm:h-10">Buka di Peta GIS</x-button>
                        <x-button href="https://dinkes.kotabogor.go.id" target="_blank" variant="outline" class="w-full font-bold text-[11px] sm:text-xs h-9 sm:h-10">Portal resmi</x-button>
                    </div>
                </div>

                {{-- Pill Badge Bawah Tengah ("Klik pin puskesmas di peta") --}}
                <div class="hidden md:flex absolute bottom-5 left-1/2 -translate-x-1/2 z-20 items-center gap-2.5 rounded-full bg-white/95 backdrop-blur-md px-4 py-2 shadow-float border border-gray-100 text-xs font-bold text-ink">
                    <span class="flex size-5 items-center justify-center rounded-md bg-accent text-white">
                        <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/></svg>
                    </span>
                    <span>Peta jalan interaktif · Klik pin untuk sorotan detail</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ================= FOOTER INTERAKTIF (SESUAI REFERENSI CHAUMES-EN-RETZ DENGAN PALET PUSKESMAP) ================= --}}
    <footer class="bg-[#084747] text-white pt-16 pb-10 border-t border-white/10">
        <div class="mx-auto max-w-[1180px] px-6">
            <div class="grid grid-cols-1 lg:grid-cols-[270px_1fr_52px] gap-10 lg:gap-9 items-center">
                {{-- Kolom Kiri: Brand Terpusat, Tombol Pill Oranye, dan Identitas Instansi (Persis Referensi Chaumes-en-Retz) --}}
                <div class="flex flex-col items-center text-center">
                    <span class="text-[10px] font-bold uppercase tracking-[0.38em] text-white/75">
                        KOTA BOGOR
                    </span>
                    <a href="#beranda" class="mt-1 flex flex-col items-center group">
                        <span class="block text-4xl sm:text-[42px] font-extrabold tracking-tight text-white leading-none">
                            PuskesMap
                        </span>
                        <span class="block font-serif italic text-xl sm:text-2xl font-bold text-surface-sky -mt-1">
                            WebGIS &amp; SDSS
                        </span>
                    </a>

                    {{-- Tombol Pill Oranye ("NOUS CONTACTER" -> "BUKA PETA GIS") --}}
                    <a href="{{ route('peta') }}"
                       class="mt-6 inline-flex items-center justify-center gap-2.5 rounded-full bg-accent hover:bg-accent-strong px-8 py-3.5 text-xs font-extrabold uppercase tracking-wider text-white shadow-lg transition-all hover:scale-105">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect width="20" height="16" x="2" y="4" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/></svg>
                        <span>HUBUNGI &amp; PETA GIS</span>
                    </a>

                    {{-- Emblem Instansi di Bawah Tombol Oranye (Seperti "PORNIC agglo PAYS DE RETZ") --}}
                    <div class="mt-7 flex items-center justify-center gap-2.5 opacity-85">
                        <div class="text-right leading-tight">
                            <span class="block text-xs font-extrabold tracking-wider text-white">DINKES</span>
                            <span class="block text-[9px] font-bold uppercase tracking-widest text-surface-sky">KOTA BOGOR</span>
                        </div>
                        <div class="flex size-9 items-center justify-center rounded-full border border-white/25 bg-white/10 text-surface-sky">
                            <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-5 w-auto brightness-125">
                        </div>
                    </div>
                </div>

                {{-- Kolom Tengah: Kotak Berbingkai dengan 3 Tab di Kiri & Detail di Kanan --}}
                <div class="rounded-2xl border border-white/20 bg-[#063b3b]/50 grid grid-cols-1 md:grid-cols-[270px_1fr] shadow-[0_20px_50px_rgba(0,0,0,0.18)]">
                    {{-- Sub-Kolom Kiri: 3 Tab Lokasi/Wilayah --}}
                    <div class="relative z-20 flex flex-col divide-y divide-white/15 md:border-r md:border-white/15" role="tablist" aria-label="Pilih Wilayah Layanan">
                        @foreach ($footerTabs as $index => $tab)
                            <button type="button"
                                    role="tab"
                                    data-footer-tab
                                    data-tab-title="{{ $tab['title'] }}"
                                    data-tab-address="{{ $tab['address'] }}"
                                    data-tab-phone="{{ $tab['phone'] }}"
                                    data-tab-hours="{{ $tab['hours'] }}"
                                    aria-selected="{{ $index === 0 ? 'true' : 'false' }}"
                                    class="group relative flex flex-col items-start justify-center pl-5 pr-14 md:px-6 py-4 md:py-5 text-left transition-colors cursor-pointer hover:bg-white/10 aria-selected:bg-[#166363] first:rounded-t-2xl md:first:rounded-tr-none md:last:rounded-bl-2xl focus:outline-none">
                                <span class="text-xs font-medium text-white/70">{{ $tab['subtitle'] }}</span>
                                <span class="mt-0.5 text-sm sm:text-[15px] font-extrabold text-white leading-snug">{{ $tab['title'] }}</span>

                                {{-- Indikator Lingkaran Oranye '>' di Garis Pembatas (Tampil di HP & Desktop) --}}
                                <span class="flex absolute right-4 md:-right-4 top-1/2 -translate-y-1/2 z-30 size-7 md:size-8 items-center justify-center rounded-full bg-accent text-white shadow-lg transition-all opacity-0 scale-75 group-aria-selected:opacity-100 group-aria-selected:scale-100">
                                    <svg class="size-3.5 md:size-4 rotate-90 md:rotate-0" fill="none" stroke="currentColor" stroke-width="2.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                                </span>
                            </button>
                        @endforeach
                    </div>

                    {{-- Sub-Kolom Kanan: Konten Dinamis Berdasarkan Tab yang Dipilih --}}
                    <div class="relative z-10 p-6 sm:p-8 lg:px-10 flex flex-col justify-center">
                        <p id="footer-tab-address" class="text-sm sm:text-[15px] font-medium text-white/95 leading-relaxed max-w-lg">
                            {{ $footerTabs[0]['address'] }}
                        </p>

                        {{-- Badge Pill Telepon Transparan --}}
                        <div class="mt-4">
                            <a href="tel:02518331753" class="inline-flex items-center gap-2.5 rounded-full bg-[#166363] hover:bg-[#1c7575] border border-white/15 px-4 py-2 text-xs sm:text-sm font-bold text-white transition-colors">
                                <svg class="size-4 text-surface-sky shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/></svg>
                                <span id="footer-tab-phone">{{ $footerTabs[0]['phone'] }}</span>
                            </a>
                        </div>

                        <div class="mt-6">
                            <h3 class="text-sm font-extrabold text-white">Jam Operasional Layanan</h3>
                            <p id="footer-tab-hours" class="mt-1 text-xs sm:text-sm text-white/80 font-medium leading-relaxed">
                                {{ $footerTabs[0]['hours'] }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Deretan Vertikal 4 Tombol Bulat (1 Oranye Lonceng + 3 Putih Sosial Media seperti Referensi) --}}
                <div class="flex flex-row lg:flex-col items-center justify-center gap-3.5">
                    <a href="{{ route('peta') }}" aria-label="Notifikasi & Siaga IGD 24 Jam" title="Siaga IGD 24 Jam"
                       class="flex size-10 items-center justify-center rounded-full bg-accent hover:bg-accent-strong text-white shadow-md transition-transform hover:scale-110">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/></svg>
                    </a>
                    <a href="https://facebook.com" target="_blank" rel="noopener" aria-label="Facebook Dinkes Kota Bogor" title="Facebook"
                       class="flex size-10 items-center justify-center rounded-full bg-white hover:bg-surface-mint text-[#084747] shadow-sm transition-transform hover:scale-110">
                        <svg class="size-4 fill-current" viewBox="0 0 24 24"><path d="M13.5 9H16V6h-2.5C11.57 6 10 7.57 10 9.5V12H8v3h2v6h3v-6h2.5l.5-3H13V9.5c0-.28.22-.5.5-.5z"/></svg>
                    </a>
                    <a href="https://instagram.com/dinkeskotabogor" target="_blank" rel="noopener" aria-label="Instagram Dinkes Kota Bogor" title="Instagram"
                       class="flex size-10 items-center justify-center rounded-full bg-white hover:bg-surface-mint text-[#084747] shadow-sm transition-transform hover:scale-110">
                        <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><rect width="18" height="18" x="3" y="3" rx="5"/><circle cx="12" cy="12" r="4"/><circle cx="17.5" cy="6.5" r="1" fill="currentColor"/></svg>
                    </a>
                    <a href="https://youtube.com" target="_blank" rel="noopener" aria-label="YouTube Dinkes Kota Bogor" title="YouTube"
                       class="flex size-10 items-center justify-center rounded-full bg-white hover:bg-surface-mint text-[#084747] shadow-sm transition-transform hover:scale-110">
                        <svg class="size-4 fill-current" viewBox="0 0 24 24"><path d="M21.58 7.19a2.75 2.75 0 0 0-1.94-1.94C17.93 4.8 12 4.8 12 4.8s-5.93 0-7.64.45a2.75 2.75 0 0 0-1.94 1.94C2 8.9 2 12 2 12s0 3.1.42 4.81a2.75 2.75 0 0 0 1.94 1.94c1.71.45 7.64.45 7.64.45s5.93 0 7.64-.45a2.75 2.75 0 0 0 1.94-1.94C22 15.1 22 12 22 12s0-3.1-.42-4.81zM10 15V9l5.2 3-5.2 3z"/></svg>
                    </a>
                </div>
            </div>

            {{-- Baris Navigasi Bawah (Horizontal Sebaris seperti Referensi) --}}
            <div class="mt-10 sm:mt-14 pt-6 border-t border-white/10 flex flex-wrap items-center justify-center sm:justify-between gap-x-5 gap-y-2.5 text-center sm:text-left text-xs font-medium text-white/65">
                @foreach ($footerLinks as $link)
                    <a href="{{ $link['href'] }}" class="hover:text-white transition-colors">
                        {{ $link['label'] }}
                    </a>
                @endforeach
                <span class="text-white/50">&copy; {{ date('Y') }} PuskesMap Kota Bogor</span>
            </div>
        </div>
    </footer>
</x-layout>
