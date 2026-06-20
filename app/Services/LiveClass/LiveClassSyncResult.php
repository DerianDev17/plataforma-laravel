<?php

namespace App\Services\LiveClass;

class LiveClassSyncResult
{
    private $updated;
    private $failed;
    private $message;
    private $errors;

    public function __construct(int $updated, int $failed, string $message, array $errors = [])
    {
        $this->updated = $updated;
        $this->failed = $failed;
        $this->message = $message;
        $this->errors = $errors;
    }

    public static function completed(int $updated, string $message, array $errors = []): self
    {
        return new self($updated, count($errors), $message, $errors);
    }

    public function updated(): int
    {
        return $this->updated;
    }

    public function failed(): int
    {
        return $this->failed;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return ! empty($this->errors);
    }
}
