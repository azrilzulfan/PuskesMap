@props(['labels' => true])

@php
    // 6 Kecamatan disusun berurutan searah jarum jam untuk animasi menyala bergantian yang halus & estetik
    // Koordinat cx & cy diposisikan tepat di pusat visual (tengah-tengah) masing-masing wilayah
    $districts = [
        'tanah-sareal' => [
            'name' => 'Tanah Sareal',
            'fill' => '#7ed0c9',
            'pill' => '#0d6e79',
            'cx' => 365,
            'cy' => 205,
            'badgeW' => 136,
            'd' => 'M351.3 26.4 L364.4 25.4 L378.2 25.1 L388 28.2 L395.6 28.7 L406.4 25.3 L423.6 25.3 L432.2 32.2 L433.8 47.8 L434.9 66.9 L430.4 82.2 L425.8 96.9 L429.5 107.8 L440 119.1 L445.8 129.6 L449.4 137.6 L448.9 145.8 L445.8 154.5 L438.9 157.3 L433.3 160.4 L430.2 164.7 L424.9 166.9 L420.9 170.9 L421.8 178 L425.4 187.3 L425.8 198.7 L429.8 206.2 L439.1 211.1 L447.6 219.4 L454.4 222.2 L460.7 219.8 L464 222.2 L464.7 231.6 L463.8 245.1 L469.5 263.8 L480.7 279.1 L483.3 289.1 L488.4 299.1 L500.3 313.8 L500.7 323.8 L493.3 326.9 L489.3 334.8 L486.9 352 L479.3 366.2 L473.8 380.2 L471.4 393.3 L463.1 404.9 L450.9 411.8 L438.2 417.8 L423.8 421.3 L412 423.8 L403.8 419.8 L399.1 409.3 L391.8 400.4 L377.1 395.8 L361.5 393.4 L348.3 381.2 L326.2 353.6 L307.5 327.9 L300.9 322.9 L301.8 330.6 L303.2 342.5 L295.3 346.2 L289.5 339.4 L291.4 326.6 L287.8 318 L276.4 305 L265.3 280.6 L261.5 266 L265.8 257.6 L265.8 246.7 L260.2 233.3 L255.8 219.8 L246.7 204.6 L235.5 193.1 L229.4 181.1 L223.1 167.8 L223.3 159.3 L230 153.4 L235.8 142.9 L237.3 132.4 L237.8 124.4 L235.3 117.6 L235.3 111.8 L240 108.7 L244.7 101.8 L244.7 89.8 L244.8 78.7 L252.5 66.9 L254.2 56.9 L250.2 50 L252.2 42.7 L262.4 34.6 L274.2 31.1 L282.4 30.6 L291.3 33.8 L304 37.1 L316.7 43.4 L327.8 43.8 L340.2 35.1 Z',
            'puskesmas' => [
                ['Puskesmas Tanah Sareal', '1,1 km', true, true, 'Sedang'],
                ['Puskesmas Kedung Badak', '1,5 km', true, false, 'Sedang'],
                ['Puskesmas Mekarwangi', '2,0 km', true, true, 'Ramai'],
                ['Puskesmas Pondok Rumput', '2,5 km', false, false, 'Sepi'],
                ['Puskesmas Kayumanis', '3,1 km', false, false, 'Sepi'],
            ],
        ],
        'bogor-utara' => [
            'name' => 'Bogor Utara',
            'fill' => '#88d6cf',
            'pill' => '#0d6e79',
            'cx' => 580,
            'cy' => 340,
            'badgeW' => 126,
            'd' => 'M586.7 173.7 L592.9 183.8 L593.8 194.4 L592.6 204 L600.4 211.4 L617.6 211.1 L626.7 209.1 L628.9 213.1 L634.6 216.7 L647.6 218.6 L658.9 225.1 L666 234.6 L677.8 251.8 L684.5 272.5 L681.8 285.6 L682.2 294.4 L687.8 303.6 L687.8 314.2 L682.2 327.4 L681.8 336.4 L685.8 344 L687.3 351.6 L688.2 362 L688.6 383.3 L695.3 408 L703.2 426.2 L699.1 443.4 L690.2 458.6 L683.4 477.1 L672.4 500 L655.6 521.9 L632.4 521.6 L614.2 511.3 L598.2 505.3 L587.3 504.9 L583.3 509.3 L582.7 516.7 L574.7 518.5 L554.2 514 L537.5 511.6 L530.7 502.4 L525.8 488.2 L523.6 469.1 L520.2 447.8 L514.6 430.4 L510.5 416.4 L500.9 406.6 L490 405.3 L480.9 410.4 L475.3 418 L470.5 427.8 L458.9 438 L449.1 438.7 L446.2 431.1 L443.8 421.8 L448.8 414.7 L464.5 405.1 L475.6 389.8 L483.8 370.6 L492 353.4 L495.1 337.3 L502 329.4 L509.8 321.8 L506.7 311.6 L494.2 294.6 L488 280.9 L479.3 271.6 L471.3 258.9 L469.3 241.1 L470.2 227.1 L471.1 216.2 L475.8 206.9 L479.3 194.6 L484.4 187.3 L491.1 189.5 L497.8 200.2 L504.5 211.8 L506.6 223.8 L511.6 230.2 L519.3 231.3 L528.7 230.5 L534.2 221.6 L536.6 210.6 L543.1 205.1 L547.8 195.6 L551.7 180.2 L568.4 173.8 Z',
            'puskesmas' => [
                ['Puskesmas Bogor Utara', '4,4 km', true, true, 'Sedang'],
                ['Puskesmas Tegal Gundil', '3,2 km', true, false, 'Sedang'],
                ['Puskesmas Villa Indah Pajajaran', '4,1 km', false, false, 'Ramai'],
            ],
        ],
        'bogor-timur' => [
            'name' => 'Bogor Timur',
            'fill' => '#75c8c1',
            'pill' => '#0d6e79',
            'cx' => 570,
            'cy' => 650,
            'badgeW' => 126,
            'd' => 'M591.7 509.5 L609.8 512.2 L630.6 523.6 L648.5 537.3 L648.7 554 L640.6 571.1 L637.1 584.2 L634.9 593.3 L635.8 605.3 L639.4 622 L640.2 637.4 L642.7 647.8 L647.1 659.1 L654.9 671.3 L661.5 686.4 L670 703.6 L674.2 719.1 L678.7 735.5 L684.2 756.9 L691.4 773.6 L695.8 786 L701.1 801.5 L694.6 820.3 L664.2 822.7 L640.2 810.4 L625.8 800.9 L607.6 791.1 L590.8 775.9 L583.4 745.5 L575.3 727.1 L567.6 726.2 L559.6 732.2 L547.4 732.5 L525.4 718.9 L498.4 695.8 L483.5 676.6 L477.2 664.2 L466.4 651.8 L459.8 641.1 L454.2 630.9 L454.6 621.1 L463.1 607.8 L474.6 592.8 L489.8 586.7 L502.9 583.8 L518.6 580 L540.2 571.5 L559.1 563.6 L570.9 547.6 L576.6 529.1 L583.4 517.1 Z',
            'puskesmas' => [
                ['Puskesmas Bogor Timur', '3,9 km', true, true, 'Sedang'],
                ['Puskesmas Pulo Armyn', '4,1 km', false, false, 'Sepi'],
            ],
        ],
        'bogor-selatan' => [
            'name' => 'Bogor Selatan',
            'fill' => '#9ce1b4',
            'pill' => '#0d6e79',
            'cx' => 455,
            'cy' => 760,
            'badgeW' => 138,
            'd' => 'M362.6 608.6 L374.5 609.3 L381.3 615.6 L390.2 618.5 L404.2 616 L416 618.2 L426.4 622.2 L438 627.8 L449.1 631.8 L456.7 638.9 L464 650.9 L475.2 664.2 L481.5 676.6 L496.8 696.2 L524.9 720.5 L546.5 734.9 L557.3 735.4 L566.7 728.6 L574.9 728.6 L581.4 738.9 L583.3 751.8 L585.1 761.3 L585.1 771.6 L583.8 781.1 L590.8 787.6 L608.9 793.8 L624.4 802.2 L639.3 812 L664.2 824.7 L693.7 830.6 L701.1 840.2 L699.6 853.2 L694.8 858.6 L691.8 868.2 L678.9 881.2 L662.9 886.6 L652.6 894.4 L651.8 905.6 L654.5 917.8 L651.8 931.8 L650.9 941.1 L652.7 947.3 L649.8 953.8 L641.8 956.9 L636.9 962.2 L633.8 971.8 L624.7 981.5 L611.3 994.5 L601.3 1001.1 L595.1 999.8 L588.4 994.2 L581.6 990.2 L575.8 983.6 L570.9 974 L561.8 967.1 L554 966.2 L547.6 966.6 L542.6 972 L542.7 980.4 L539.4 989.1 L529.3 992 L516.5 985.3 L500.8 977.6 L491.4 970.4 L483.1 963.6 L478.7 956.4 L472.7 951.3 L464.2 947.8 L457.4 941.8 L448.6 937.1 L447.1 930.2 L452.9 918.9 L453.1 908.9 L448 904.2 L441.1 904.2 L434.2 898.9 L429.8 886.2 L428.6 874.7 L437.6 862.4 L438 851.8 L424 852.6 L405.6 865.8 L390 869.1 L386.6 864 L391.8 855.8 L390 843.3 L381.7 832 L382.2 821.1 L381.8 810.2 L374 806.6 L364.9 809.8 L355.8 813.1 L346 822.9 L341.4 836.2 L335.8 847.3 L329.8 856.5 L321.3 859.3 L315.3 864.2 L313.1 873.8 L309.6 878.2 L304 877.8 L299.8 880 L297.4 885.8 L292.2 896 L286.9 908.4 L277.8 918.9 L270.9 920.7 L268.4 916 L269.3 910 L271.8 903.6 L269.1 897.8 L262.9 895.1 L255.8 893.8 L244.4 893.8 L232.9 888.7 L224.4 880.6 L214.9 880.6 L200.2 885.6 L188 883.8 L178.9 880.2 L173.8 873.1 L175.8 864.2 L184 858.7 L192.2 851.1 L197.3 840.9 L203.1 830.7 L212 820.6 L227.4 815.1 L239.6 806.5 L247.6 793.3 L255.3 787.4 L265.1 776.9 L271.8 760 L274.2 749.1 L280.4 741.3 L288.2 733.4 L294.2 723.5 L303.1 718.2 L310.2 712 L314.2 703.6 L318 694.4 L313.6 688.6 L305.3 688.7 L301.3 686.7 L302.9 681.3 L308 676.2 L312.2 668 L311.1 659.3 L306 655.8 L300 656.2 L291.8 655.3 L283.3 652.9 L283.4 643.1 L281.1 629.8 L278 620.4 L284.4 615.8 L296.4 614.2 L309.1 611.8 L320.4 613.8 L328 625.5 L329.5 643.6 L338.2 647 L351.2 631.1 L355.8 616.4 Z',
            'puskesmas' => [
                ['Puskesmas Bogor Selatan', '4,8 km', true, true, 'Sedang'],
                ['Puskesmas Bondongan', '4,5 km', true, false, 'Sedang'],
                ['Puskesmas Cipaku', '5,3 km', true, false, 'Sedang'],
                ['Puskesmas Lawang Gintung', '5,1 km', false, false, 'Sepi'],
                ['Puskesmas Mulyaharja', '5,4 km', false, false, 'Sepi'],
            ],
        ],
        'bogor-barat' => [
            'name' => 'Bogor Barat',
            'fill' => '#a7e6bc',
            'pill' => '#0d6e79',
            'cx' => 210,
            'cy' => 410,
            'badgeW' => 126,
            'd' => 'M100.2 206.4 L108.2 207.1 L123.6 216 L137.4 219.3 L142.7 223.3 L143.3 231.1 L147.8 238 L156.7 242.2 L163.6 243.8 L166.7 248 L167.8 254 L172.4 258.7 L180 258.7 L186.7 250 L191.3 234 L198 226.6 L205.6 231.3 L213.3 238.9 L222.7 239.1 L229.1 231.4 L233.3 218.6 L238.7 209.6 L242.4 203.8 L247.3 206.4 L253.8 219.8 L258.2 233.3 L263.8 246.7 L263.8 257.1 L259.5 264.9 L260.7 273.6 L264.6 286.8 L275.3 308 L286.3 325.3 L288 339.8 L294.4 348.2 L304.2 349.1 L307.4 342.9 L304.2 330.6 L307.5 330.4 L320.7 349.2 L331.8 363.6 L339.1 373.3 L349.8 385.6 L356.7 406.8 L354.5 439.4 L343.8 454.9 L335.8 462.9 L331.8 473.1 L326.2 482 L321.8 488.7 L313.6 494.6 L307.3 506.9 L309.2 525.8 L308 546 L311.6 556.2 L319.3 561.5 L330.4 570.9 L344 577.8 L355.4 587.8 L357.8 602.7 L353.8 616.9 L349.4 632.4 L340.9 646.5 L332.9 650.2 L330.2 642 L331.4 626 L326.7 615.6 L313.4 611.1 L295.5 611.8 L286.2 609.6 L284.2 604 L277.8 598.9 L266.2 592 L257.6 580.4 L250.9 576.9 L245.6 578.2 L240.9 575.1 L240.2 568.9 L245.6 562.7 L248.9 554 L243.6 545.3 L233.6 544 L222 550.5 L211.8 553.8 L205.6 556.2 L200.4 554.7 L198.2 547.6 L200.2 538.4 L195.6 530 L182.8 522.4 L179.8 515.1 L184.2 506 L184.2 495.6 L180.6 484 L179.8 472.9 L175.6 465.3 L169.8 460.2 L166.7 452.9 L161.1 447.8 L152.4 445.4 L143.6 440.2 L136.2 434.5 L133.1 424.6 L125.2 420.2 L108 420.5 L94.2 415.8 L83.5 410.7 L77.8 402.7 L72.6 391.8 L74.2 384.9 L83.6 378.9 L90.9 368.2 L89.1 360.9 L85.1 356.2 L85.4 348.4 L81.6 341.3 L74 340.2 L66.4 342.9 L62.2 337.8 L62.2 324.4 L63.1 310.9 L67.8 298.2 L72.6 285.1 L79.8 273.6 L87.5 255.8 L98 239.8 L100.7 230.4 L98.2 224.4 L98.7 216.9 Z',
            'puskesmas' => [
                ['Puskesmas Pasir Mulya', '2,3 km', true, true, 'Sepi'],
                ['Puskesmas Semplak', '3,7 km', true, true, 'Sedang'],
                ['Puskesmas Gang Kelor', '3,0 km', true, false, 'Sedang'],
                ['Puskesmas Sindangbarang', '4,0 km', true, false, 'Sepi'],
                ['Puskesmas Pancasan', '3,4 km', false, false, 'Ramai'],
            ],
        ],
        'bogor-tengah' => [
            'name' => 'Bogor Tengah',
            'fill' => '#b7ebc8',
            'pill' => '#0d6e79',
            'cx' => 435,
            'cy' => 515,
            'badgeW' => 136,
            'd' => 'M373.8 398.2 L390 401.6 L397.1 407.8 L399.8 417.6 L407.8 424.5 L422.9 423.3 L438.5 424.2 L444.7 431.6 L445.3 439.1 L449.3 443.1 L459.8 440 L472.5 427.3 L480.2 414.9 L489.6 407.8 L500.4 408.2 L508.9 416.9 L512.6 430.4 L517.8 446.4 L520.9 461.8 L522.7 474.2 L524.2 488.7 L528.2 502 L531.8 512.2 L539.3 515.8 L552.4 514.6 L565.8 518 L572.2 528 L571.1 545.4 L565.1 559.1 L555.1 565.8 L539.4 570 L517.8 578.5 L500.6 582.9 L488.9 585.1 L474.6 588.6 L465.3 598 L460.2 609.1 L453.8 618.4 L447.8 625.8 L438 625.8 L426.4 620.2 L416 616.2 L404.2 614 L390.6 616.9 L382.4 614.7 L374.9 607.3 L364 599.4 L358.7 588.2 L353.1 580 L343.1 574.9 L330.4 568.9 L319.3 559.5 L312 554.7 L309.5 545.6 L311.2 525.8 L308.8 507.3 L313.6 496.6 L321.1 493.1 L325.1 489.1 L328.2 482 L333.8 473.1 L337.3 463.3 L342.9 457.3 L351.6 454.7 L358.7 446 L360.7 425.6 L362 404.8 Z',
            'puskesmas' => [
                ['Puskesmas Sempur', '1,2 km', false, false, 'Sepi'],
                ['Puskesmas Merdeka', '1,8 km', true, false, 'Sedang'],
                ['Puskesmas Bogor Tengah', '1,5 km', false, false, 'Sedang'],
                ['Puskesmas Gang Aut', '2,0 km', false, false, 'Sepi'],
                ['Puskesmas Belong', '2,2 km', false, false, 'Sepi'],
            ],
        ],
    ];
