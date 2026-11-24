@php
    $editing = isset($puskesmas);
    $submitLabel = $submitLabel ?? 'Simpan Perubahan';
    $submitAccent = $submitAccent ?? false;
@endphp

@if ($errors->any())
    <div role="alert" class="mb-5 rounded-[20px] border-l-4 border-[#B42318] bg-white px-5 py-3.5 text-xs text-[#B42318] shadow-[0_6px_20px_rgba(0,121,121,0.06)]">
        <ul class="list-disc space-y-1 pl-4 font-semibold">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-5 lg:grid-cols-12">
    {{-- Bento Kiri (7 cols): Identitas & Peta Koordinat --}}
    <section class="lg:col-span-7 space-y-5 rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
        <h2 class="border-b border-primary/10 pb-3 text-base font-extrabold text-ink">Identitas &amp; Lokasi Geodetik</h2>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="kode_puskesmas" class="block text-xs font-bold text-ink">Kode Puskesmas</label>
                <input id="kode_puskesmas" name="kode_puskesmas" type="text" required
                       value="{{ old('kode_puskesmas', $puskesmas->kode_puskesmas ?? '') }}"
                       class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink transition focus:bg-white focus:border-primary">
                @error('kode_puskesmas')
                    <p class="mt-1.5 text-xs font-semibold text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="kecamatan_id" class="block text-xs font-bold text-ink">Kecamatan</label>
                <select id="kecamatan_id" name="kecamatan_id" required
                        class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink transition focus:bg-white focus:border-primary">
                    <option value="">Pilih Kecamatan...</option>
                    @foreach ($districts as $district)
                        <option value="{{ $district->id }}" @selected((int) old('kecamatan_id', $puskesmas->kecamatan_id ?? 0) === $district->id)>
                            {{ $district->nama_kecamatan }}
                        </option>
                    @endforeach
                </select>
                @error('kecamatan_id')
                    <p class="mt-1.5 text-xs font-semibold text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="nama_puskesmas" class="block text-xs font-bold text-ink">Nama Puskesmas</label>
                <input id="nama_puskesmas" name="nama_puskesmas" type="text" required
                       value="{{ old('nama_puskesmas', $puskesmas->nama_puskesmas ?? '') }}"
                       class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink transition focus:bg-white focus:border-primary">
                @error('nama_puskesmas')
                    <p class="mt-1.5 text-xs font-semibold text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>

            <div class="sm:col-span-2">
                <label for="alamat" class="block text-xs font-bold text-ink">Alamat Lengkap</label>
                <textarea id="alamat" name="alamat" rows="2" required
                          class="mt-1.5 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink transition focus:bg-white focus:border-primary">{{ old('alamat', $puskesmas->alamat ?? '') }}</textarea>
                @error('alamat')
                    <p class="mt-1.5 text-xs font-semibold text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="y_lat" class="block text-xs font-bold text-ink">Latitude (y_lat)</label>
                <input id="y_lat" name="y_lat" type="number" step="0.000001" required
                       value="{{ old('y_lat', $puskesmas->y_lat ?? '-6.595000') }}"
                       class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink tabular-nums transition focus:bg-white focus:border-primary">
                @error('y_lat')
                    <p class="mt-1.5 text-xs font-semibold text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="x_long" class="block text-xs font-bold text-ink">Longitude (x_long)</label>
                <input id="x_long" name="x_long" type="number" step="0.000001" required
                       value="{{ old('x_long', $puskesmas->x_long ?? '106.795000') }}"
                       class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink tabular-nums transition focus:bg-white focus:border-primary">
                @error('x_long')
                    <p class="mt-1.5 text-xs font-semibold text-[#B42318]">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div>
            <div class="mb-2 flex items-center justify-between gap-2">
                <p class="text-xs font-bold text-ink">Pemilih Koordinat Peta Kota Bogor (klik atau geser pin pada peta)</p>
                <button type="button" id="btn-center-coord-map"
                        class="rounded-lg bg-surface-mint px-2.5 py-1 text-[11px] font-bold text-primary-dark transition hover:bg-card-mint cursor-pointer">
                    Pusatkan Bogor
                </button>
            </div>
            <div id="admin-coord-map-wrap" class="relative h-64 w-full overflow-hidden rounded-2xl border border-primary/20 bg-surface-mint/40">
                <div id="admin-coord-map" class="isolate z-0 h-full w-full"></div>
            </div>
        </div>
    </section>

    {{-- Bento Kanan (5 cols): Layanan, Binaan UKM & Simpan --}}
    <aside class="lg:col-span-5 flex flex-col justify-between gap-5 rounded-[24px] bg-white p-6 shadow-[0_8px_24px_rgba(0,121,121,0.05)]">
        <div class="space-y-4">
            <h2 class="border-b border-primary/10 pb-3 text-base font-extrabold text-ink">Layanan &amp; Binaan UKM</h2>

            <div>
                <label for="kategori_layanan" class="block text-xs font-bold text-ink">Kategori Layanan</label>
                <select id="kategori_layanan" name="kategori_layanan" required
                        class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink transition focus:bg-white focus:border-primary">
                    @foreach ($categories as $category)
                        <option value="{{ $category->value }}" @selected(old('kategori_layanan', isset($puskesmas) ? $puskesmas->kategori_layanan->value : \App\Enums\ServiceCategory::NonRawatInap->value) === $category->value)>
                            {{ $category->value }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="website_url" class="block text-xs font-bold text-ink">Website Resmi</label>
                <input id="website_url" name="website_url" type="url"
                       value="{{ old('website_url', $puskesmas->website_url ?? '') }}"
                       class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink transition focus:bg-white focus:border-primary">
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="jumlah_posyandu" class="block text-xs font-bold text-ink">Posyandu Binaan</label>
                    <input id="jumlah_posyandu" name="jumlah_posyandu" type="number" min="0" required
                           value="{{ old('jumlah_posyandu', $puskesmas->jumlah_posyandu ?? 0) }}"
                           class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink tabular-nums transition focus:bg-white focus:border-primary">
                </div>

                <div>
                    <label for="jumlah_sekolah" class="block text-xs font-bold text-ink">Sekolah Binaan</label>
                    <input id="jumlah_sekolah" name="jumlah_sekolah" type="number" min="0" required
                           value="{{ old('jumlah_sekolah', $puskesmas->jumlah_sekolah ?? 0) }}"
                           class="mt-1.5 min-h-11 w-full rounded-xl border border-[#7B8A97] bg-input px-3.5 py-2.5 text-sm text-ink tabular-nums transition focus:bg-white focus:border-primary">
                </div>
            </div>

            @if (! $editing)
                <div class="space-y-2 rounded-2xl bg-[#f7fbfa] p-4">
                    <label class="flex min-h-10 items-center gap-2.5 text-xs font-bold text-ink cursor-pointer">
                        <input type="checkbox" name="status_igd_24jam" value="1" class="size-4 accent-primary" @checked(old('status_igd_24jam'))>
                        <span>Memiliki IGD 24 Jam</span>
                    </label>
                </div>
            @endif
        </div>

        <div class="flex flex-wrap items-center justify-end gap-2.5 border-t border-primary/10 pt-5">
            <a href="{{ route('admin.puskesmas.index') }}"
               class="inline-flex min-h-11 items-center rounded-xl bg-surface-mint px-5 py-2.5 text-xs font-bold text-primary-dark transition hover:bg-card-mint">
                Batal
            </a>
            <button type="submit"
                    class="inline-flex min-h-11 flex-1 sm:flex-initial items-center justify-center rounded-xl {{ $submitAccent ? 'bg-accent hover:bg-accent-strong' : 'bg-gradient-to-r from-primary to-primary-dark hover:opacity-95' }} px-6 py-2.5 text-xs font-extrabold text-white shadow-xs transition cursor-pointer">
                {{ $submitLabel }}
            </button>
        </div>
    </aside>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.L) { return; }
        var latInput = document.getElementById('y_lat');
        var lngInput = document.getElementById('x_long');
        var initLat = parseFloat(latInput.value) || -6.597147;
        var initLng = parseFloat(lngInput.value) || 106.806039;

        var map = window.L.map('admin-coord-map').setView([initLat, initLng], 13);
        window.L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        var marker = window.L.marker([initLat, initLng], { draggable: true }).addTo(map);

        var wrap = document.getElementById('admin-coord-map-wrap');
        if (window.ResizeObserver && wrap) {
            new ResizeObserver(function () { map.invalidateSize(); }).observe(wrap);
        }

        function setCoords(lat, lng) {
            latInput.value = lat.toFixed(6);
            lngInput.value = lng.toFixed(6);
            marker.setLatLng([lat, lng]);
        }

        map.on('click', function (e) {
            setCoords(e.latlng.lat, e.latlng.lng);
        });

        marker.on('dragend', function () {
            var pos = marker.getLatLng();
            setCoords(pos.lat, pos.lng);
        });

        document.getElementById('btn-center-coord-map')?.addEventListener('click', function () {
            setCoords(-6.597147, 106.806039);
            map.setView([-6.597147, 106.806039], 13);
        });

        function syncFromInputs() {
            var lat = parseFloat(latInput.value);
            var lng = parseFloat(lngInput.value);
            if (!Number.isNaN(lat) && !Number.isNaN(lng)) {
                marker.setLatLng([lat, lng]);
                map.panTo([lat, lng]);
            }
        }

        latInput.addEventListener('change', syncFromInputs);
        lngInput.addEventListener('change', syncFromInputs);
    });
</script>
