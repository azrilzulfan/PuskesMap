<?php

namespace App\Enums;

enum AuditAction: string
{
    case Insert = 'INSERT';
    case Update = 'UPDATE';
    case Delete = 'DELETE';
    case Deactivate = 'DEACTIVATE';
}
