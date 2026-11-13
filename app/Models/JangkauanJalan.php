<?php

namespace App\Models;

use Database\Factories\JangkauanJalanFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'kecamatan_id',
    'kategori_jangkauan',
    'jangkauan_faskes',
    'jangkauan_jalan',
])]
class JangkauanJalan extends Model
{
    /** @use HasFactory<JangkauanJalanFactory> */
    use HasFactory;

    protected $table = 'jangkauan_jalan';

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'kecamatan_id' => 'integer',
            'jangkauan_faskes' => 'integer',
            'jangkauan_jalan' => 'integer',
        ];
    }

    /**
     * @return BelongsTo<Kecamatan, $this>
     */
    public function kecamatan(): BelongsTo
    {
        return $this->belongsTo(Kecamatan::class, 'kecamatan_id');
    }
}
