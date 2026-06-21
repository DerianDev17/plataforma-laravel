<?php

namespace App\Imports;

use App\Services\StudentImport\StudentImportService;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;

class AccountsImport implements ToCollection, WithStartRow
{
    private StudentImportService $service;
    private int $rows = 0;

    public function __construct(?StudentImportService $service = null)
    {
        $this->service = $service ?? app(StudentImportService::class);
    }

    public function collection(Collection $rows)
    {
        set_time_limit(0);

        foreach ($rows as $row) {
            if (
                $row[7] == 'semilladigital@gmail.com'
                || $row[7] == 'paucarevelyn222@gmail.com'
                || $row[7] == 'admin@mail.com'
                || $row[7] == 'admin@mail.com'
                || $row[7] == 'johannacurillo.2003@gmail.com'
            ) {
                continue;
            }

            ++$this->rows;
            $this->service->createUser($row);
        }
    }

    public function startRow(): int
    {
        return 2;
    }

    public function getRowCount(): int
    {
        return $this->rows;
    }
}
