<?php

namespace App\Enums;

enum MautScenario: string
{
    case Darurat = 'darurat';
    case Reguler = 'reguler';
    case Pemda = 'pemda';

    public function label(): string
    {
        return match ($this) {
            self::Darurat => 'Kedaruratan',
            self::Reguler => 'Layanan Reguler',
            self::Pemda => 'Evaluasi Pemda',
        };
    }

    /**
     * @return array{distance: float, igd: float, hours: float, nakes: float, crowd: float}
     */
    public function weights(): array
    {
        $weights = config("sdss.maut.weights.{$this->value}");

        return [
            'distance' => (float) ($weights['distance'] ?? 0.0),
            'igd' => (float) ($weights['igd'] ?? 0.0),
            'hours' => (float) ($weights['hours'] ?? 0.0),
            'nakes' => (float) ($weights['nakes'] ?? 0.0),
            'crowd' => (float) ($weights['crowd'] ?? 0.0),
        ];
    }
}
