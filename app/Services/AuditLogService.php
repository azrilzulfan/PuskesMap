<?php

namespace App\Services;

use App\Enums\AuditAction;
use App\Models\AuditLog;
use App\Models\User;

class AuditLogService
{
    public function record(
        User $user,
        AuditAction $action,
        string $targetTable,
        int $targetId,
        string $description,
        ?string $ipAddress = null,
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $user->id,
            'aksi' => $action,
            'tabel_target' => $targetTable,
            'target_id' => $targetId,
            'deskripsi_perubahan' => $description,
            'ip_address' => $ipAddress,
            'created_at' => now(),
        ]);
    }
}
