<?php

namespace App\Enums;

enum WorkloadSpectrum: string
{
    case Rendah = 'Rendah';
    case Ideal = 'Ideal';
    case Tinggi = 'Tinggi';

    public static function fromScore(float $score): self
    {
        $highThreshold = (float) config('sdss.psw.thresholds.high', 0.70);
        $idealThreshold = (float) config('sdss.psw.thresholds.ideal', 0.40);

        if ($score >= $highThreshold) {
            return self::Tinggi;
        }

        if ($score >= $idealThreshold) {
            return self::Ideal;
        }

        return self::Rendah;
    }

    public function colorHex(): string
    {
        return match ($this) {
            self::Tinggi => '#DC2626',
            self::Ideal => '#16A34A',
            self::Rendah => '#2563EB',
        };
    }
}
