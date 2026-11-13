<?php

namespace App\Http\Resources;

use App\Enums\WorkloadSpectrum;
use App\Models\Puskesmas;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Puskesmas
 */
class PuskesmasSummaryResource extends JsonResource
{
    public static $wrap = null;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $kecamatan = $this->relationLoaded('kecamatan') ? $this->kecamatan : null;
        $operasional = $this->relationLoaded('operasional') ? $this->operasional : null;
        $tenagaMedis = $this->relationLoaded('tenagaMedis') ? $this->tenagaMedis : null;
        $latestKunjungan = $this->relationLoaded('latestKunjungan') ? $this->latestKunjungan : null;

        $pswScore = $this->skor_psw !== null ? round((float) $this->skor_psw, 4) : null;
        $distanceMeters = $this->getAttribute('distance_meters');

        return [
            'id' => $this->id,
            'kode_puskesmas' => $this->kode_puskesmas,
            'nama_puskesmas' => $this->nama_puskesmas,
            'alamat' => $this->alamat,
            'kategori_layanan' => $this->kategori_layanan->value,
            'website_url' => $this->website_url,
            'coordinates' => [
                'latitude' => (float) $this->y_lat,
                'longitude' => (float) $this->x_long,
            ],
            'kecamatan' => $kecamatan ? [
                'id' => $kecamatan->id,
                'kode_kecamatan' => $kecamatan->kode_kecamatan,
                'nama_kecamatan' => $kecamatan->nama_kecamatan,
            ] : [
                'id' => $this->kecamatan_id,
            ],
            'operasional' => $operasional ? [
                'status_igd_24jam' => (bool) $operasional->status_igd_24jam,
                'jam_buka' => (string) $operasional->jam_buka,
                'jam_tutup' => (string) $operasional->jam_tutup,
                'durasi_jam' => $operasional->durationHours(),
                'jam_label' => $operasional->formattedHoursLabel(),
                'is_open_now' => $operasional->isOpenAt(),
                'kontak_darurat' => $operasional->kontak_darurat,
                'rawat_inap' => (bool) $operasional->rawat_inap,
            ] : null,
            'tenaga_medis' => $tenagaMedis ? [
                'profesi' => $tenagaMedis->professionCounts(),
                'total_nakes' => array_sum($tenagaMedis->professionCounts()),
                'persentase_kelengkapan' => round((float) $tenagaMedis->persentase_kelengkapan, 2),
                'kategori_jumlah_nakes' => $tenagaMedis->kategori_jumlah_nakes->value,
            ] : null,
            'kunjungan_terbaru' => $latestKunjungan ? [
                'bulan' => (int) $latestKunjungan->bulan,
                'tahun' => (int) $latestKunjungan->tahun,
                'total_pasien_bulanan' => (int) $latestKunjungan->total_pasien_bulanan,
                'kapasitas_maksimal_bulanan' => (int) $latestKunjungan->kapasitas_maksimal_bulanan,
                'persentase_keramaian' => round((float) $latestKunjungan->persentase_keramaian, 2),
                'status_kepadatan' => $latestKunjungan->status_kepadatan->value,
            ] : null,
            'kapasitas_wilayah' => [
                'jumlah_posyandu' => (int) $this->jumlah_posyandu,
                'jumlah_sekolah' => (int) $this->jumlah_sekolah,
            ],
            'skor_psw' => $pswScore,
            'spektrum_beban' => $pswScore !== null ? WorkloadSpectrum::fromScore($pswScore)->value : null,
            $this->mergeWhen($distanceMeters !== null, [
                'distance_meters' => round((float) $distanceMeters, 1),
                'distance_km' => round(((float) $distanceMeters) / 1000.0, 2),
            ]),
        ];
    }
}
