<?php

namespace App\Services\Attendance;

use App\Models\Attendance;

class AttendanceResult
{
    private function __construct(
        private bool $allowed,
        private ?Attendance $attendance,
        private bool $alreadyRegistered,
        private string $message,
        private ?string $reason,
    ) {}

    public static function created(Attendance $attendance, string $message = 'Asistencia registrada correctamente.'): self
    {
        return new self(true, $attendance, false, $message, null);
    }

    public static function alreadyExists(Attendance $attendance, string $message = 'Ya registraste asistencia para esta clase.'): self
    {
        return new self(true, $attendance, true, $message, null);
    }

    public static function rejected(string $message, ?string $reason = null): self
    {
        return new self(false, null, false, $message, $reason);
    }

    public function allowed(): bool
    {
        return $this->allowed;
    }

    public function denied(): bool
    {
        return ! $this->allowed;
    }

    public function attendance(): ?Attendance
    {
        return $this->attendance;
    }

    public function alreadyRegistered(): bool
    {
        return $this->alreadyRegistered;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function reason(): ?string
    {
        return $this->reason;
    }
}
