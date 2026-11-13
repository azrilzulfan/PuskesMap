@php
    $navLinks = [
        ['label' => 'Tentang', 'href' => '#wilayah'],
        ['label' => 'Fitur', 'href' => '#fitur'],
        ['label' => 'Puskesmas', 'href' => '#cari'],
    ];

    $featureBubblesLeft = [
        [
            'label' => 'IGD 24 Jam',
            'icon' => 'shield-plus',
            'bg' => 'from-[#45b884] to-[#2e9e6b]',
            'text' => 'text-white',
            'iconWrap' => 'bg-white/22 text-white',
            'shadow' => 'shadow-[0_18px_36px_-8px_rgba(46,158,107,0.40)]',
            'pos' => 'lg:absolute lg:left-[3%] xl:left-[5%] lg:top-[10%]',
            'tilt' => '-rotate-6',
            'float' => 'float-soft',
            'delay' => 80,
        ],
        [
            'label' => 'Radius 1–5 km',
            'icon' => 'radar-pin',
            'bg' => 'from-[#0e9191] to-[#007979]',
            'text' => 'text-white',
            'iconWrap' => 'bg-white/22 text-white',
            'shadow' => 'shadow-[0_18px_36px_-8px_rgba(0,121,121,0.40)]',
            'pos' => 'lg:absolute lg:left-[21%] xl:left-[23%] lg:top-[0%]',
            'tilt' => 'rotate-3',
            'float' => 'float-soft-alt',
            'delay' => 160,
        ],
        [
            'label' => 'Rekomendasi Terdekat',
            'icon' => 'sparkles-target',
            'bg' => 'from-[#ee884a] to-[#e37434]',
            'text' => 'text-white',
            'iconWrap' => 'bg-white/22 text-white',
            'shadow' => 'shadow-[0_20px_38px_-8px_rgba(227,116,52,0.44)]',
            'pos' => 'lg:absolute lg:left-[1%] xl:left-[2%] lg:bottom-[14%]',
            'tilt' => '-rotate-3',
            'float' => 'float-soft',
            'delay' => 240,
        ],
    ];

    $featureBubblesRight = [
        [
            'label' => '9 Profesi Nakes',
            'icon' => 'medical-team',
            'bg' => 'from-[#ffe8be] to-[#ffd691]',
            'text' => 'text-accent-strong',
            'iconWrap' => 'bg-white/65 text-accent-strong',
            'shadow' => 'shadow-[0_18px_34px_-8px_rgba(227,116,52,0.32)]',
            'pos' => 'lg:absolute lg:right-[3%] xl:right-[5%] lg:top-[9%]',
            'tilt' => 'rotate-6',
            'float' => 'float-soft-alt',
            'delay' => 120,
        ],
        [
            'label' => 'Peta WebGIS',
            'icon' => 'map-gis',
            'bg' => 'from-[#cdecf4] to-[#bfe6ef]',
            'text' => 'text-primary-dark',
            'iconWrap' => 'bg-white/70 text-primary-dark',
            'shadow' => 'shadow-[0_18px_34px_-8px_rgba(0,121,121,0.28)]',
            'pos' => 'lg:absolute lg:right-[1%] xl:right-[2%] lg:bottom-[14%]',
            'tilt' => '-rotate-3',
            'float' => 'float-soft',
            'delay' => 200,
        ],
        [
            'label' => 'Info Rawat Inap',
            'icon' => 'clinic-bed',
            'bg' => 'from-[#0e7070] to-[#0b5c5c]',
            'text' => 'text-white',
            'iconWrap' => 'bg-white/20 text-white',
            'shadow' => 'shadow-[0_18px_36px_-8px_rgba(11,92,92,0.40)]',
            'pos' => 'lg:absolute lg:right-[20%] xl:right-[22%] lg:bottom-[0%]',
            'tilt' => '-rotate-4',
            'float' => 'float-soft-alt',
            'delay' => 280,
        ],
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
        ['tag' => 'Rekomendasi Lokasi', 'title' => 'Rekomendasi Puskesmas Terdekat & Terbaik dari Lokasi Anda', 'meta' => 'Pilihan Cerdas', 'highlight' => false],
        ['tag' => 'Radius Spasial', 'title' => 'Filter Jangkauan Geodetik 1 km, 3 km, hingga 5 km dari Lokasimu', 'meta' => 'WebGIS Interaktif', 'highlight' => false],
        ['tag' => 'Analitik SDSS', 'title' => 'Pemetaan Indeks Beban Wilayah (PSW-Index) 6 Kecamatan', 'meta' => 'Dinas Kesehatan', 'highlight' => false],
    ];

    $cardColors = ['bg-card-mint', 'bg-card-peach', 'bg-card-green', 'bg-card-salmon', 'bg-card-blue'];

    /**
     * Data kartu puskesmas pada landing page.
     * Tempat upload gambar masing-masing puskesmas:
     * Simpan file foto ke `public/images/puskesmas/` lalu sesuaikan path pada key `'image'` di bawah ini.
     */
    $puskesmas = [
        [
            'name' => 'Puskesmas Tanah Sareal',
            'district' => 'Tanah Sareal',
            'address' => 'Jl. Kesehatan No. 1, Tanah Sareal',
            'distance' => '1,1 km',
            'hours' => '07.30 – 16.00 WIB',
            'igd' => true,
            'inap' => true,
            'nakes' => '100%',
            'crowd' => 'Sedang',
            'image' => 'images/puskesmas/puskesmas-tanah-sareal.jpg',
        ],
        [
            'name' => 'Puskesmas Sempur',
            'district' => 'Bogor Tengah',
            'address' => 'Jl. Sempur Kaler No. 12, Bogor Tengah',
            'distance' => '1,2 km',
            'hours' => '07.30 – 14.30 WIB',
            'igd' => false,
            'inap' => false,
            'nakes' => '89%',
            'crowd' => 'Sepi',
            'image' => 'images/puskesmas/puskesmas-sempur.jpg',
        ],
        [
            'name' => 'Puskesmas Kedung Badak',
            'district' => 'Tanah Sareal',
            'address' => 'Jl. Kol. Enjo Martadisastra, Kedung Badak',
            'distance' => '1,5 km',
            'hours' => 'Buka 24 Jam (IGD)',
            'igd' => true,
            'inap' => false,
            'nakes' => '100%',
            'crowd' => 'Sedang',
            'image' => 'images/puskesmas/puskesmas-kedung-badak.jpg',
        ],
        [
            'name' => 'Puskesmas Mekarwangi',
            'district' => 'Tanah Sareal',
            'address' => 'Perum Bukit Cimanggu City, Mekarwangi',
            'distance' => '2,0 km',
            'hours' => 'Buka 24 Jam (IGD)',
            'igd' => true,
            'inap' => true,
            'nakes' => '100%',
            'crowd' => 'Ramai',
            'image' => 'images/puskesmas/puskesmas-mekarwangi.jpg',
        ],
        [
            'name' => 'Puskesmas Pasir Mulya',
            'district' => 'Bogor Barat',
            'address' => 'Jl. Gunung Batu No. 45, Pasir Mulya',
            'distance' => '2,3 km',
            'hours' => 'Buka 24 Jam (IGD)',
            'igd' => true,
            'inap' => true,
            'nakes' => '100%',
            'crowd' => 'Sepi',
            'image' => 'images/puskesmas/puskesmas-pasir-mulya.jpg',
        ],
        [
            'name' => 'Puskesmas Bogor Timur',
            'district' => 'Bogor Timur',
            'address' => 'Jl. Pakuan No. 8, Baranangsiang',
            'distance' => '2,4 km',
            'hours' => 'Buka 24 Jam (IGD)',
            'igd' => true,
            'inap' => true,
            'nakes' => '100%',
            'crowd' => 'Sedang',
            'image' => 'images/puskesmas/puskesmas-bogor-timur.jpg',
        ],
        [
            'name' => 'Puskesmas Bogor Utara',
            'district' => 'Bogor Utara',
            'address' => 'Jl. Kresna Raya No. 14, Bantarjati',
            'distance' => '2,6 km',
            'hours' => 'Buka 24 Jam (IGD)',
            'igd' => true,
            'inap' => true,
            'nakes' => '100%',
            'crowd' => 'Sedang',
            'image' => 'images/puskesmas/puskesmas-bogor-utara.jpg',
        ],
        [
            'name' => 'Puskesmas Bogor Selatan',
            'district' => 'Bogor Selatan',
            'address' => 'Jl. Batutulis No. 40, Bogor Selatan',
            'distance' => '2,8 km',
            'hours' => 'Buka 24 Jam (IGD)',
            'igd' => true,
            'inap' => true,
            'nakes' => '100%',
            'crowd' => 'Sedang',
            'image' => 'images/puskesmas/puskesmas-bogor-selatan.jpg',
        ],
    ];

    $hashtags = ['Semua', 'IGD 24 Jam', 'Rawat Inap', 'Buka Sekarang'];
    $footerLinks = [
        ['label' => 'Beranda', 'href' => '#beranda'],
        ['label' => 'Wilayah Cakupan', 'href' => '#wilayah'],
        ['label' => 'Daftar 25 Puskesmas', 'href' => '#cari'],
        ['label' => 'Pratinjau Peta', 'href' => '#peta'],
        ['label' => 'Peta & Rekomendasi Terdekat', 'href' => route('peta')],
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
    {{-- ================= HERO SECTION ================= --}}
    <header id="beranda" class="relative z-20 bg-white">
        {{-- Floating Pill Navbar Desktop (Fixed di Atas, Smooth & Ringan) --}}
        <nav id="main-navbar"
             class="hidden md:flex fixed left-1/2 top-5 z-50 w-[calc(100%-2rem)] max-w-[1040px] -translate-x-1/2 flex-col items-center pointer-events-none"
             aria-label="Navigasi utama desktop">
            <div id="nav-pill"
                 class="nav-pill pointer-events-auto flex h-[68px] w-full items-center rounded-full bg-white/95 backdrop-blur-md pl-8 pr-4 shadow-nav border border-white/80">
                <a href="#beranda"
                   class="nav-brand group mr-auto flex items-center gap-2.5 shrink-0"
                   aria-label="PuskesMap - Kembali ke atas"
                   title="PuskesMap">
                    <img src="{{ asset('images/logo-sm.png') }}" alt="" class="nav-brand-logo size-9 object-contain transition-transform duration-300 group-hover:scale-110">
                    <span class="nav-brand-text whitespace-nowrap text-xl font-extrabold tracking-tight text-primary">PuskesMap</span>
                </a>

                <div class="nav-actions flex items-center gap-5 py-1.5 overflow-hidden">
                    <ul id="nav-links-list" class="relative flex items-center gap-1 text-[15px] font-bold text-body">
                        <span id="nav-hover-pill" aria-hidden="true"
                              class="pointer-events-none absolute left-0 top-1/2 h-9 rounded-full bg-surface-mint border border-primary/15 shadow-[0_2px_10px_rgba(0,121,121,0.10)] opacity-0 transition-[transform,width,opacity] duration-250 ease-out will-change-transform"></span>
                        @foreach ($navLinks as $link)
                            <li>
                                <a href="{{ $link['href'] }}" data-nav-link
                                   class="nav-link group relative z-10 inline-flex items-center px-4 py-2 rounded-full transition-[color,transform] duration-200 ease-out hover:text-primary-dark hover:-translate-y-0.5 active:scale-95">
                                    <span class="relative">
                                        {{ $link['label'] }}
                                        <span aria-hidden="true"
                                              class="nav-link-bar absolute -bottom-1 left-0 h-[2.5px] w-full origin-center scale-x-0 rounded-full bg-gradient-to-r from-primary to-accent transition-transform duration-250 ease-out group-hover:scale-x-100"></span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <a href="{{ route('peta') }}"
                       class="inline-flex h-11 shrink-0 items-center justify-center rounded-full bg-accent px-6 text-[15px] font-bold text-white shadow-xs transition-[background-color,transform,box-shadow] duration-200 hover:bg-accent-strong hover:-translate-y-0.5 hover:shadow-[0_8px_20px_rgba(227,116,52,0.34)] active:scale-95">
                        <span>Peta</span>
                    </a>
                </div>
            </div>
        </nav>

        {{-- Floating Sliding Curved-Notch Bottom Navbar Khusus Mobile (< 768px) --}}
        <nav id="mobile-bottom-nav"
             class="md:hidden fixed inset-x-0 bottom-3.5 z-50 px-3.5 pointer-events-none select-none"
             aria-label="Navigasi bawah mobile">
            <div id="mobile-dock-bar" class="mobile-dock-bar relative mx-auto w-full max-w-[404px] h-[64px] pointer-events-auto">
                {{-- Background Bar Putih dengan Cekungan Melengkung yang Ikut Bergeser --}}
                <div class="mobile-dock-shadow pointer-events-none absolute inset-0" aria-hidden="true">
                    <div class="relative h-full w-full overflow-hidden rounded-[22px]">
                        <div id="mobile-dock-notch-track" class="mobile-dock-notch-track absolute inset-y-0 left-0 flex w-[200%]">
                            <div class="flex-1 bg-white"></div>
                            <svg class="block h-full w-[82px] shrink-0 -mx-px" viewBox="0 0 82 64" preserveAspectRatio="none">
                                <path d="M0,0 C15,0 17,33 41,33 C65,33 67,0 82,0 L82,64 L0,64 Z" fill="#ffffff" />
                            </svg>
                            <div class="flex-1 bg-white"></div>
                        </div>
                    </div>
                </div>

                {{-- Lingkaran Teal PuskesMap Melayang yang Ikut Menggeser Bersama Cekungan --}}
                <div id="mobile-dock-orb"
                     class="mobile-dock-orb pointer-events-none absolute -top-[22px] left-0 z-10 -ml-[24px] size-[48px] rounded-full bg-gradient-to-b from-primary to-primary-dark shadow-[0_10px_24px_rgba(0,121,121,0.36)] ring-2 ring-white/80"
                     aria-hidden="true"></div>

                {{-- 5 Item Navigasi Mobile (Peta di Tengah, Ikon & Label Rata Tengah dengan Padding Seimbang) --}}
                <div class="relative z-20 grid h-full grid-cols-5 items-stretch px-3">
                    <a href="#beranda" data-mobile-dock-link="#beranda"
                       class="mobile-dock-link is-active relative flex flex-col items-center justify-center gap-[5px]">
                        <svg class="mobile-dock-icon size-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path d="M11.47 3.841a.75.75 0 0 1 1.06 0l8.69 8.69a.75.75 0 1 0 1.06-1.061l-8.689-8.69a2.25 2.25 0 0 0-3.182 0l-8.69 8.69a.75.75 0 1 0 1.061 1.06l8.69-8.689Z" />
                            <path d="m12 5.432 8.159 8.159c.03.03.06.058.091.086v6.198c0 1.035-.84 1.875-1.875 1.875H15a.75.75 0 0 1-.75-.75v-4.5a.75.75 0 0 0-.75-.75h-3a.75.75 0 0 0-.75.75V21a.75.75 0 0 1-.75.75H5.625a1.875 1.875 0 0 1-1.875-1.875v-6.198a2.29 2.29 0 0 0 .091-.086L12 5.432Z" />
                        </svg>
                        <span class="text-[10.5px] leading-none tracking-tight">Beranda</span>
                    </a>

                    <a href="#wilayah" data-mobile-dock-link="#wilayah"
                       class="mobile-dock-link relative flex flex-col items-center justify-center gap-[5px]">
                        <svg class="mobile-dock-icon size-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="m11.54 22.351.07.04.028.016a.76.76 0 0 0 .723 0l.028-.015.071-.041a16.975 16.975 0 0 0 1.144-.742 19.58 19.58 0 0 0 2.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 0 0-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 0 0 2.682 2.282 16.975 16.975 0 0 0 1.145.742ZM12 13.5a3 3 0 1 0 0-6 3 3 0 0 0 0 6Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-[10.5px] leading-none tracking-tight">Wilayah</span>
                    </a>

                    <a href="{{ route('peta') }}" data-mobile-dock-link="#peta" data-mobile-dock-route="{{ route('peta') }}"
                       class="mobile-dock-link relative flex flex-col items-center justify-center gap-[5px]">
                        <svg class="mobile-dock-icon size-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M8.161 2.58a1.875 1.875 0 0 1 1.678 0l4.993 2.498c.106.052.23.052.336 0l3.869-1.935A1.875 1.875 0 0 1 21.75 4.82v12.485c0 .71-.401 1.36-1.037 1.677l-4.875 2.437a1.875 1.875 0 0 1-1.676 0l-4.994-2.497a.375.375 0 0 0-.336 0l-3.868 1.935A1.875 1.875 0 0 1 2.25 19.18V6.695c0-.71.401-1.36 1.036-1.677l4.875-2.437ZM9 6a.75.75 0 0 1 .75.75V15a.75.75 0 0 1-1.5 0V6.75A.75.75 0 0 1 9 6Zm6.75 3a.75.75 0 0 0-1.5 0v8.25a.75.75 0 0 0 1.5 0V9Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-[10.5px] leading-none tracking-tight">Peta</span>
                    </a>

                    <a href="#fitur" data-mobile-dock-link="#fitur"
                       class="mobile-dock-link relative flex flex-col items-center justify-center gap-[5px]">
                        <svg class="mobile-dock-icon size-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M3 6a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3V6Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3v2.25a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3V6ZM3 15.75a3 3 0 0 1 3-3h2.25a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3H6a3 3 0 0 1-3-3v-2.25Zm9.75 0a3 3 0 0 1 3-3H18a3 3 0 0 1 3 3V18a3 3 0 0 1-3 3h-2.25a3 3 0 0 1-3-3v-2.25Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-[10.5px] leading-none tracking-tight">Fitur</span>
                    </a>

                    <a href="#cari" data-mobile-dock-link="#cari"
                       class="mobile-dock-link relative flex flex-col items-center justify-center gap-[5px]">
                        <svg class="mobile-dock-icon size-5 shrink-0" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path fill-rule="evenodd" d="M4.5 2.25a.75.75 0 0 0 0 1.5v16.5h-.75a.75.75 0 0 0 0 1.5h16.5a.75.75 0 0 0 0-1.5h-.75V3.75a.75.75 0 0 0 0-1.5h-15ZM9 6a.75.75 0 0 0 0 1.5h1.5A.75.75 0 0 0 11.25 9v1.5a.75.75 0 0 0 1.5 0V9a.75.75 0 0 0 .75-.75H15a.75.75 0 0 0 0-1.5h-1.5A.75.75 0 0 0 12.75 5.25V3.75a.75.75 0 0 0-1.5 0v1.5A.75.75 0 0 0 10.5 6H9Zm-1.5 9.75A.75.75 0 0 1 8.25 15h7.5a.75.75 0 0 1 .75.75v4.5h-9v-4.5Z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-[10.5px] leading-none tracking-tight">Faskes</span>
                    </a>
                </div>
            </div>
        </nav>

        <div class="relative h-[360px] sm:h-auto sm:aspect-[1170/760] sm:min-h-[520px] w-full overflow-hidden [clip-path:ellipse(140%_100%_at_50%_0%)] sm:[clip-path:ellipse(92%_100%_at_50%_0%)]">
            {{-- Ilustrasi Utama Kota Bogor --}}
            <div id="hero-lottie" data-parallax="0.1" data-parallax-max="40" role="img" aria-label="Ilustrasi Kota Bogor"
                 data-src="{{ asset('images/main-hero.json') }}"
                 class="absolute left-1/2 -translate-x-1/2 w-[155%] sm:left-[-11.54%] sm:translate-x-0 top-0 h-[112%] sm:h-[115%] sm:w-[123.08%] max-w-none"></div>
            <script src="https://cdn.jsdelivr.net/npm/lottie-web@5.12.2/build/player/lottie_svg.min.js"></script>
            <script>
                (function () {
                    var el = document.getElementById('hero-lottie');
                    if (!el || !window.lottie) { return; }
                    var reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
                    window.lottie.loadAnimation({
                        container: el,
                        renderer: 'svg',
                        loop: true,
                        autoplay: !reduceMotion,
                        path: el.dataset.src,
                        rendererSettings: { preserveAspectRatio: 'xMidYMin slice' }
                    });
                })();
            </script>

            {{-- Floating Decorative Badges & Judul Hero di Area Langit/Awan --}}
            <div data-parallax-title class="absolute inset-x-0 top-[15%] sm:top-[19%] lg:top-[20%] z-10 flex flex-col items-center px-4 text-center">
                <div class="relative inline-flex items-center gap-2 sm:gap-5">
                    {{-- Badge Dekoratif Kiri --}}
                    <span class="float-soft flex absolute -left-7 -top-2 sm:-left-16 sm:-top-5 size-6 sm:size-12 items-center justify-center rounded-lg sm:rounded-2xl bg-primary/90 text-white shadow-lg -rotate-12 backdrop-blur-xs" aria-hidden="true">
                        <svg class="size-3.5 sm:size-6" fill="currentColor" viewBox="0 0 24 24"><path d="M11.645 20.91l-.007-.003-.022-.012a15.247 15.247 0 01-.383-.218 25.18 25.18 0 01-4.244-3.17C4.688 15.36 2.25 12.174 2.25 8.25 2.25 5.322 4.714 3 7.688 3A5.5 5.5 0 0112 5.052 5.5 5.5 0 0116.313 3c2.973 0 5.437 2.322 5.437 5.25 0 3.925-2.438 7.111-4.739 9.256a25.175 25.175 0 01-4.244 3.17 15.247 15.247 0 01-.383.219l-.022.012-.007.004-.003.001a.752.752 0 01-.704 0l-.003-.001z"/></svg>
                    </span>

                    <img data-reveal="pop" data-reveal-delay="150" src="{{ asset('images/logo-sm.png') }}" alt="" class="h-9 w-auto sm:h-[86px] lg:h-[96px] drop-shadow-md">
                    <h1 data-reveal="up" data-reveal-delay="300" class="text-3xl font-extrabold tracking-tight leading-none text-primary-dark sm:text-6xl lg:text-[74px] drop-shadow-xs">
                        PuskesMap
                    </h1>

                    {{-- Badge Dekoratif Kanan --}}
                    <span class="float-soft-alt flex absolute -right-7 -bottom-1.5 sm:-right-16 sm:-bottom-3 size-6 sm:size-12 items-center justify-center rounded-lg sm:rounded-2xl bg-accent/95 text-white shadow-lg rotate-12 backdrop-blur-xs" aria-hidden="true">
                        <svg class="size-3.5 sm:size-6" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/></svg>
                    </span>
                </div>

                <p data-reveal="up" data-reveal-delay="500" class="mt-2.5 sm:mt-4 max-w-[92vw] rounded-full bg-white/88 px-3.5 py-1 sm:px-7 sm:py-2 text-[11px] sm:text-lg lg:text-xl font-extrabold text-primary-dark shadow-sm backdrop-blur-md border border-white">
                    Peta puskesmas Kota Bogor, di satu tempat
                </p>
            </div>
        </div>
    </header>

    {{-- ================= SECTION WILAYAH (OVERLAPPING / NIMPA DENGAN PALET PUSKESMAP) ================= --}}
    <section id="wilayah" class="relative z-10 w-full bg-white overflow-hidden -mt-16 sm:-mt-24 lg:-mt-36 pt-16 sm:pt-24 lg:pt-36 pb-10 sm:pb-14 lg:pb-16">
        {{-- Canvas Partikel Interaktif Khusus Wilayah (Hanya di background putih) --}}
        <canvas id="wilayah-particles"
                data-cursor-particles
                data-particle-theme="light"
                aria-hidden="true"
                class="pointer-events-none absolute inset-0 z-0 h-full w-full"></canvas>

        <div class="relative z-10 mx-auto w-full max-w-[1180px] px-4 sm:px-6 lg:px-8">
            <div class="grid items-center gap-0 lg:grid-cols-[1.05fr_1fr]">
                {{-- Kartu "Wilayah" di Kiri (Diperpanjang ke Kanan Sampai ke Belakang Peta) --}}
                <div id="wilayah-card" data-reveal="left" class="relative z-10 rounded-[24px] bg-surface-sky p-6 pb-11 sm:p-10 sm:pb-14 lg:py-16 lg:pl-12 lg:pr-[430px] lg:-mr-[410px] shadow-[0_16px_40px_rgba(0,121,121,0.12)] border border-white/60">
                    <div class="lg:max-w-[460px]">
                        <h2 class="text-[32px] sm:text-[44px] lg:text-[52px] font-extrabold tracking-tight leading-[1.14] text-primary-dark">
                            Wilayah
                        </h2>

                        {{-- Garis Gelombang Putih (Wavy Underline ala Referensi Territoire) --}}
                        <svg class="mt-2.5 sm:mt-3 h-3 w-20 sm:w-24 text-white" viewBox="0 0 100 12" fill="none" aria-hidden="true">
                            <path d="M2 8 C22 1, 42 13, 64 5 C78 0, 88 8, 98 4" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                        </svg>

                        <p class="mt-3 sm:mt-4 text-base sm:text-[17px] leading-relaxed text-body font-medium">
                            PuskesMap memetakan 25 puskesmas induk di enam kecamatan Kota Bogor: Tanah Sareal, Bogor Utara,
                            Bogor Barat, Bogor Tengah, Bogor Timur, dan Bogor Selatan, dilengkapi informasi kesiapan IGD 24 jam,
                            fasilitas rawat inap, kelengkapan 9 profesi tenaga kesehatan, serta jam operasional harian.
                        </p>
                    </div>
                </div>

                {{-- Peta Vektor Kota Bogor (Palet Mint-Teal-Sage) Menimpa Kartu --}}
                <div id="wilayah-map" data-reveal="right" data-reveal-delay="150" class="relative z-20 -mt-7 sm:-mt-10 lg:-mt-32 lg:-ml-8 flex flex-col items-center px-1 sm:px-0">
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
    </section>

    {{-- ================= SECTION FITUR PUSKESMAP (FULL 1 LAYAR PUTIH + BUBBLE ELEMEN SVG + PARTIKEL KURSOR) ================= --}}
    <section id="fitur" class="relative bg-white min-h-[84svh] lg:min-h-[90svh] flex flex-col justify-between pt-6 sm:pt-10 lg:pt-12 overflow-hidden">
        {{-- Canvas Partikel Interaktif (Bergerak acak otomatis & menghampiri kursor saat diarahkan) --}}
        <canvas id="fitur-particles"
                data-cursor-particles
                data-particle-theme="light"
                aria-hidden="true"
                class="pointer-events-none absolute inset-0 z-0 h-full w-full"></canvas>

        <div class="relative z-10 mx-auto my-auto w-full max-w-[1180px] min-h-[480px] lg:min-h-[560px] px-4 sm:px-6 lg:px-8 flex flex-col items-center justify-center gap-8 sm:gap-10 lg:gap-0 pb-4 sm:pb-6">
            {{-- Judul Utama 2 Baris Konsisten dengan Header Section Lain --}}
            <div data-reveal="up" class="order-2 relative z-10 text-center max-w-3xl px-2">
                <h2 class="text-[32px] sm:text-[44px] lg:text-[52px] font-extrabold tracking-tight leading-[1.14] text-ink">
                    <span class="block sm:whitespace-nowrap">
                        Fitur apa saja yang ada di
                    </span>
                    <span class="mt-1 block text-accent">
                        PUSKESMAP?
                    </span>
                </h2>
            </div>

            {{-- Bubble Fitur Sisi Kiri & Kanan (Tanpa Emoji, Menggunakan Elemen Ikon SVG) --}}
            @foreach (['order-1' => $featureBubblesLeft, 'order-3' => $featureBubblesRight] as $orderClass => $bubbles)
                <div class="{{ $orderClass }} flex flex-wrap justify-center gap-2.5 sm:gap-5 lg:contents">
                    @foreach ($bubbles as $bubble)
                        <div data-reveal="pop" data-reveal-delay="{{ $bubble['delay'] }}" class="{{ $bubble['pos'] }}">
                            <div class="{{ $bubble['float'] }}">
                                <div class="inline-flex items-center gap-2 sm:gap-3.5 rounded-full bg-gradient-to-b {{ $bubble['bg'] }} {{ $bubble['shadow'] }} {{ $bubble['tilt'] }} {{ $bubble['text'] }} px-4 py-2 sm:px-7 sm:py-3.5 lg:px-8 lg:py-4 ring-1 ring-white/50 transition-transform duration-300 ease-out hover:scale-105 hover:rotate-0 cursor-default select-none">
                                    <span class="flex size-7 sm:size-10 lg:size-11 shrink-0 items-center justify-center rounded-full {{ $bubble['iconWrap'] }}" aria-hidden="true">
                                        @switch($bubble['icon'])
                                            @case('shield-plus')
                                                <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2.3" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3l7 3v5.5c0 4.5-3 8.4-7 9.5-4-1.1-7-5-7-9.5V6l7-3z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8.5v7M8.5 12h7" />
                                                </svg>
                                                @break
                                            @case('radar-pin')
                                                <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 21s-6-5.6-6-10.5a6 6 0 1112 0C18 15.4 12 21 12 21z" />
                                                    <circle cx="12" cy="10.5" r="2.2" fill="currentColor" stroke="none" />
                                                </svg>
                                                @break
                                            @case('sparkles-target')
                                                <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                    <circle cx="12" cy="12" r="8" />
                                                    <circle cx="12" cy="12" r="4" />
                                                    <circle cx="12" cy="12" r="1.5" fill="currentColor" stroke="none" />
                                                </svg>
                                                @break
                                            @case('medical-team')
                                                <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                    <circle cx="9" cy="8.5" r="3" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.5 19a5.5 5.5 0 0111 0" />
                                                    <circle cx="16.5" cy="9.5" r="2.3" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 14.5a4 4 0 014 4" />
                                                </svg>
                                                @break
                                            @case('map-gis')
                                                <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5L3.5 7v12.5L9 17l6 2.5 5.5-2.5V4.5L15 7 9 4.5z" />
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5V17M15 7v12.5" />
                                                </svg>
                                                @break
                                            @case('clinic-bed')
                                                <svg class="size-4 sm:size-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 18V7m0 7h18v4M3 11h6a2 2 0 012 2v1h8a2 2 0 012 2" />
                                                    <circle cx="7" cy="9.5" r="1.5" fill="currentColor" stroke="none" />
                                                </svg>
                                                @break
                                        @endswitch
                                    </span>
                                    <span class="whitespace-nowrap text-sm sm:text-lg lg:text-xl font-extrabold tracking-tight">{{ $bubble['label'] }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>

        {{-- Organic Wave Divider Menuju Section Faskes / Kecamatan --}}
        <div class="relative z-10 -mb-px w-full overflow-hidden leading-none pointer-events-none" aria-hidden="true">
            <svg class="relative block w-full h-[42px] sm:h-[86px]" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path d="M0,15 C320,105 680,-25 1120,65 C1280,95 1380,80 1440,55 L1440,100 L0,100 Z" fill="#138282"></path>
            </svg>
        </div>
    </section>

    {{-- ================= SECTION PUSKESMAS TERPADU (HEADING + HEALTH PATTERN + MARQUEE INTERAKTIF KE KIRI) ================= --}}
    <section id="cari" class="relative bg-gradient-to-b from-[#138282] via-primary to-primary-dark text-white overflow-hidden">
        {{-- Heading Section di Dalam Area Hijau/Teal agar Terlihat 1 Section --}}
        <div class="relative z-10 mx-auto w-full max-w-[1180px] px-4 sm:px-6 lg:px-8 pt-8 sm:pt-12 pb-8 sm:pb-10 text-center">
            <h2 data-reveal="up" data-reveal-delay="80" class="text-[32px] sm:text-[44px] lg:text-[52px] font-extrabold tracking-tight leading-[1.14] text-white">
                Jangan Bingung Cari Faskes! <span class="text-card-peach drop-shadow-[0_1px_1px_rgba(0,0,0,0.2)]">PUSKESMAP</span>
            </h2>
            <p data-reveal="up" data-reveal-delay="180" class="mx-auto mt-3 max-w-2xl text-base sm:text-[17px] leading-relaxed font-medium text-white/90">
                Informasi kesiapan IGD 24 jam, tenaga kesehatan, dan operasional puskesmas induk Kota Bogor
            </p>
        </div>

        {{-- Full-Width Edge-to-Edge Marquee Berjalan Perlahan ke Kiri & Bisa Digeser Tangan --}}
        <div data-marquee class="marquee-wrap relative z-10 w-full overflow-hidden pt-2 sm:pt-3 pb-12 sm:pb-16 cursor-grab active:cursor-grabbing select-none">
            <div data-marquee-track class="marquee-track">
                @for ($copy = 0; $copy < 2; $copy++)
                    <div class="marquee-group flex shrink-0 items-stretch gap-5 sm:gap-6 pr-5 sm:pr-6" @if ($copy === 1) aria-hidden="true" @endif>
                        @foreach ($puskesmas as $item)
                            @php
                                $pastel = $cardColors[$loop->index % count($cardColors)];
                                $hasImage = ! empty($item['image']) && file_exists(public_path($item['image']));
                            @endphp
                            <article class="marquee-card group/card w-[258px] sm:w-[272px] shrink-0 rounded-2xl bg-white p-4 text-ink shadow-[0_16px_40px_rgba(7,52,52,0.22)] flex flex-col justify-between">
                                <div>
                                    {{-- Slot Foto / Ilustrasi Header Kartu Puskesmas --}}
                                    <div class="relative h-[152px] w-full rounded-xl {{ $pastel }} overflow-hidden p-3.5 flex flex-col justify-between">
                                        @if ($hasImage)
                                            <img src="{{ asset($item['image']) }}"
                                                 alt="{{ $copy === 0 ? $item['name'] : '' }}"
                                                 loading="lazy"
                                                 draggable="false"
                                                 class="absolute inset-0 h-full w-full object-cover transition-transform duration-500 group-hover/card:scale-105">
                                            <div class="absolute inset-0 bg-gradient-to-t from-ink/65 via-ink/15 to-ink/25" aria-hidden="true"></div>
                                        @endif

                                        <div class="relative z-10 flex items-center justify-between">
                                            <span class="rounded-full bg-white/95 px-2.5 py-0.5 text-[10px] font-extrabold text-primary-dark shadow-2xs transition-colors group-hover/card:text-accent-strong">
                                                Kec. {{ $item['district'] }}
                                            </span>
                                            <span class="rounded-full bg-primary-dark/90 px-2.5 py-0.5 text-[10px] font-bold text-white transition-colors group-hover/card:bg-accent-strong">
                                                {{ $item['distance'] }}
                                            </span>
                                        </div>

                                        @unless ($hasImage)
                                            {{-- Placeholder Foto Puskesmas (Otomatis diganti foto asli saat file tersedia di public/images/puskesmas/) --}}
                                            <div class="relative z-10 my-auto flex flex-col items-center justify-center gap-1.5">
                                                <div class="flex size-14 items-center justify-center rounded-2xl bg-white/85 shadow-xs backdrop-blur-xs">
                                                    <svg class="size-8 text-primary transition-colors group-hover/card:text-accent-strong" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M12 7v4m-2-2h4"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        @endunless

                                        <div class="relative z-10 flex items-center justify-between text-[10px] font-bold {{ $hasImage ? 'text-white' : 'text-primary-dark/80' }}">
                                            <span>9 Nakes: {{ $item['nakes'] }}</span>
                                            <span>{{ $item['igd'] ? '🚨 IGD 24 Jam' : '🩺 Rawat Jalan' }}</span>
                                        </div>
                                    </div>

                                    {{-- Informasi Teks Kartu --}}
                                    <div class="mt-3.5 px-1">
                                        <span class="text-[11px] font-extrabold text-primary uppercase tracking-wider transition-colors group-hover/card:text-[#ffe2af]">Puskesmas Induk</span>
                                        <h3 class="mt-0.5 text-base font-extrabold leading-snug text-ink line-clamp-1 transition-colors group-hover/card:text-white">{{ $item['name'] }}</h3>
                                        <p class="mt-1 text-xs text-muted line-clamp-1 transition-colors">📍 {{ $item['address'] }}</p>
                                        <p class="mt-1 text-[11px] font-semibold text-muted transition-colors">⏰ {{ $item['hours'] }}</p>
                                    </div>
                                </div>

                                {{-- Tombol Pill di Bawah Kartu --}}
                                <div class="mt-4 pt-1">
                                    <a href="{{ route('peta') }}"
                                       draggable="false"
                                       @if ($copy === 1) tabindex="-1" @endif
                                       class="flex h-9 w-full items-center justify-center rounded-full bg-primary text-xs font-extrabold text-white shadow-xs transition-all hover:bg-primary-dark hover:scale-[1.02] group-hover/card:shadow-md">
                                        <span>Lihat di Peta</span>
                                    </a>
                                </div>
                            </article>
                        @endforeach
                    </div>
                @endfor
            </div>
        </div>

        {{-- Bottom Organic Wave Divider Menuju Sekilas di Peta --}}
        <div class="relative z-10 w-full overflow-hidden leading-none" aria-hidden="true">
            <svg class="relative block w-full h-[40px] sm:h-[76px]" viewBox="0 0 1440 100" preserveAspectRatio="none">
                <path d="M0,60 C380,110 820,-10 1440,50 L1440,100 L0,100 Z" fill="#e8f4f2"></path>
            </svg>
        </div>
    </section>

    {{-- ================= SEKILAS DI PETA (REAL INTERACTIVE STREET MAP — RESPONSIF UNTUK HP) ================= --}}
    <section id="peta" class="bg-gradient-to-b from-surface-mint via-[#eef7f6] to-white py-12 sm:py-16 lg:py-20">
        <div class="mx-auto w-full max-w-[1180px] px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 data-reveal="up" data-reveal-delay="100" class="text-[32px] sm:text-[44px] lg:text-[52px] font-extrabold tracking-tight leading-[1.14] text-primary-dark">Sekilas di Peta</h2>
                <p data-reveal="up" data-reveal-delay="180" class="mx-auto mt-3 max-w-2xl text-base sm:text-[17px] leading-relaxed font-medium text-muted">
                    Peta jalan interaktif 25 Puskesmas Induk di 6 Kecamatan Kota Bogor
                </p>
                <div data-reveal-stagger="60" class="mt-5 sm:mt-6 flex flex-wrap justify-center gap-2">
                    @foreach ($hashtags as $tag)
                        <button type="button" data-chip aria-pressed="{{ $loop->first ? 'true' : 'false' }}"
                                class="h-8 cursor-pointer rounded-full border border-primary/20 bg-white px-3.5 sm:px-4 text-xs font-bold text-ink shadow-2xs transition-colors aria-pressed:border-primary aria-pressed:bg-primary aria-pressed:text-white">
                            {{ $tag }}
                        </button>
                    @endforeach
                </div>
            </div>

            {{-- Container Peta Jalan Interaktif (Stacked Bersih di HP agar Peta 100% Terbuka, Floating Overlay di Desktop!) --}}
            <div data-reveal="zoom" class="relative mt-8 sm:mt-10 flex flex-col sm:block sm:h-[560px] rounded-3xl border-2 border-primary/20 bg-white sm:bg-[#f3f6f4] shadow-[0_24px_60px_rgba(0,121,121,0.14)] overflow-hidden">
                {{-- Kartu Info Puskesmas (Sorotan Peta): Header Ringkas di HP, Floating Card Kiri Atas di Desktop --}}
                <div id="map-info-card" class="relative sm:absolute sm:left-5 sm:top-5 z-20 w-full sm:w-[275px] sm:rounded-2xl bg-white sm:bg-white/95 sm:backdrop-blur-md p-4 sm:shadow-float border-b sm:border border-gray-100 transition-all duration-200">
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
                    <div class="mt-3.5 grid grid-cols-2 sm:grid-cols-1 gap-2">
                        <x-button :href="route('peta')" variant="primary" class="w-full font-bold text-[11px] sm:text-xs h-9 sm:h-10">Buka di Peta GIS</x-button>
                        <x-button href="https://dinkes.kotabogor.go.id" target="_blank" variant="outline" class="w-full font-bold text-[11px] sm:text-xs h-9 sm:h-10">Portal resmi</x-button>
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

    {{-- ================= TRANSISI GELOMBANG ORGANIK & KONTUR SPASIAL MENUJU FOOTER ================= --}}
    <div class="relative bg-white overflow-hidden leading-none select-none" aria-hidden="true">
        <svg class="relative block w-full h-[56px] sm:h-[104px] -mb-px" viewBox="0 0 1440 120" preserveAspectRatio="none">
            {{-- Layer 1: Gelombang Mint Lembut (Menyambung dari Section Atas) --}}
            <path d="M0,42 C280,108 580,-8 920,56 C1160,98 1320,38 1440,22 L1440,120 L0,120 Z" fill="#dff2ef"></path>
            {{-- Layer 2: Gelombang Teal Menengah --}}
            <path d="M0,66 C340,10 700,106 1060,44 C1240,14 1360,48 1440,66 L1440,120 L0,120 Z" fill="#0d6363" opacity="0.9"></path>
            {{-- Garis Rute Kontur Spasial Halus (Non-Scaling Stroke agar Tetap Rapi) --}}
            <path d="M0,56 C340,2 700,98 1060,36 C1240,8 1360,38 1440,54" fill="none" stroke="#ffe2af" stroke-width="2" stroke-dasharray="8 9" stroke-linecap="round" vector-effect="non-scaling-stroke" opacity="0.7"></path>
            {{-- Layer 3: Gelombang Utama Footer (#084747) --}}
            <path d="M0,80 C360,26 720,114 1100,58 C1260,36 1370,64 1440,78 L1440,120 L0,120 Z" fill="#084747"></path>
        </svg>
    </div>

    {{-- ================= FOOTER INTERAKTIF DENGAN PALET PUSKESMAP ================= --}}
    <footer class="relative bg-[#084747] text-white pt-8 sm:pt-12 pb-28 md:pb-10 overflow-hidden">
        {{-- Ambient Glow Halus di Latar Footer --}}
        <div class="pointer-events-none absolute -top-20 left-1/4 size-80 rounded-full bg-primary/25 blur-3xl" aria-hidden="true"></div>
        <div class="pointer-events-none absolute bottom-0 right-10 size-72 rounded-full bg-accent/10 blur-3xl" aria-hidden="true"></div>

        <div class="relative z-10 mx-auto w-full max-w-[1180px] px-4 sm:px-6 lg:px-8">
            <div data-reveal-stagger="140" class="grid grid-cols-1 lg:grid-cols-[240px_1fr_52px] gap-10 lg:gap-9 items-center">
                {{-- Kolom Kiri: Brand PuskesMap Bersih --}}
                <div class="flex flex-col items-center text-center">
                    <a href="#beranda" class="flex flex-col items-center gap-3 group">
                        <div class="flex size-16 items-center justify-center rounded-3xl border border-white/20 bg-white/10 shadow-[0_12px_28px_rgba(0,0,0,0.18)] backdrop-blur-xs transition-transform duration-300 group-hover:scale-105">
                            <img src="{{ asset('images/logo-sm.png') }}" alt="" class="h-9 w-auto brightness-110">
                        </div>
                        <span class="block text-3xl sm:text-[38px] font-extrabold tracking-tight text-white leading-none">
                            PuskesMap
                        </span>
                    </a>
                    <p class="mt-3 max-w-[200px] text-xs sm:text-sm font-medium leading-relaxed text-white/75">
                        Peta layanan puskesmas induk di Kota Bogor
                    </p>
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
                        <p id="footer-tab-address" class="text-base sm:text-[17px] font-medium text-white/95 leading-relaxed max-w-lg">
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
                            <p id="footer-tab-hours" class="mt-1.5 text-base sm:text-[17px] text-white/80 font-medium leading-relaxed">
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
