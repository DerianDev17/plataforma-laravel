<?php

namespace App\Imports;

use App\Models\SenecytCarrer;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Maatwebsite\Excel\Concerns\WithStartRow;

class OfertaImport implements ToCollection, WithStartRow
{
    const EMPTYHEADER1 = 0;
    const institucion = 1;
    const carrera = 3;
    const campus = 4;
    const provincia = 5;
    const modalidad = 6;
    const jornada = 7;
    const puntaje_referencial = 8;
    // const website = 7;
    const tipo_institucion = 2;
    

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            SenecytCarrer::create([
                'institucion' =>             $row[self::institucion],
                'carrera' =>                 $row[self::carrera],
                'campus' =>                  $row[self::campus],
                'provincia' =>               $row[self::provincia],
                'modalidad' =>               $row[self::modalidad],
                'jornada' =>                 $row[self::jornada],
                'puntaje_referencial' =>     $row[self::puntaje_referencial],
                // 'website' =>                 $row[self::website],
                'tipo_institucion' =>        $row[self::tipo_institucion],
            ]);
        }
    }

    public function startRow(): int
    {
        return 2;
    }
}
