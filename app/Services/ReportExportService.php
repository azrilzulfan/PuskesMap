<?php

namespace App\Services;

use App\Enums\WorkloadSpectrum;
use App\Models\Puskesmas;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportExportService
{
    public function __construct(
        private readonly SpatialAnalysisService $spatialAnalysis,
        private readonly WorkloadService $workloadService,
    ) {}

    public function streamCsv(?int $kecamatanId = null, float $bufferKm = 1.5, int $tahun = 2025): StreamedResponse
    {
        $rows = $this->collectFacilityReportRows($kecamatanId, $tahun);
        $filename = sprintf('laporan-analitik-puskesmap-%s.csv', now()->format('Ymd-His'));

        return response()->streamDownload(function () use ($rows, $bufferKm): void {
            $handle = fopen('php://output', 'wb');
            if ($handle === false) {
                return;
            }

            // Emit UTF-8 BOM for clean spreadsheet rendering on Windows/Excel.
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'Kode Puskesmas',
                'Nama Puskesmas',
                'Kecamatan',
                'Alamat',
                'Kategori Layanan',
                'IGD 24 Jam',
                'Jam Buka',
                'Jam Tutup',
                'Kelengkapan Nakes (%)',
                'Kategori Nakes',
                'Jumlah Posyandu',
                'Jumlah Sekolah',
                'Total Pasien Bulan Terakhir',
                'Keramaian (%)',
                'Status Kepadatan',
                'Skor PSW',
                'Spektrum Beban',
                'Buffer Cakupan (km)',
            ]);

            foreach ($rows as $row) {
                fputcsv($handle, [
                    $row['kode_puskesmas'],
                    $row['nama_puskesmas'],
                    $row['nama_kecamatan'],
                    $row['alamat'],
                    $row['kategori_layanan'],
                    $row['igd_24jam'] ? 'Ya' : 'Tidak',
                    $row['jam_buka'],
                    $row['jam_tutup'],
                    $row['persentase_kelengkapan'],
                    $row['kategori_jumlah_nakes'],
                    $row['jumlah_posyandu'],
                    $row['jumlah_sekolah'],
                    $row['total_pasien_bulanan'],
                    $row['persentase_keramaian'],
                    $row['status_kepadatan'],
                    $row['skor_psw'],
                    $row['spektrum'],
                    $bufferKm,
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function downloadPdf(?int $kecamatanId = null, float $bufferKm = 1.5, int $tahun = 2025): Response
    {
        $coverage = $this->spatialAnalysis->calculateServiceArea($bufferKm);
        $equity = $this->spatialAnalysis->getEquityAnalysis($kecamatanId);
        $workload = $this->workloadService->getWorkloadDistribution($kecamatanId, $tahun);
        $facilities = $this->collectFacilityReportRows($kecamatanId, $tahun);

        $filename = sprintf('laporan-analitik-puskesmap-%s.pdf', now()->format('Ymd-His'));

        $pdf = Pdf::loadView('exports.analytics-report', [
            'generatedAt' => now('Asia/Jakarta')->format('d M Y H:i').' WIB',
            'bufferKm' => $bufferKm,
            'tahun' => $tahun,
            'coverage' => $coverage,
            'equity' => $equity,
            'workload' => $workload,
            'facilities' => $facilities,
        ])->setPaper('a4', 'landscape');

        return $pdf->download($filename);
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function collectFacilityReportRows(?int $kecamatanId, int $tahun): array
    {
        $facilities = Puskesmas::query()
            ->with(['kecamatan', 'operasional', 'tenagaMedis'])
            ->withLatestKunjungan($tahun)
            ->when($kecamatanId !== null, fn ($q) => $q->where('kecamatan_id', $kecamatanId))
            ->orderByDesc('skor_psw')
            ->orderBy('kode_puskesmas')
            ->get();

        return $facilities->map(function (Puskesmas $facility): array {
            $score = (float) ($facility->skor_psw ?? 0.0);

            return [
                'kode_puskesmas' => $facility->kode_puskesmas,
                'nama_puskesmas' => $facility->nama_puskesmas,
                'nama_kecamatan' => $facility->kecamatan?->nama_kecamatan ?? '-',
                'alamat' => $facility->alamat,
                'kategori_layanan' => $facility->kategori_layanan->value,
                'igd_24jam' => (bool) ($facility->operasional?->status_igd_24jam ?? false),
                'jam_buka' => (string) ($facility->operasional?->jam_buka ?? '07:30:00'),
                'jam_tutup' => (string) ($facility->operasional?->jam_tutup ?? '14:00:00'),
                'persentase_kelengkapan' => (float) ($facility->tenagaMedis?->persentase_kelengkapan ?? 0.0),
                'kategori_jumlah_nakes' => $facility->tenagaMedis?->kategori_jumlah_nakes?->value ?? '-',
                'jumlah_posyandu' => $facility->jumlah_posyandu,
                'jumlah_sekolah' => $facility->jumlah_sekolah,
                'total_pasien_bulanan' => (int) ($facility->latestKunjungan?->total_pasien_bulanan ?? 0),
                'persentase_keramaian' => (float) ($facility->latestKunjungan?->persentase_keramaian ?? 0.0),
                'status_kepadatan' => $facility->latestKunjungan?->status_kepadatan?->value ?? '-',
                'skor_psw' => round($score, 2),
                'spektrum' => WorkloadSpectrum::fromScore($score)->value,
            ];
        })->all();
    }
}
