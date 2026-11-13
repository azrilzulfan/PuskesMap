<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Pemerintah = 'pemerintah';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrator',
            self::Pemerintah => 'Dinas Kesehatan',
        };
    }
}
