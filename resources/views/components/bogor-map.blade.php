@props(['labels' => true, 'interactivePins' => true])

@php
    $districts = [
        'tanah-sareal' => [
            'name' => 'Tanah Sareal', 'pill' => '#0f766e', 'cx' => 345, 'cy' => 195,
            'd' => 'M250 30 L300 20 L380 25 L430 40 L445 90 L440 130 L455 160 L445 200 L470 225 L475 260 L500 300 L480 340 L450 380 L420 395 L395 395 L360 385 L355 395 L335 370 L300 330 L255 265 L220 235 L222 195 L238 160 L225 110 L252 65 Z',
            'puskesmas' => [
                ['Puskesmas Tanah Sareal', '1,1 km', true, true, 'Sedang'],
                ['Puskesmas Kedung Badak', '1,5 km', true, false, 'Sedang'],
                ['Puskesmas Mekarwangi', '2,0 km', false, false, 'Ramai'],
                ['Puskesmas Pondok Rumput', '2,5 km', false, false, 'Sepi'],
                ['Puskesmas Kayumanis', '3,1 km', false, false, 'Sepi'],
            ],
        ],
        'bogor-barat' => [
            'name' => 'Bogor Barat', 'pill' => '#2f8a2f', 'cx' => 210, 'cy' => 395,
            'd' => 'M95 205 L130 215 L170 230 L195 235 L220 235 L255 265 L300 330 L335 370 L355 395 L350 440 L330 500 L310 530 L335 555 L350 590 L340 640 L320 650 L300 620 L275 600 L260 570 L230 555 L195 520 L170 470 L150 430 L95 370 L60 325 L75 290 L90 255 Z',
            'puskesmas' => [
                ['Puskesmas Pasir Mulya', '2,3 km', true, true, 'Sepi'],
                ['Puskesmas Belong', '2,8 km', false, false, 'Sedang'],
                ['Puskesmas Gang Kelor', '3,0 km', true, false, 'Sedang'],
                ['Puskesmas Pancasan', '3,4 km', false, false, 'Ramai'],
                ['Puskesmas Semplak', '3,7 km', true, false, 'Sedang'],
                ['Puskesmas Sindangbarang', '4,0 km', false, true, 'Sepi'],
            ],
        ],
        'bogor-utara' => [
            'name' => 'Bogor Utara', 'pill' => '#7a3ea8', 'cx' => 592, 'cy' => 295,
            'd' => 'M470 190 L505 185 L540 170 L580 185 L650 235 L665 250 L690 330 L705 410 L690 460 L675 490 L650 530 L560 520 L500 500 L490 455 L470 435 L445 410 L450 380 L480 340 L500 300 L475 260 L470 225 Z',
            'puskesmas' => [
                ['Puskesmas Tegal Gundil', '3,2 km', true, false, 'Sedang'],
                ['Puskesmas Bantarjati', '3,6 km', false, false, 'Sepi'],
                ['Puskesmas Villa Indah Pajajaran', '4,1 km', false, false, 'Ramai'],
                ['Puskesmas Bogor Utara', '4,4 km', true, true, 'Sedang'],
            ],
        ],
        'bogor-tengah' => [
            'name' => 'Bogor Tengah', 'pill' => '#be2a55', 'cx' => 430, 'cy' => 517,
            'd' => 'M355 395 L395 395 L445 410 L470 435 L490 455 L500 500 L560 520 L570 545 L540 575 L500 585 L455 590 L440 610 L400 620 L380 610 L350 590 L335 555 L310 530 L330 500 L350 440 Z',
            'puskesmas' => [
                ['Puskesmas Sempur', '1,2 km', false, false, 'Sepi'],
                ['Puskesmas Merdeka', '1,8 km', true, false, 'Sedang'],
                ['Puskesmas Babakan Pasar', '2,1 km', false, false, 'Ramai'],
            ],
        ],
        'bogor-timur' => [
            'name' => 'Bogor Timur', 'pill' => '#1d5fa8', 'cx' => 560, 'cy' => 625,
            'd' => 'M560 520 L650 530 L640 560 L650 620 L670 680 L700 740 L712 790 L705 840 L695 860 L670 840 L640 810 L625 795 L580 740 L545 700 L510 660 L480 630 L455 590 L500 585 L540 575 L570 545 Z',
            'puskesmas' => [
                ['Puskesmas Baranangsiang', '3,9 km', false, false, 'Sedang'],
                ['Puskesmas Sukasari', '4,1 km', true, true, 'Sedang'],
                ['Puskesmas Sindangsari', '4,8 km', false, false, 'Sepi'],
            ],
        ],
        'bogor-selatan' => [
            'name' => 'Bogor Selatan', 'pill' => '#8a5a10', 'cx' => 480, 'cy' => 777,
            'd' => 'M275 610 L300 620 L320 650 L340 640 L350 590 L380 610 L400 620 L440 610 L455 590 L480 630 L510 660 L545 700 L580 740 L625 795 L640 810 L670 840 L695 860 L650 930 L600 975 L560 985 L520 960 L470 900 L430 880 L415 855 L380 830 L350 835 L320 870 L280 920 L250 910 L215 890 L170 880 L195 850 L235 830 L280 775 L300 720 L300 680 L280 640 Z',
            'puskesmas' => [
                ['Puskesmas Bondongan', '4,8 km', true, false, 'Sedang'],
                ['Puskesmas Lawang Gintung', '5,1 km', false, false, 'Sepi'],
                ['Puskesmas Cipaku', '5,3 km', false, false, 'Sedang'],
                ['Puskesmas Mulyaharja', '5,4 km', false, true, 'Sepi'],
                ['Puskesmas Batutulis', '5,7 km', false, false, 'Sedang'],
            ],
        ],
    ];

    $pins = [
        // Tanah Sareal
        ['Puskesmas Kayumanis', 312, 75, 'Tanah Sareal', '3,1 km', false, false, 'Sepi'],
        ['Puskesmas Pondok Rumput', 392, 148, 'Tanah Sareal', '2,5 km', false, false, 'Sepi'],
        ['Puskesmas Tanah Sareal', 382, 248, 'Tanah Sareal', '1,1 km', true, true, 'Sedang'],
        ['Puskesmas Mekarwangi', 336, 312, 'Tanah Sareal', '2,0 km', false, false, 'Ramai'],
        ['Puskesmas Kedung Badak', 450, 295, 'Tanah Sareal', '1,5 km', true, false, 'Sedang'],

        // Bogor Barat
        ['Puskesmas Pancasan', 125, 288, 'Bogor Barat', '3,4 km', false, false, 'Ramai'],
        ['Puskesmas Gang Kelor', 225, 257, 'Bogor Barat', '3,0 km', true, false, 'Sedang'],
        ['Puskesmas Belong', 128, 357, 'Bogor Barat', '2,8 km', false, false, 'Sedang'],
        ['Puskesmas Semplak', 281, 338, 'Bogor Barat', '3,7 km', true, false, 'Sedang'],
        ['Puskesmas Pasir Mulya', 215, 442, 'Bogor Barat', '2,3 km', true, true, 'Sepi'],
        ['Puskesmas Sindangbarang', 301, 447, 'Bogor Barat', '4,0 km', false, true, 'Sepi'],
        ['Puskesmas Curug', 272, 505, 'Bogor Barat', '4,3 km', false, false, 'Sedang'],
        ['Puskesmas Bubulak', 313, 605, 'Bogor Barat', '4,6 km', false, false, 'Sepi'],

        // Bogor Utara
        ['Puskesmas Tegal Gundil', 564, 230, 'Bogor Utara', '3,2 km', true, false, 'Sedang'],
        ['Puskesmas Bantarjati', 548, 350, 'Bogor Utara', '3,6 km', false, false, 'Sepi'],
        ['Puskesmas Villa Indah Pajajaran', 652, 347, 'Bogor Utara', '4,1 km', false, false, 'Ramai'],
        ['Puskesmas Bogor Utara', 611, 443, 'Bogor Utara', '4,4 km', true, true, 'Sedang'],
        ['Puskesmas Ciluar', 548, 485, 'Bogor Utara', '4,7 km', false, false, 'Sepi'],
        ['Puskesmas Cibuluh', 642, 482, 'Bogor Utara', '5,0 km', false, false, 'Sedang'],

        // Bogor Tengah
        ['Puskesmas Sempur', 450, 427, 'Bogor Tengah', '1,2 km', false, false, 'Sepi'],
        ['Puskesmas Merdeka', 393, 463, 'Bogor Tengah', '1,8 km', true, false, 'Sedang'],
        ['Puskesmas Babakan Pasar', 442, 563, 'Bogor Tengah', '2,1 km', false, false, 'Ramai'],
        ['Puskesmas Paledang', 383, 572, 'Bogor Tengah', '2,4 km', false, false, 'Sepi'],

        // Bogor Timur
        ['Puskesmas Baranangsiang', 611, 552, 'Bogor Timur', '3,9 km', false, false, 'Sedang'],
        ['Puskesmas Sukasari', 548, 675, 'Bogor Timur', '4,1 km', true, true, 'Sedang'],
        ['Puskesmas Sindangsari', 601, 737, 'Bogor Timur', '4,8 km', false, false, 'Sepi'],

        // Bogor Selatan
        ['Puskesmas Bondongan', 429, 657, 'Bogor Selatan', '4,8 km', true, false, 'Sedang'],
        ['Puskesmas Lawang Gintung', 353, 716, 'Bogor Selatan', '5,1 km', false, false, 'Sepi'],
        ['Puskesmas Cipaku', 452, 720, 'Bogor Selatan', '5,3 km', false, false, 'Sedang'],
        ['Puskesmas Mulyaharja', 291, 797, 'Bogor Selatan', '5,4 km', false, true, 'Sepi'],
        ['Puskesmas Batutulis', 514, 830, 'Bogor Selatan', '5,7 km', false, false, 'Sedang'],
        ['Puskesmas Bojongkerta', 548, 858, 'Bogor Selatan', '6,0 km', false, false, 'Sepi'],
        ['Puskesmas Ranggamekar', 615, 855, 'Bogor Selatan', '6,3 km', false, false, 'Sepi'],
        ['Puskesmas Kertamaya', 591, 917, 'Bogor Selatan', '6,8 km', false, false, 'Sepi'],
    ];
