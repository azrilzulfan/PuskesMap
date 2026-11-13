<?php

namespace App\Models;

use Database\Factories\KecamatanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['kode_kecamatan', 'nama_kecamatan', 'jumlah_penduduk', 'luas_wilayah_km2'])]
class Kecamatan extends Model
{
    /** @use HasFactory<KecamatanFactory> */
    use HasFactory;

    protected $table = 'kecamatan';

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'jumlah_penduduk' => 'integer',
            'luas_wilayah_km2' => 'float',
        ];
    }

    /**
     * @return HasMany<Puskesmas, $this>
     */
    public function puskesmas(): HasMany
    {
        return $this->hasMany(Puskesmas::class, 'kecamatan_id');
    }

    /**
     * @return HasMany<JangkauanJalan, $this>
     */
    public function jangkauanJalan(): HasMany
    {
        return $this->hasMany(JangkauanJalan::class, 'kecamatan_id');
    }
}
