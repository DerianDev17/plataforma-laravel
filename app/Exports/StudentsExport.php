<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class StudentsExport implements FromQuery, WithMapping, WithHeadings
{

    use Exportable;

    public function query()
    {
        return User::query();
    }

    public function map($user): array
    {
        if ($user->hasRole('student')) {
            //dd($user);
            return [
                $user->name,
                $user->last_name,
                $user->email,
                $user->cellphone,
                $user->fixedphone,
                $user->highschool,
                $user->especialty,
                $user->paralelo,
                $user->city,
                $user->status,
                $user->name_representante . ' ' . $user->last_name_representante,
                $user->cellphone_representante,
                $user->regimen,
                $user->exam_month,
                $user->cedula,
                $user->username,
                $user->email_verified_at ? 'verificado' : 'pendiente',
                $user->cedulaPadre,
                $user->cedulaMadre,
                $user->nombresPadre,
                $user->nombresMadre,
                $user->emailPadre,
                $user->emailMadre,
                $user->telefonoPadre,
                $user->telefonoMadre
            ];
        }
        return [];
    }

    public function headings(): array
    {
        return [
            'nombres',
            'apellidos',
            'email',
            'num_celular',
            'num_fijo',
            'colegio',
            'especialidad',
            'paralelo',
            'ciudad',
            'status',
            'nombre_representante',
            'celular_representante',
            'regimen',
            'fecha_examen',
            'cedula',
            'nombre_de_usuario',
            'correo_verificado',
            'cedulaPadre',
            'cedulaMadre',
            'nombresPadre',
            'nombresMadre',
            'emailPadre',
            'emailMadre',
            'telefonoPadre',
            'telefonoMadre'
        ];
    }
}
