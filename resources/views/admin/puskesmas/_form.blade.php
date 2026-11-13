@php
    $editing = isset($puskesmas);
@endphp

@if ($errors->any())
    <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-xs text-red-800">
        <ul class="list-disc space-y-1 pl-4">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
    <div>
        <label for="kode_puskesmas" class="block text-xs font-bold text-ink">Kode Puskesmas</label>
        <input id="kode_puskesmas" name="kode_puskesmas" type="text" required
               value="{{ old('kode_puskesmas', $puskesmas->kode_puskesmas ?? '') }}"
               class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">
    </div>

    <div>
        <label for="kecamatan_id" class="block text-xs font-bold text-ink">Kecamatan</label>
        <select id="kecamatan_id" name="kecamatan_id" required
                class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">
            <option value="">Pilih Kecamatan...</option>
            @foreach ($districts as $district)
                <option value="{{ $district->id }}" @selected((int) old('kecamatan_id', $puskesmas->kecamatan_id ?? 0) === $district->id)>
                    {{ $district->nama_kecamatan }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="sm:col-span-2">
        <label for="nama_puskesmas" class="block text-xs font-bold text-ink">Nama Puskesmas</label>
        <input id="nama_puskesmas" name="nama_puskesmas" type="text" required
               value="{{ old('nama_puskesmas', $puskesmas->nama_puskesmas ?? '') }}"
               class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">
    </div>

    <div class="sm:col-span-2">
        <label for="alamat" class="block text-xs font-bold text-ink">Alamat Lengkap</label>
        <textarea id="alamat" name="alamat" rows="2" required
                  class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">{{ old('alamat', $puskesmas->alamat ?? '') }}</textarea>
    </div>

    <div>
        <label for="y_lat" class="block text-xs font-bold text-ink">Latitude (y_lat)</label>
        <input id="y_lat" name="y_lat" type="number" step="0.000001" required
               value="{{ old('y_lat', $puskesmas->y_lat ?? '-6.595000') }}"
               class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">
    </div>

    <div>
        <label for="x_long" class="block text-xs font-bold text-ink">Longitude (x_long)</label>
        <input id="x_long" name="x_long" type="number" step="0.000001" required
               value="{{ old('x_long', $puskesmas->x_long ?? '106.795000') }}"
               class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">
    </div>

    <div class="sm:col-span-2">
        <p class="mb-1.5 text-xs font-bold text-ink">Pemilih Koordinat Peta Kota Bogor (klik peta untuk mengisi koordinat)</p>
        <div id="admin-coord-map" class="h-64 w-full rounded-xl border border-gray-200 bg-surface-mint/40"></div>
    </div>

    <div>
        <label for="kategori_layanan" class="block text-xs font-bold text-ink">Kategori Layanan</label>
        <select id="kategori_layanan" name="kategori_layanan" required
                class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">
            @foreach ($categories as $category)
                <option value="{{ $category->value }}" @selected(old('kategori_layanan', $puskesmas->kategori_layanan->value ?? '') === $category->value)>
                    {{ $category->value }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="website_url" class="block text-xs font-bold text-ink">Website Resmi</label>
        <input id="website_url" name="website_url" type="url"
               value="{{ old('website_url', $puskesmas->website_url ?? '') }}"
               class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">
    </div>

    <div>
        <label for="jumlah_posyandu" class="block text-xs font-bold text-ink">Jumlah Posyandu Binaan</label>
        <input id="jumlah_posyandu" name="jumlah_posyandu" type="number" min="0" required
               value="{{ old('jumlah_posyandu', $puskesmas->jumlah_posyandu ?? 0) }}"
               class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">
    </div>

    <div>
        <label for="jumlah_sekolah" class="block text-xs font-bold text-ink">Jumlah Sekolah Binaan</label>
        <input id="jumlah_sekolah" name="jumlah_sekolah" type="number" min="0" required
               value="{{ old('jumlah_sekolah', $puskesmas->jumlah_sekolah ?? 0) }}"
               class="mt-1.5 w-full rounded-xl border border-gray-200 bg-input px-3 py-2.5 text-sm text-ink">
    </div>

    @if (! $editing)
        <div class="sm:col-span-2 grid grid-cols-1 gap-4 border-t border-gray-100 pt-4 sm:grid-cols-2">
            <label class="flex items-center gap-2 text-xs font-bold text-ink">
                <input type="checkbox" name="status_igd_24jam" value="1" @checked(old('status_igd_24jam'))>
                <span>Memiliki IGD 24 Jam</span>
            </label>
            <label class="flex items-center gap-2 text-xs font-bold text-ink">
                <input type="checkbox" name="rawat_inap" value="1" @checked(old('rawat_inap'))>
                <span>Tersedia Rawat Inap</span>
            </label>
        </div>
    @endif
</div>

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        if (!window.L) return;
        var latInput = document.getElementById('y_lat');
        var lngInput = document.getElementById('x_long');
        var initLat = parseFloat(latInput.value) || -6.595;
        var initLng = parseFloat(lngInput.value) || 106.795;

        var map = L.map('admin-coord-map').setView([initLat, initLng], 13);
        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', { maxZoom: 19 }).addTo(map);
        var marker = L.marker([initLat, initLng]).addTo(map);

        map.on('click', function (e) {
            latInput.value = e.latlng.lat.toFixed(6);
            lngInput.value = e.latlng.lng.toFixed(6);
            marker.setLatLng(e.latlng);
        });
    });
</script>
