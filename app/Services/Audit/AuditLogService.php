<?php

namespace App\Services\Audit;

use App\Models\AuditLog;
use App\Models\User;
use App\Services\Audit\Contracts\AuditLogger;
use Illuminate\Database\Eloquent\Collection;

class AuditLogService
{
    public function __construct(private AuditLogger $logger) {}

    public function log(string $action, ?User $actor = null, array $context = []): void
    {
        $this->logger->write($action, $actor?->getKey(), $context);
    }

    public function recent(int $limit = 50): Collection
    {
        return AuditLog::with('actor')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    public function forAction(string $action, int $limit = 50): Collection
    {
        return AuditLog::with('actor')
            ->where('action', $action)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }
}
