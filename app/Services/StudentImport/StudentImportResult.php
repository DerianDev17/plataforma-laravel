<?php

namespace App\Services\StudentImport;

class StudentImportResult
{
    private function __construct(
        private int $created,
        private int $updated,
        private int $deleted,
        private array $failedEmails,
        private array $errors,
    ) {}

    public static function make(int $created, int $updated, int $deleted, array $failedEmails = [], array $errors = []): self
    {
        return new self($created, $updated, $deleted, $failedEmails, $errors);
    }

    public function created(): int
    {
        return $this->created;
    }

    public function updated(): int
    {
        return $this->updated;
    }

    public function deleted(): int
    {
        return $this->deleted;
    }

    public function failedEmails(): array
    {
        return $this->failedEmails;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function hasFailures(): bool
    {
        return ! empty($this->failedEmails) || ! empty($this->errors);
    }

    public function totals(): array
    {
        return [
            'created' => $this->created,
            'updated' => $this->updated,
            'deleted' => $this->deleted,
        ];
    }
}
