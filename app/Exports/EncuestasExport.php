<?php

namespace App\Exports;

use App\Models\Encuesta;
use App\Models\Subject;
use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EncuestasExport implements FromQuery, WithMapping, WithHeadings
{

    use Exportable;

    public function query()
    {
        return Encuesta::query();
    }

    public function map($encuesta): array
    {
        $user = User::find($encuesta->user_id);
        $subject_name = '';
        // dd($encuesta->subject_id);
        if(!isset($encuesta->subject_id)){
            $subject_name = 'sin datos';
        } else{
            $subject_name = Subject::find($encuesta->subject_id)->name;
        }
        return [
            $user->email ?? 'null',
            // Solo en esta columna se va a guardar la respuesta
            $encuesta->score_mate,
            // $encuesta->score_lengua,
            // $encuesta->score_socila,
            // $encuesta->score_ciencias,
            // $encuesta->score_voca,
            $encuesta->frecuencia,
            $encuesta->atencion,
            $encuesta->satisfaccion,
            $encuesta->recomendacion,
            $subject_name,
        ];
    }

    public function headings(): array
    {
        return [
            'Usuario',
            'Calificación Materia',
            // 'Lenguaje',
            // 'Sociales',
            // 'Naturales',
            // 'Orientacion',
            'Frecuencia',
            'Atención',
            'Calificación General',
            'Recomendación',
            'Materia'
        ];
    }
}
