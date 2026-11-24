<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analitik Spasial PuskesMap Kota Bogor</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #111C24; margin: 18px; }
        h1 { font-size: 16px; margin: 0 0 4px 0; color: #0A6663; }
        h2 { font-size: 12px; margin: 16px 0 6px 0; color: #074E4C; border-bottom: 1px solid #E1E7EC; padding-bottom: 3px; }
        .meta { font-size: 9px; color: #4E5D6C; margin-bottom: 12px; }
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .kpi td { width: 25%; border: 1px solid #E1E7EC; padding: 8px; background: #F4F7F6; vertical-align: top; }
        .kpi-label { font-size: 8px; color: #4E5D6C; text-transform: uppercase; }
        .kpi-value { font-size: 13px; font-weight: bold; color: #111C24; margin-top: 2px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.data th, table.data td { border: 1px solid #E1E7EC; padding: 5px 6px; text-align: left; }
        table.data th { background: #EAF4F3; color: #074E4C; font-weight: bold; }
        .num { text-align: right; }
        .badge-tinggi { color: #DC2626; font-weight: bold; }
        .badge-ideal { color: #16A34A; font-weight: bold; }
        .badge-rendah { color: #2563EB; font-weight: bold; }
    </style>
</head>
<body>
@php
    $coverageByDistrict = collect($coverage['per_kecamatan'] ?? [])->keyBy('kecamatan_id');
@endphp
    <h1>Laporan Analitik Spasial Layanan Puskesmas Kota Bogor (PuskesMap SDSS)</h1>
    <div class="meta">
        Dicetak pada: {{ $generatedAt }} &bull; Wilayah: {{ $selectedDistrictName ? 'Kec. '.$selectedDistrictName : 'Seluruh Kota Bogor' }} &bull; Tahun Kunjungan: {{ $tahun }} &bull; Radius Buffer Analisis: {{ $bufferKm }} km
    </div>

    <table class="grid kpi">
        <tr>
            <td>
                <div class="kpi-label">Cakupan Wilayah ({{ $bufferKm }} km)</div>
                <div class="kpi-value">{{ $coverageScope['persentase_terlayani'] ?? $coverage['summary']['persentase_terlayani'] }}% Terlayani</div>
                <div>Blankspot: {{ $coverageScope['blankspot_km2'] ?? $coverage['summary']['blankspot_km2'] }} km&sup2; ({{ $coverageScope['persentase_blankspot'] ?? $coverage['summary']['persentase_blankspot'] }}%)</div>
            </td>
            <td>
                <div class="kpi-label">Rasio Ekuitas (1 : {{ number_format($equity['standard_population_per_facility'], 0, ',', '.') }})</div>
                <div class="kpi-value">{{ $equity['city_totals']['puskesmas_count'] }} / {{ $equity['city_totals']['target_count'] }} Unit</div>
                <div>{{ $selectedDistrictName ? 'Defisit Wilayah' : 'Defisit Kota' }}: {{ $equity['city_totals']['deficit'] }} unit</div>
            </td>
            <td>
                <div class="kpi-label">{{ $selectedDistrictName ? 'Total Populasi Kecamatan' : 'Total Populasi Kota' }}</div>
                <div class="kpi-value">{{ number_format($equity['city_totals']['population'], 0, ',', '.') }} Jiwa</div>
                <div>Luas Wilayah: {{ $coverageScope['luas_wilayah_km2'] ?? $coverage['summary']['luas_kota_km2'] }} km&sup2;</div>
            </td>
            <td>
                <div class="kpi-label">Spektrum Beban PSW</div>
                <div class="kpi-value">Rata-rata: {{ $workload['summary']['average_score'] }}</div>
                <div>Tinggi: {{ $workload['summary']['counts']['Tinggi'] ?? 0 }} | Ideal: {{ $workload['summary']['counts']['Ideal'] ?? 0 }} | Rendah: {{ $workload['summary']['counts']['Rendah'] ?? 0 }}</div>
            </td>
        </tr>
    </table>

    <h2>Ringkasan Ekuitas &amp; Area Cakupan Per Kecamatan</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Kecamatan</th>
                <th class="num">Penduduk</th>
                <th class="num">Puskesmas</th>
                <th class="num">Target Ideal</th>
                <th>Status Rasio</th>
                <th class="num">Terlayani (%)</th>
                <th class="num">Blankspot (km&sup2;)</th>
                <th class="num">Rata-rata PSW</th>
                <th>Spektrum</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equity['districts'] as $district)
                @php
                    $distCov = $coverageByDistrict->get($district['id']);
                @endphp
                <tr>
                    <td>{{ $district['nama_kecamatan'] }}</td>
                    <td class="num">{{ number_format($district['jumlah_penduduk'], 0, ',', '.') }}</td>
                    <td class="num">{{ $district['puskesmas_count'] }}</td>
                    <td class="num">{{ $district['target_count'] }}</td>
                    <td>{{ $district['ratio_status'] }}</td>
                    <td class="num">{{ $distCov ? $distCov['persentase_terlayani'].'%' : '-' }}</td>
                    <td class="num">{{ $distCov ? $distCov['blankspot_km2'] : '-' }}</td>
                    <td class="num">{{ $district['avg_psw_score'] }}</td>
                    <td>{{ $district['psw_spectrum'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Detail Indikator Operasional, Nakes, &amp; Beban Wilayah Puskesmas</h2>
    <table class="data">
        <thead>
            <tr>
                <th>Kode</th>
                <th>Nama Puskesmas</th>
                <th>Kecamatan</th>
                <th>IGD</th>
                <th class="num">Nakes (%)</th>
                <th class="num">Posyandu</th>
                <th class="num">Sekolah</th>
                <th class="num">Pasien/Bln</th>
                <th class="num">Keramaian (%)</th>
                <th class="num">Skor PSW</th>
                <th>Spektrum</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facilities as $item)
                <tr>
                    <td>{{ $item['kode_puskesmas'] }}</td>
                    <td>{{ $item['nama_puskesmas'] }}</td>
                    <td>{{ $item['nama_kecamatan'] }}</td>
                    <td>{{ $item['igd_24jam'] ? '24 Jam' : 'Reguler' }}</td>
                    <td class="num">{{ $item['persentase_kelengkapan'] }}%</td>
                    <td class="num">{{ $item['jumlah_posyandu'] }}</td>
                    <td class="num">{{ $item['jumlah_sekolah'] }}</td>
                    <td class="num">{{ number_format($item['total_pasien_bulanan'], 0, ',', '.') }}</td>
                    <td class="num">{{ $item['persentase_keramaian'] }}%</td>
                    <td class="num">{{ $item['skor_psw'] }}</td>
                    <td class="badge-{{ strtolower($item['spektrum']) }}">{{ $item['spektrum'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