@endphp

<div data-bogor-map {{ $attributes->merge(['class' => 'relative aspect-[3/4] select-none']) }}>
    <svg viewBox="25 10 740 1005" class="map-svg h-full w-full overflow-hidden sm:overflow-visible transition-transform duration-300 ease-out origin-center" role="group" aria-label="Peta wilayah Kota Bogor">
        <defs>
            <filter id="map-soft-shadow" x="-8%" y="-8%" width="116%" height="116%">
                <feDropShadow dx="0" dy="12" stdDeviation="14" flood-color="#007979" flood-opacity="0.18"/>
            </filter>
            <filter id="pin-badge-shadow" x="-15%" y="-15%" width="130%" height="130%">
                <feDropShadow dx="0" dy="4" stdDeviation="4" flood-color="#0b5c5c" flood-opacity="0.22"/>
            </filter>

            {{-- Template Bangunan Puskesmas Estetik Sesuai Desain & Palet Pohon --}}
            <g id="puskesmas-building-asset">
                {{-- Bayangan Tanah Halus (Sama persis dengan bayangan pohon pinus) --}}
                <ellipse cx="22" cy="27" rx="22" ry="4.5" fill="#c4e2dc" opacity="0.8"/>

                {{-- Dinding Bangunan Puskesmas (Putih Bersih dengan Garis Hijau-Teal) --}}
                <rect x="5" y="9" width="34" height="17" rx="2.5" fill="#ffffff" stroke="#2c8577" stroke-width="1.2"/>

                {{-- Atap List Hijau Pinus Tua (Palet Serasi dengan Pohon Pinus #2c8577) --}}
                <rect x="3" y="7" width="38" height="3" rx="1.5" fill="#2c8577"/>

                {{-- Ornamen Kubah / Kanopi Tengah --}}
                <rect x="17" y="2" width="10" height="6" rx="1" fill="#007979"/>

                {{-- Tanda Palang Plus (+) Medis Puskesmas di Atap --}}
                <path d="M21 3.5 H23 V4.5 H24.5 V5.5 H23 V6.5 H21 V5.5 H19.5 V4.5 H21 Z" fill="#ffffff"/>

                {{-- Jendela Kaca Puskesmas (Warna Mint Lembut #7ed0c9) --}}
                <rect x="8" y="12" width="6" height="5" rx="0.8" fill="#7ed0c9" stroke="#2c8577" stroke-width="0.7"/>
                <rect x="30" y="12" width="6" height="5" rx="0.8" fill="#7ed0c9" stroke="#2c8577" stroke-width="0.7"/>

                {{-- Pintu Masuk Kaca --}}
                <rect x="19" y="17" width="6" height="9" rx="0.5" fill="#2c8577"/>
                <rect x="20" y="18" width="4" height="8" rx="0.4" fill="#d8f5ef"/>

                {{-- 1 Pohon Pinus Kecil Pendamping (Desain Identik dengan Pohon Pinus) --}}
                <g transform="translate(41, 6)">
                    <polygon points="5,0 0,11 10,11" fill="#3a9b8c"/>
                    <polygon points="5,4 1,14 9,14" fill="#2c8577"/>
                    <rect x="4" y="14" width="2" height="4" fill="#5c4033"/>
                </g>
            </g>

            {{-- Animasi Warna Menyala Bergantian per Kecamatan (Loop 7.2 detik) --}}
            <style>
                @keyframes districtGlowCycle {
                    0%, 100% {
                        filter: brightness(1);
                        stroke: #ffffff;
                    }
                    8% {
                        filter: brightness(1.24) saturate(1.3) drop-shadow(0 0 16px rgba(0, 209, 178, 0.95)) drop-shadow(0 0 24px rgba(11, 92, 92, 0.4));
                        stroke: #ffffff;
                    }
                    18% {
                        filter: brightness(1);
                        stroke: #ffffff;
                    }
                }

                @keyframes badgeGlowCycle {
                    0%, 100% {
                        filter: drop-shadow(0 3px 5px rgba(11, 92, 92, 0.22));
                    }
                    8% {
                        filter: drop-shadow(0 0 12px rgba(255, 255, 255, 0.95)) drop-shadow(0 4px 14px rgba(0, 121, 121, 0.5));
                    }
                    18% {
                        filter: drop-shadow(0 3px 5px rgba(11, 92, 92, 0.22));
                    }
                }

                .district-animated {
                    animation: districtGlowCycle 7.2s infinite ease-in-out;
                }

                .badge-animated {
                    animation: badgeGlowCycle 7.2s infinite ease-in-out;
                }

                .district-animated:hover,
                .district-animated.is-active {
                    filter: brightness(1.2) saturate(1.25) drop-shadow(0 0 18px rgba(0, 209, 178, 0.9)) !important;
                    stroke: #0b5c5c !important;
                }
            </style>
        </defs>

        {{-- 6 Wilayah Kecamatan Rapi Tanpa Lapisan Belakang Tambahan, Bersih & Beranimasi --}}
        <g filter="url(#map-soft-shadow)" stroke="#ffffff" stroke-width="2.5" stroke-linejoin="round">
            @foreach ($districts as $id => $district)
                <path d="{{ $district['d'] }}"
                      fill="{{ $district['fill'] }}"
                      data-district-shape="{{ $id }}"
                      tabindex="0"
                      role="button"
                      aria-label="Kecamatan {{ $district['name'] }}"
                      style="animation-delay: {{ $loop->index * 1.2 }}s;"
                      class="district-animated cursor-pointer outline-none transition-all duration-200 hover:brightness-95 focus-visible:brightness-90 [&.is-active]:fill-[#63bfb7] [&.is-active]:stroke-[#0b5c5c] [&.is-active]:stroke-[3.5]"/>
            @endforeach
        </g>

        {{-- ================= ORNAMEN ILUSTRASI POHON & BUKIT KECIL (Sesuai Referensi) ================= --}}
        {{-- Cluster 3 Pohon Pinus di Kiri Luar Peta --}}
        <g transform="translate(52, 545)" pointer-events="none">
            <ellipse cx="26" cy="34" rx="26" ry="5" fill="#d9ece9"/>
            <g transform="translate(4, 4)">
                <polygon points="8,0 0,18 16,18" fill="#3a9b8c"/>
                <polygon points="8,7 1,23 15,23" fill="#2c8577"/>
                <rect x="6.5" y="23" width="3" height="6" fill="#5c4033"/>
            </g>
            <g transform="translate(20, 0)">
                <polygon points="9,0 0,20 18,20" fill="#4eb09f"/>
                <polygon points="9,8 1,26 17,26" fill="#3a9b8c"/>
                <rect x="7.5" y="26" width="3" height="6" fill="#5c4033"/>
            </g>
            <g transform="translate(36, 6)">
                <polygon points="7,0 0,16 14,16" fill="#3a9b8c"/>
                <polygon points="7,6 1,21 13,21" fill="#2c8577"/>
                <rect x="5.5" y="21" width="3" height="5" fill="#5c4033"/>
            </g>
        </g>

        {{-- Cluster 3 Pohon Pinus di Kanan Luar Peta --}}
        <g transform="translate(692, 385)" pointer-events="none">
            <ellipse cx="26" cy="34" rx="26" ry="5" fill="#d9ece9"/>
            <g transform="translate(4, 5)">
                <polygon points="7,0 0,16 14,16" fill="#3a9b8c"/>
                <polygon points="7,6 1,21 13,21" fill="#2c8577"/>
                <rect x="5.5" y="21" width="3" height="5" fill="#5c4033"/>
            </g>
            <g transform="translate(18, 0)">
                <polygon points="9,0 0,20 18,20" fill="#4eb09f"/>
                <polygon points="9,8 1,26 17,26" fill="#3a9b8c"/>
                <rect x="7.5" y="26" width="3" height="6" fill="#5c4033"/>
            </g>
            <g transform="translate(35, 4)">
                <polygon points="8,0 0,18 16,18" fill="#3a9b8c"/>
                <polygon points="8,7 1,23 15,23" fill="#2c8577"/>
                <rect x="6.5" y="23" width="3" height="6" fill="#5c4033"/>
            </g>
        </g>

        {{-- Ilustrasi Bukit & Taman Kebun Raya di Tengah Peta --}}
        <g transform="translate(375, 365)" pointer-events="none">
            <path d="M0 24 Q22 2 46 24 Z" fill="#49a88f"/>
            <path d="M18 24 Q36 8 56 24 Z" fill="#63be9f"/>
            <circle cx="14" cy="18" r="5" fill="#2e856e"/>
            <circle cx="48" cy="19" r="4" fill="#2e856e"/>
        </g>

        {{-- Ilustrasi Bukit Kecil di Bogor Barat --}}
        <g transform="translate(145, 470)" pointer-events="none">
            <path d="M0 26 L22 4 L44 26 Z" fill="#c7b299" opacity="0.7"/>
            <path d="M16 26 L36 8 L56 26 Z" fill="#82c99b"/>
            <polygon points="10,12 4,24 16,24" fill="#2e856e"/>
            <polygon points="20,14 15,25 25,25" fill="#3a9b8c"/>
        </g>

        {{-- Ilustrasi Bukit Hijau di Bogor Selatan --}}
        <g transform="translate(275, 835)" pointer-events="none">
            <path d="M0 28 Q26 0 54 28 Z" fill="#46a77c"/>
            <path d="M22 28 Q46 6 70 28 Z" fill="#68c196"/>
            <circle cx="18" cy="22" r="6" fill="#2e856e"/>
            <circle cx="32" cy="19" r="7" fill="#399974"/>
        </g>

        {{-- ================= BENTUK BANGUNAN PUSKESMAS (SEPADAN DENGAN DESAIN POHON) ================= --}}
        <g pointer-events="none">
            {{-- Puskesmas Tanah Sareal (Atas) --}}
            <use href="#puskesmas-building-asset" x="300" y="80" />

            {{-- Puskesmas Bogor Barat (Kiri) --}}
            <use href="#puskesmas-building-asset" x="120" y="280" />

            {{-- Puskesmas Bogor Utara (Kanan Atas) --}}
            <use href="#puskesmas-building-asset" x="620" y="215" />

            {{-- Puskesmas Bogor Timur (Kanan Bawah) --}}
            <use href="#puskesmas-building-asset" x="605" y="725" />

            {{-- Puskesmas Bogor Selatan (Bawah) --}}
            <use href="#puskesmas-building-asset" x="320" y="850" />
        </g>

        {{-- ================= LABEL 6 KECAMATAN DENGAN PILL BADGE & MARKER PLUS BESAR ================= --}}
        @if ($labels)
            <g font-family="Plus Jakarta Sans, sans-serif">
                @foreach ($districts as $id => $district)
                    <g transform="translate({{ $district['cx'] }}, {{ $district['cy'] }})"
                       data-district-shape="{{ $id }}"
                       role="button"
                       tabindex="0"
                       aria-label="Kecamatan {{ $district['name'] }}"
                       class="cursor-pointer group outline-none">
                        {{-- Hit area transparan solid: cegah kedutan/flicker kursor antara pin dan pill --}}
                        <rect x="{{ -$district['badgeW'] / 2 - 8 }}" y="-44" width="{{ $district['badgeW'] + 16 }}" height="80" rx="16" fill="white" fill-opacity="0" pointer-events="all"/>

                        <g class="badge-animated group-hover:brightness-110 group-focus-visible:brightness-110 transition-[filter] duration-200"
                           style="animation-delay: {{ $loop->index * 1.2 }}s;"
                           filter="url(#pin-badge-shadow)"
                           pointer-events="none">
                            {{-- Ikon Marker Pin Lebih Besar & Jelas (Tinggi 40px, Lebar 30px) --}}
                            <path d="M0 -4 C-15 -18 -17 -40 0 -40 C17 -40 15 -18 0 -4 Z" fill="{{ $district['pill'] }}" stroke="#ffffff" stroke-width="2.5"/>
                            
                            {{-- Tanda Medis Plus (+) Jauh Lebih Besar, Tegas & Terlihat Jelas di Dalam Pin --}}
                            <rect x="-3" y="-34" width="6" height="18" rx="1.5" fill="#ffffff"/>
                            <rect x="-9" y="-28" width="18" height="6" rx="1.5" fill="#ffffff"/>

                            {{-- Rounded Pill Badge Tepat di Bawah Marker Pin --}}
                            <rect x="{{ -$district['badgeW'] / 2 }}" y="0" width="{{ $district['badgeW'] }}" height="32" rx="16" fill="{{ $district['pill'] }}" stroke="#ffffff" stroke-width="2"/>
                            <text x="0" y="21" font-size="14" font-weight="800" fill="#ffffff" text-anchor="middle">{{ $district['name'] }}</text>
                        </g>
                    </g>
                @endforeach
            </g>
        @endif
    </svg>

    {{-- Popup kecamatan ringkas & presisi (Sesuai Gambar 2: Hanya Nama Kecamatan & Jumlah Puskesmas) --}}
    @foreach ($districts as $id => $district)
        <div data-district-popup="{{ $id }}"
             data-x="{{ round($district['cx'] / 768 * 100, 2) }}"
             data-y="{{ round($district['cy'] / 1024 * 100, 2) }}"
             role="dialog"
             aria-label="Informasi Wilayah {{ $district['name'] }}"
             class="absolute z-40 hidden min-w-[210px] max-w-[260px] rounded-2xl bg-white/95 backdrop-blur-md p-3.5 sm:p-4 text-left shadow-xl border border-teal-100/80 transition-all duration-200">
            <div class="flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <span class="size-4 shrink-0 rounded-full shadow-xs" style="background-color: {{ $district['pill'] }}"></span>
                    <div>
                        <h3 class="text-sm font-bold text-ink leading-tight">Kec. {{ $district['name'] }}</h3>
                        <span class="text-xs text-muted leading-tight mt-0.5 block">{{ count($district['puskesmas']) }} Puskesmas terdata</span>
                    </div>
                </div>
                <button type="button" data-popup-close aria-label="Tutup" class="size-7 shrink-0 flex items-center justify-center rounded-full text-muted hover:text-ink hover:bg-surface-mint transition-colors">
                    <svg class="size-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>
    @endforeach
</div>
