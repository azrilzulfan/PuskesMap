<?php

namespace App\Http\Resources;

use App\Enums\WorkloadSpectrum;
use App\Models\Puskesmas;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Puskesmas
 */
class PuskesmasGeoJsonResource extends JsonResource
{
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

        $properties = [
            'id' => $this->id,
            'kode_puskesmas' => $this->kode_puskesmas,
            'nama_puskesmas' => $this->nama_puskesmas,
            'alamat' => $this->alamat,
            'kecamatan_id' => $this->kecamatan_id,
            'nama_kecamatan' => $kecamatan?->nama_kecamatan,
            'kategori_layanan' => $this->kategori_layanan->value,
            'status_igd_24jam' => (bool) ($operasional?->status_igd_24jam ?? false),
            'rawat_inap' => (bool) ($operasional?->rawat_inap ?? false),
            'jam_buka' => $operasional?->jam_buka,
            'jam_tutup' => $operasional?->jam_tutup,
            'durasi_jam' => $operasional?->durationHours(),
            'is_open_now' => $operasional?->isOpenAt() ?? false,
            'kontak_darurat' => $operasional?->kontak_darurat,
            'persentase_kelengkapan_nakes' => $tenagaMedis ? round((float) $tenagaMedis->persentase_kelengkapan, 2) : null,
            'kategori_jumlah_nakes' => $tenagaMedis?->kategori_jumlah_nakes->value,
            'total_nakes' => $tenagaMedis ? array_sum($tenagaMedis->professionCounts()) : null,
            'status_kepadatan' => $latestKunjungan?->status_kepadatan->value,
            'persentase_keramaian' => $latestKunjungan ? round((float) $latestKunjungan->persentase_keramaian, 2) : null,
            'total_pasien_bulanan' => $latestKunjungan?->total_pasien_bulanan,
            'jumlah_posyandu' => (int) $this->jumlah_posyandu,
            'jumlah_sekolah' => (int) $this->jumlah_sekolah,
            'skor_psw' => $pswScore,
            'spektrum_beban' => $pswScore !== null ? WorkloadSpectrum::fromScore($pswScore)->value : null,
            'website_url' => $this->website_url,
        ];

        if ($distanceMeters !== null) {
            $properties['distance_meters'] = round((float) $distanceMeters, 1);
            $properties['distance_km'] = round(((float) $distanceMeters) / 1000.0, 2);
        }

        return [
            'type' => 'Feature',
            'geometry' => [
                'type' => 'Point',
                'coordinates' => [
                    (float) $this->x_long,
                    (float) $this->y_lat,
                ],
            ],
            'properties' => $properties,
        ];
    }
}
