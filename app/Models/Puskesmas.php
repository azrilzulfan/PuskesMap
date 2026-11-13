<?php

namespace App\Models;

use App\Enums\ServiceCategory;
use Database\Factories\PuskesmasFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

#[Fillable([
    'kecamatan_id',
    'kode_puskesmas',
    'nama_puskesmas',
    'alamat',
    'x_long',
    'y_lat',
    'website_url',
    'kategori_layanan',
    'jumlah_posyandu',
    'jumlah_sekolah',
    'skor_psw',
])]
class Puskesmas extends Model
{
    /** @use HasFactory<PuskesmasFactory> */
    use HasFactory;

    protected $table = 'puskesmas';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kecamatan_id' => 'integer',
            'x_long' => 'float',
            'y_lat' => 'float',
            'kategori_layanan' => ServiceCategory::class,
            'jumlah_posyandu' => 'integer',
            'jumlah_sekolah' => 'integer',
            'skor_psw' => 'float',
        ];
    }

    /**
     * @return BelongsTo<Kecamatan, $this>
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }

    /**
     * @return HasOne<Operasional, $this>
     */
    public function operasional(): HasOne
    {
        return $this->hasOne(Operasional::class, 'puskesmas_id');
    }

    /**
     * @return HasOne<TenagaMedis, $this>
     */
    public function tenagaMedis(): HasOne
    {
        return $this->hasOne(TenagaMedis::class, 'puskesmas_id');
    }

    /**
     * @return HasMany<KunjunganPasien, $this>
     */
    public function kunjunganPasien(): HasMany
    {
        return $this->hasMany(KunjunganPasien::class, 'puskesmas_id');
    }

    /**
     * @return BelongsTo<KunjunganPasien, $this>
     */
    public function latestKunjungan(): BelongsTo
    {
        return $this->belongsTo(KunjunganPasien::class, 'latest_kunjungan_id');
    }

    /**
     * Eager load the most recent monthly visit row per facility without N+1 queries.
     *
     * @param  Builder<Puskesmas>  $query
     */
    public function scopeWithLatestKunjungan(Builder $query, ?int $tahun = null, ?int $bulan = null): void
    {
        if (empty($query->getQuery()->columns)) {
            $query->select('puskesmas.*');
        }

        $subquery = KunjunganPasien::select('id')
            ->whereColumn('puskesmas_id', 'puskesmas.id')
            ->when($tahun !== null, fn (Builder $q) => $q->where('tahun', $tahun))
            ->when($bulan !== null, fn (Builder $q) => $q->where('bulan', $bulan))
            ->orderByDesc('tahun')
            ->orderByDesc('bulan')
            ->take(1);

        $query->addSelect(['latest_kunjungan_id' => $subquery])
            ->with('latestKunjungan');
    }

    protected static function booted(): void
    {
        static::saved(function (Puskesmas $puskesmas): void {
            if (DB::connection()->getDriverName() !== 'pgsql') {
                return;
            }

            if ($puskesmas->wasRecentlyCreated || $puskesmas->wasChanged(['x_long', 'y_lat'])) {
                DB::update(
                    'UPDATE puskesmas SET geom = ST_SetSRID(ST_MakePoint(?, ?), 4326) WHERE id = ?',
                    [(float) $puskesmas->x_long, (float) $puskesmas->y_lat, $puskesmas->id],
                );
            }
        });
    }
}
