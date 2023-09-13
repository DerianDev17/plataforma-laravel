<?php

namespace App\Imports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithStartRow;

class StudentsImport implements ToCollection, WithStartRow
{
    private $rows = 0;
    private $failed_updates = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {

            ++$this->rows;

            $user = User::where('email', '=', $row[7])
                ->first();

            // revisar si el usuario existe
            if ($user !== null) {
                $user->status = $row[15];
                $user->payment_day = $row[14];
                $user->fecha_examen = trim($row[13]);
                $user->exam_month = trim($row[13]);
                $user->save();
            } else {
                array_push($this->failed_updates, $row[7] . ' - ' . $row[15]);
            }
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

    public function getFailedUpdates(): array
    {
        return $this->failed_updates;
    }
}
