<?php

namespace App\Services\Audit\Loggers;

use App\Models\AuditLog;
use App\Services\Audit\Contracts\AuditLogger;

class DatabaseAuditLogger implements AuditLogger
{
    public function write(string $action, ?int $actorId, array $context): void
    {
        AuditLog::create([
            'action' => $action,
            'actor_id' => $actorId,
            'context' => $context,
        ]);
    }
}
