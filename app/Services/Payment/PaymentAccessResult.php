<?php

namespace App\Services\Payment;

class PaymentAccessResult
{
    private function __construct(
        private bool $allowed,
        private string $status,
        private string $label,
        private string $reason,
    ) {}

    public static function allow(string $status, string $label, string $reason = ''): self
    {
        return new self(true, $status, $label, $reason);
    }

    public static function deny(string $status, string $label, string $reason = ''): self
    {
        return new self(false, $status, $label, $reason);
    }

    public function allowed(): bool
    {
        return $this->allowed;
    }

    public function denied(): bool
    {
        return ! $this->allowed;
    }

    public function status(): string
    {
        return $this->status;
    }

    public function label(): string
    {
        return $this->label;
    }

    public function reason(): string
    {
        return $this->reason;
    }
}
