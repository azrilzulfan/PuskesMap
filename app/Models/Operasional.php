<?php

namespace App\Models;

use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Database\Factories\OperasionalFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'puskesmas_id',
    'status_igd_24jam',
    'jam_buka',
    'jam_tutup',
    'kontak_darurat',
    'rawat_inap',
])]
class Operasional extends Model
{
    /** @use HasFactory<OperasionalFactory> */
    use HasFactory;

    protected $table = 'operasional';

    public $timestamps = false;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'puskesmas_id' => 'integer',
            'status_igd_24jam' => 'boolean',
            'rawat_inap' => 'boolean',
        ];
    }

    /**
     * @return BelongsTo<Puskesmas, $this>
     */
    public function puskesmas(): BelongsTo
    {
        return $this->belongsTo(Puskesmas::class, 'puskesmas_id');
    }

    public function durationHours(): float
    {
        $open = CarbonImmutable::parse((string) $this->jam_buka);
        $close = CarbonImmutable::parse((string) $this->jam_tutup);

        if ($close->lessThanOrEqualTo($open)) {
            $close = $close->addDay();
        }

        $hours = $open->diffInMinutes($close) / 60;

        if ($hours >= 23.9) {
            return 24.0;
        }

        return round($hours, 2);
    }

    public function isOpenAt(?CarbonInterface $at = null): bool
    {
        if ($this->status_igd_24jam) {
            return true;
        }

        $timezone = (string) config('sdss.spatial.timezone', 'Asia/Jakarta');
        $now = ($at ? CarbonImmutable::instance($at) : CarbonImmutable::now($timezone))
            ->setTimezone($timezone);

        $current = $now->format('H:i:s');
        $open = substr((string) $this->jam_buka, 0, 8);
        $close = substr((string) $this->jam_tutup, 0, 8);

        if ($close <= $open) {
            return $current >= $open || $current <= $close;
        }

        return $current >= $open && $current <= $close;
    }

    public function formattedHoursLabel(): string
    {
        if ($this->status_igd_24jam) {
            return 'Buka 24 Jam (IGD)';
        }

        $open = str_replace(':', '.', substr((string) $this->jam_buka, 0, 5));
        $close = str_replace(':', '.', substr((string) $this->jam_tutup, 0, 5));

        return "Buka {$open} – {$close}";
    }
}
