<?php

namespace App\Models;

use App\Enums\CrowdStatus;
use Database\Factories\KunjunganPasienFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'puskesmas_id',
    'bulan',
    'tahun',
    'total_pasien_bulanan',
    'kapasitas_maksimal_bulanan',
    'persentase_keramaian',
    'status_kepadatan',
])]
class KunjunganPasien extends Model
{
    /** @use HasFactory<KunjunganPasienFactory> */
    use HasFactory;

    protected $table = 'kunjungan_pasien';

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'puskesmas_id' => 'integer',
            'bulan' => 'integer',
            'tahun' => 'integer',
            'total_pasien_bulanan' => 'integer',
            'kapasitas_maksimal_bulanan' => 'integer',
            'persentase_keramaian' => 'float',
            'status_kepadatan' => CrowdStatus::class,
        ];
    }

    /**
     * @return BelongsTo<Puskesmas, $this>
     */
    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class, 'puskesmas_id');
    }

    public function recomputeMetrics(): void
    {
        $capacity = max(1, (int) ($this->kapasitas_maksimal_bulanan ?: config('sdss.capacity.default_monthly', 850)));
        $percentage = round(((int) $this->total_pasien_bulanan / $capacity) * 100, 2);

        $this->kapasitas_maksimal_bulanan = $capacity;
        $this->persentase_keramaian = $percentage;
        $this->status_kepadatan = CrowdStatus::fromPercentage($percentage);
    }
}
