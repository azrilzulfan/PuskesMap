<x-layout title="Peta Interaktif Puskesmas Kota Bogor - PuskesMap" body-class="h-dvh w-screen overflow-hidden bg-[#f4f7f6] flex flex-col text-ink font-sans">
    {{-- Header & Bar Filter Cepat Mobile --}}
    @include('map.partials.header')

    {{-- Area Kerja Utama Peta WebGIS --}}
    <main class="flex-1 relative flex overflow-hidden bg-white">
        {{-- Backdrop Gelap Saat Drawer HP Dibuka --}}
        <div id="mobile-drawer-backdrop" class="fixed inset-0 bg-black/40 backdrop-blur-[1px] z-30 hidden lg:hidden"></div>

        {{-- Panel Kiri: Filter Layanan & Kontrol Radius Spasial --}}
        @include('map.partials.filter-panel')

        {{-- Kanvas Tengah: Peta Interaktif Leaflet & Kontrol GIS --}}
        @include('map.partials.map-canvas')

        {{-- Panel Kanan: Daftar Puskesmas & Mesin Rekomendasi MAUT --}}
        @include('map.partials.sidebar-panel')
    </main>

    {{-- Modal Detail Puskesmas & Kelengkapan 9 Nakes --}}
    @include('map.partials.detail-modal')

    {{-- Payload JSON Data Puskesmas untuk Modul JS Peta --}}
    <script id="puskesmas-data" type="application/json">
        {!! json_encode($puskesmas) !!}
    </script>
</x-layout>
