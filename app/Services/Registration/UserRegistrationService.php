<?php

namespace App\Services\Registration;

use App\Models\User;
use App\Services\Audit\AuditLogService;
use App\Services\NotificationService;
use App\Services\Registration\Contracts\UserRegistrar;

class UserRegistrationService
{
    public function __construct(
        private UserRegistrar $registrar,
        private AuditLogService $audit,
        private NotificationService $notifications,
    ) {}

    public function register(array $data, ?User $actor = null): User
    {
        $user = $this->registrar->register($data);

        $this->audit->log('user.registered', $actor, [
            'user_id' => $user->id,
            'email' => $user->email,
            'channel' => 'self',
        ]);

        $this->notifications->sendRegistrationCredentials($user);

        return $user;
    }
}
