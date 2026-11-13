<?php

namespace App\Models;

use App\Enums\NakesCategory;
use Database\Factories\TenagaMedisFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'puskesmas_id',
    'dokter_umum',
    'dokter_gigi',
    'bidan',
    'perawat',
    'farmasi',
    'gizi',
    'kesling',
    'kesmas',
    'atlm',
    'persentase_kelengkapan',
    'kategori_jumlah_nakes',
])]
class TenagaMedis extends Model
{
    /** @use HasFactory<TenagaMedisFactory> */
    use HasFactory;

    protected $table = 'tenaga_medis';

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'puskesmas_id' => 'integer',
            'dokter_umum' => 'integer',
            'dokter_gigi' => 'integer',
            'bidan' => 'integer',
            'perawat' => 'integer',
            'farmasi' => 'integer',
            'gizi' => 'integer',
            'kesling' => 'integer',
            'kesmas' => 'integer',
            'atlm' => 'integer',
            'persentase_kelengkapan' => 'float',
            'kategori_jumlah_nakes' => NakesCategory::class,
        ];
    }

    /**
     * @return BelongsTo<Puskesmas, $this>
     */
    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class, 'puskesmas_id');
    }

    /**
     * @return array<string, int>
     */
    public function professionCounts(): array
    {
        return [
            'Dokter' => (int) $this->dokter_umum,
            'Dokter Gigi' => (int) $this->dokter_gigi,
            'Perawat' => (int) $this->perawat,
            'Bidan' => (int) $this->bidan,
            'Tenaga Kesmas' => (int) $this->kesmas,
            'Tenaga Kesling' => (int) $this->kesling,
            'ATLM' => (int) $this->atlm,
            'Tenaga Gizi' => (int) $this->gizi,
            'Farmasi' => (int) $this->farmasi,
        ];
    }

    public function countPresentProfessions(): int
    {
        $professions = config('sdss.nakes.professions', []);
        $present = 0;

        foreach ($professions as $column) {
            if ((int) ($this->{$column} ?? 0) > 0) {
                $present++;
            }
        }

        return $present;
    }

    public function totalCount(): int
    {
        return array_sum($this->professionCounts());
    }

    public function recomputeMetrics(): void
    {
        $totalProfessions = max(1, count(config('sdss.nakes.professions', [])));
        $present = $this->countPresentProfessions();
        $percentage = round(($present / $totalProfessions) * 100, 2);
        $completeThreshold = (float) config('sdss.nakes.complete_threshold', 100.0);

        $this->persentase_kelengkapan = $percentage;
        $this->kategori_jumlah_nakes = $percentage >= $completeThreshold
            ? NakesCategory::Lengkap
            : NakesCategory::KurangLengkap;
    }
}