@endphp

<div data-bogor-map {{ $attributes->merge(['class' => 'relative aspect-[3/4] select-none']) }}>
    {{-- Gambar Utama Peta Kota Bogor (.webp) --}}
    <img src="{{ asset('images/bogor-map.webp') }}"
         alt="Peta wilayah 6 kecamatan dan puskesmas Kota Bogor"
         class="map-svg h-full w-full object-contain drop-shadow-md transition-transform duration-300 ease-out origin-center pointer-events-none">

    {{-- Lapisan Interaktif Transparan di Atas Gambar WebP untuk Klik Kecamatan & Pin --}}
    <svg viewBox="0 0 768 1024" class="absolute inset-0 h-full w-full overflow-visible" role="group" aria-label="Area interaktif peta Kota Bogor">
        {{-- Area Klik Kecamatan --}}
        <g>
            @foreach ($districts as $id => $district)
                <path d="{{ $district['d'] }}"
                      fill="transparent"
                      stroke="transparent"
                      stroke-width="6"
                      stroke-linejoin="round"
                      data-district-shape="{{ $id }}"
                      tabindex="0"
                      role="button"
                      aria-label="Kecamatan {{ $district['name'] }}"
                      class="cursor-pointer outline-none transition-all duration-200 hover:fill-white/15 focus-visible:fill-white/20 [&.is-active]:fill-white/20 [&.is-active]:stroke-[#e37434]"/>
            @endforeach
        </g>

        {{-- Titik Klik Puskesmas --}}
        @if ($interactivePins)
            <g class="pins-layer">
                @foreach ($pins as [$name, $px, $py, $dist, $distance, $igd, $inap, $crowd])
                    <g transform="translate({{ $px }} {{ $py }})"
                       class="puskesmas-pin cursor-pointer"
                       tabindex="0"
                       role="button"
                       aria-label="{{ $name }} ({{ $dist }})"
                       data-pin-name="{{ $name }}"
                       data-pin-district="{{ $dist }}"
                       data-pin-distance="{{ $distance }}"
                       data-pin-igd="{{ $igd ? '1' : '0' }}"
                       data-pin-inap="{{ $inap ? '1' : '0' }}"
                       data-pin-crowd="{{ $crowd }}">
                        <title>{{ $name }} ({{ $dist }} · {{ $distance }})</title>
                        <circle cy="-3" r="18" fill="transparent" class="hover:fill-white/25 transition-colors"/>
                    </g>
                @endforeach
            </g>
        @endif
    </svg>

    {{-- Popup kecamatan interaktif --}}
    @foreach ($districts as $id => $district)
        <div data-district-popup="{{ $id }}"
             data-x="{{ round($district['cx'] / 768 * 100, 2) }}"
             data-y="{{ round($district['cy'] / 1024 * 100, 2) }}"
             role="dialog"
             aria-label="Puskesmas di {{ $district['name'] }}"
             class="absolute z-40 hidden w-[280px] rounded-2xl bg-white/95 backdrop-blur-md p-4 text-left shadow-2xl border border-primary/20 transition-all duration-200">
            {{-- Header popup --}}
            <div class="flex items-center justify-between pb-2.5 border-b border-gray-100">
                <div class="flex items-center gap-2">
                    <span class="size-3.5 rounded-full shadow-xs" style="background-color: {{ $district['pill'] }}"></span>
                    <div>
                        <h3 class="text-sm font-bold text-ink leading-tight">Kec. {{ $district['name'] }}</h3>
                        <span class="text-[11px] text-muted">{{ count($district['puskesmas']) }} Puskesmas terdata</span>
                    </div>
                </div>
                <button type="button" data-popup-close aria-label="Tutup" class="size-6 flex items-center justify-center rounded-full text-muted hover:bg-surface-mint transition-colors">
                    <svg class="size-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" d="M6 6l12 12M18 6 6 18"/></svg>
                </button>
            </div>

            {{-- List puskesmas --}}
            <div class="mt-3 max-h-[175px] overflow-y-auto space-y-2 pr-1 no-scrollbar">
                @foreach ($district['puskesmas'] as $p)
                    <div class="rounded-xl bg-surface-mint/70 p-2.5 text-xs">
                        <div class="font-semibold text-ink leading-snug">{{ $p[0] }}</div>
                        <div class="mt-1.5 flex flex-wrap gap-1">
                            <span class="inline-flex items-center rounded-full bg-white px-2 py-0.5 text-[10px] font-semibold text-primary-dark shadow-xs">{{ $p[1] }}</span>
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-2 py-0.5 text-[10px] font-semibold text-emerald-800">Buka</span>
                            @if ($p[2])
                                <span class="inline-flex items-center rounded-full bg-accent/20 px-2 py-0.5 text-[10px] font-semibold text-accent-strong">IGD 24 Jam</span>
                            @endif
                            @if ($p[3] ?? false)
                                <span class="inline-flex items-center rounded-full bg-sky-100 px-2 py-0.5 text-[10px] font-semibold text-sky-800">Rawat Inap</span>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- Action button --}}
            <button type="button" data-select-district="{{ $district['name'] }}"
                    class="mt-3 flex w-full items-center justify-center gap-1.5 rounded-full bg-primary py-2 text-xs font-semibold text-white shadow-sm hover:bg-primary-dark transition-colors">
                <span>Filter di Daftar Puskesmas</span>
                <svg class="size-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
            </button>
        </div>
    @endforeach
</div>
