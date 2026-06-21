<?php

namespace App\Services\Audit\Contracts;

interface AuditLogger
{
    public function write(string $action, ?int $actorId, array $context): void;
}
