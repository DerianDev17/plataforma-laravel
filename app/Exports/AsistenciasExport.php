<?php

namespace App\Exports;

use App\Models\User;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Support\Facades\DB;


class AsistenciasExport implements FromCollection, WithHeadings
{

    use Exportable;

    public function collection()
    {

        $asistencias = $this->queryAsistenciasSemanas(0);
        // dd(collect($asistencias));
        
        return collect($asistencias);
    }

    //$semana = numero de la semana 
    public function queryAsistenciasSemana($semana)
    {
        $start_date = new Carbon('2022-01-04');
        $from = date($start_date->addWeeks($semana)->toDateString());
        $to = date($start_date->endOfWeek()->toDateString());
        // dd($from, $to);

        return DB::table('users')
            ->join('attendances', 'users.id', '=', 'attendances.user_id')
            ->join('course_sessions', 'attendances.course_session_id', '=', 'course_sessions.id')
            ->select('users.username', 'users.name', 'users.last_name', 'attendances.course_session_id', 'subject', 'date', 'time')
            ->whereBetween('date', [$from, $to])
            ->orderBy('last_name')
            ->orderBy('date')
            ->orderBy('time')
            ->get();
    }

    public function getColumnasMaterias($n_semana){
        $start_date = new Carbon('2022-01-04');
        $from = date($start_date->addWeeks($n_semana)->toDateString());
        $to = date($start_date->endOfWeek()->toDateString());
        $cols = "SUM(CASE
                        WHEN cs.subject = 'Matemática'
                            AND cs.`date` BETWEEN CAST('".$from."' AS DATE) AND CAST('".$to."' AS DATE)
                        THEN 1
                        ELSE 0
                    END) AS mate".strval($n_semana+1).",
                SUM(CASE
                        WHEN cs.subject = 'Lengua y Literatura'
                            AND cs.`date` BETWEEN CAST('".$from."' AS DATE) AND CAST('".$to."' AS DATE)
                        THEN 1
                        ELSE 0
                    END) AS lengua".strval($n_semana+1).",
                SUM(CASE
                        WHEN cs.subject = 'Ciencias Naturales'
                            AND cs.`date` BETWEEN CAST('".$from."' AS DATE) AND CAST('".$to."' AS DATE)
                        THEN 1
                        ELSE 0
                    END) AS naturales".strval($n_semana+1).",
                SUM(CASE
                        WHEN cs.subject = 'Ciencias Sociales'
                            AND cs.`date` BETWEEN CAST('".$from."' AS DATE) AND CAST('".$to."' AS DATE)
                        THEN 1
                        ELSE 0
                    END) AS sociales".strval($n_semana+1).",
                SUM(CASE
                        WHEN cs.subject = 'Orientación Vocacional'
                            AND cs.`date` BETWEEN CAST('".$from."' AS DATE) AND CAST('".$to."' AS DATE)
                        THEN 1
                        ELSE 0
                    END) AS orientacion".strval($n_semana+1)."";
        return $cols;
    }

    public function queryAsistenciasSemanas($semana)
    {
        $start_date = new Carbon('2021-05-03');
        $from = date($start_date->addWeeks($semana)->toDateString());
        $to = date($start_date->endOfWeek()->toDateString());
        // dd($from, $to);
        $cols = '';
        for($week=0; $week<3; $week++){
            $cols .= $this->getColumnasMaterias($week) . ",\n";
        }
        $cols .= $this->getColumnasMaterias(3);
        // dd($cols);
        $query = DB::select(DB::raw("
        SELECT
            u.username,
            u.name,
            u.last_name,
            ".$cols."

        FROM users u
        JOIN role_user ru ON ru.user_id = u.id
        LEFT JOIN attendances a2 ON u.id=a2.user_id
        LEFT JOIN course_sessions cs ON cs.id = a2.course_session_id 
        WHERE ru.role_id = 2
        AND u.status = 1
        AND deleted_at IS NULL
        GROUP BY username 
        ORDER BY username 
        "));
        return $query;
    }

    public function getAttendancesbyDate($fecha, $asistencias)
    {
        return  array_filter($asistencias, function ($asist) use ($fecha) {
            return $asist->date == $fecha;
        });
    }

    public function getAttendancesbyWeek($fecha, $asistencias)
    {
        $weekStartDate = $fecha->startOfWeek();
        $weekEndDate = $fecha->endOfWeek();
        // dd($fecha, $weekStartDate, $weekEndDate);
        return  array_filter($asistencias, function ($asist) use ($fecha, $weekStartDate, $weekEndDate) {
            // dd($fecha, $asist);
            return $fecha->between($weekStartDate, $weekEndDate);
        });
    }

    public function collectionRespaldo()
    {
        $asistencias = DB::table('users')
            ->join('attendances', 'users.id', '=', 'attendances.user_id')
            ->join('course_sessions', 'attendances.course_session_id', '=', 'course_sessions.id')
            ->select('users.username', 'users.name', 'users.last_name', 'attendances.course_session_id', 'subject', 'date', 'time')
            // ->select(DB::raw('users.username, users.name, users.last_name, JSON_ARRAYAGG(subject) AS subjects'))
            // ->select(DB::raw('users.username, users.name, users.last_name, attendances.course_session_id, subject, date, time, JSON_ARRAYAGG(subject) AS subjects'))
            ->orderBy('last_name')
            ->get()
            ->take(10);
        // dd($asistencias);
        //1. Agrupar informacion por usuario
        $user_info = [];
        foreach ($asistencias as $asist) {
            $user_info[$asist->username] = [
                'subject' => $asist->subject,
                'date' => $asist->date,
                'time' => $asist->time,
            ];
        }
        // dd($user_info);
        return $asistencias;
    }


    // public function map($attendance): array
    // {
    //     return [
    //         $attendance->username,
    //         $attendance->name,
    //         $attendance->Matemática,
    //         $attendance->Ciencias_Sociales,
    //         $attendance->Orientación_Vocacional,
    //         $attendance->Ciencias_Naturales,
    //         $attendance->Lengua_y_Literatura,
    //     ];
    // }

    public function headings(): array
    {
        $headers = [
            'Usuario',
            'Nombres',
            'Apellidos',
        ];
        for ($i = 0; $i < 4; $i++) {
            $number = strval($i + 1);
            $tmp = [
                'Matemática semana ' . $number,
                'Lengua y Literatura semana ' . $number,
                'Ciencias Naturales semana ' . $number,
                'Ciencias Sociales semana ' . $number,
                'Orientación Vocacional semana ' . $number,
            ];
            foreach ($tmp as $t) {
                array_push($headers, $t);
            }
        }
        // dd($headers);
        return $headers;
    }
}
