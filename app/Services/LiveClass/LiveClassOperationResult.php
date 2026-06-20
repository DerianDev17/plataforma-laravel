<?php

namespace App\Services\LiveClass;

class LiveClassOperationResult
{
    private $successful;
    private $message;
    private $externalParticipantId;
    private $accessUrl;
    private $raw;

    private function __construct(
        bool $successful,
        string $message,
        ?string $externalParticipantId = null,
        ?string $accessUrl = null,
        array $raw = []
    ) {
        $this->successful = $successful;
        $this->message = $message;
        $this->externalParticipantId = $externalParticipantId;
        $this->accessUrl = $accessUrl;
        $this->raw = $raw;
    }

    public static function success(
        string $message,
        ?string $externalParticipantId = null,
        ?string $accessUrl = null,
        array $raw = []
    ): self {
        return new self(true, $message, $externalParticipantId, $accessUrl, $raw);
    }

    public static function failure(string $message, array $raw = []): self
    {
        return new self(false, $message, null, null, $raw);
    }

    public function successful(): bool
    {
        return $this->successful;
    }

    public function failed(): bool
    {
        return ! $this->successful;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function externalParticipantId(): ?string
    {
        return $this->externalParticipantId;
    }

    public function accessUrl(): ?string
    {
        return $this->accessUrl;
    }

    public function raw(): array
    {
        return $this->raw;
    }
}
