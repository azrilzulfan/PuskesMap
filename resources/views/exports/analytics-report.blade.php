<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Analitik Spasial PuskesMap Kota Bogor</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; color: #1d2b2b; margin: 18px; }
        h1 { font-size: 16px; margin: 0 0 4px 0; color: #007979; }
        h2 { font-size: 12px; margin: 16px 0 6px 0; color: #0b5c5c; border-bottom: 1px solid #d1e3e0; padding-bottom: 3px; }
        .meta { font-size: 9px; color: #616f6f; margin-bottom: 12px; }
        .grid { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        .kpi td { width: 25%; border: 1px solid #d7e5e3; padding: 8px; background: #f4f9f8; vertical-align: top; }
        .kpi-label { font-size: 8px; color: #616f6f; text-transform: uppercase; }
        .kpi-value { font-size: 13px; font-weight: bold; color: #1d2b2b; margin-top: 2px; }
        table.data { width: 100%; border-collapse: collapse; margin-top: 6px; }
        table.data th, table.data td { border: 1px solid #dce7e5; padding: 5px 6px; text-align: left; }
        table.data th { background: #e8f4f2; color: #0b5c5c; font-weight: bold; }
        .num { text-align: right; }
        .badge-tinggi { color: #b91c1c; font-weight: bold; }
        .badge-ideal { color: #15803d; font-weight: bold; }
        .badge-rendah { color: #1d4ed8; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Laporan Analitik Spasial Layanan Puskesmas Kota Bogor (PuskesMap SDSS)</h1>
    <div class="meta">
        Dicetak pada: {{ $generatedAt }} &bull; Tahun Kunjungan: {{ $tahun }} &bull; Radius Buffer Analisis: {{ $bufferKm }} km
    </div>

    <table class="grid kpi">
        <tr>
            <td>
                <div class="kpi-label">Cakupan Wilayah ({{ $bufferKm }} km)</div>
                <div class="kpi-value">{{ $coverage['summary']['persentase_terlayani'] }}% Terlayani</div>
                <div>Blankspot: {{ $coverage['summary']['blankspot_km2'] }} km&sup2; ({{ $coverage['summary']['persentase_blankspot'] }}%)</div>
            </td>
            <td>
                <div class="kpi-label">Rasio Ekuitas (1 : {{ number_format($equity['standard_population_per_facility'], 0, ',', '.') }})</div>
                <div class="kpi-value">{{ $equity['city_totals']['puskesmas_count'] }} / {{ $equity['city_totals']['target_count'] }} Unit</div>
                <div>Defisit Kota: {{ $equity['city_totals']['deficit'] }} unit</div>
            </td>
            <td>
                <div class="kpi-label">Total Populasi Kota</div>
                <div class="kpi-value">{{ number_format($equity['city_totals']['population'], 0, ',', '.') }} Jiwa</div>
                <div>Luas Kota: {{ $coverage['summary']['luas_kota_km2'] }} km&sup2;</div>
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
                <th class="num">Rata-rata PSW</th>
                <th>Spektrum</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($equity['districts'] as $district)
                <tr>
                    <td>{{ $district['nama_kecamatan'] }}</td>
                    <td class="num">{{ number_format($district['jumlah_penduduk'], 0, ',', '.') }}</td>
                    <td class="num">{{ $district['puskesmas_count'] }}</td>
                    <td class="num">{{ $district['target_count'] }}</td>
                    <td>{{ $district['ratio_status'] }}</td>
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
