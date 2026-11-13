<?php

namespace App\Enums;

enum CrowdStatus: string
{
    case Rendah = 'Rendah';
    case Sedang = 'Sedang';
    case Padat = 'Padat';

    public function toMapLabel(): string
    {
        return match ($this) {
            self::Rendah => 'Sepi',
            self::Sedang => 'Sedang',
            self::Padat => 'Ramai',
        };
    }

    public static function fromPercentage(float $percentage): self
    {
        $lowThreshold = (float) config('sdss.capacity.low_threshold', 60.0);
        $mediumThreshold = (float) config('sdss.capacity.medium_threshold', 85.0);

        if ($percentage < $lowThreshold) {
            return self::Rendah;
        }

        if ($percentage <= $mediumThreshold) {
            return self::Sedang;
        }

        return self::Padat;
    }
}
