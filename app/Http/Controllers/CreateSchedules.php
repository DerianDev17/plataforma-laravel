<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

use App\Utils\Horarios;


class CreateSchedules extends Controller
{
    private $horario;

    function __construct()
    {
        $this->horario = new Horarios;
    }

    public function __invoke(Request $request)
    {
        set_time_limit(0);

        $horario = $this->transformHorario($this->horario->get_horario('B'));
        $start_date = Carbon::create(2021, 9, 6); // debe ser el lunes en el que inicia el horario
        $this->createSchedule(2, $horario, $start_date, 20);

        return ':)';
    }

    public function transformHorario($horario)
    {
        /*
        Se busca crear un array de la sig. manera
        [
            [subject_id => 1, subject_title => 'subname...', 'session_time' => '19:00', 'day' => 1],
            ...
        ]
         */
        $transformed = [];
        foreach ($horario as $elem) {
            $elemcpy = $elem;   // duplico array para quitar las horas y dejar solo los nombres
            unset($elemcpy[0]);
            unset($elemcpy[6]);
            $time_inweek = $elem[0];
            $time_weekend = $elem[6];
            $day_number = 1;
            foreach ($elemcpy as $index => $subject_title) {
                array_push($transformed, [
                    'subject_id' => $this->getIdBySubjectTitle($subject_title),
                    'subject_title' => $subject_title,
                    'session_time' => $index <= 5 ? $time_inweek : $time_weekend,
                    'day' => $day_number,
                ]);
                $day_number++;
            }
        }
        return $transformed;
    }

    public function getIdBySubjectTitle($subject_title) {
        return Subject::where('name', 'like', '%' . $subject_title . '%')->first()->id;
    }

    public function getNextWeekDayByOffset(Carbon $day, $offset) {
        return $day->copy()->addDays(7 * ($offset))->toDateString();
    }

    public function insertSessionsRepetitions($session_data, $weeks_repeat, Carbon $start_date, $student_group_id) {
        
        $start_date_cpy = $start_date->copy();
        for ($i = 0; $i < $weeks_repeat; $i++) {
            DB::table('course_sessions')->insert([
                'course_id' =>          $student_group_id,
                'date' =>               $this->getNextWeekDayByOffset($start_date_cpy, $i),
                'time' =>               $session_data['session_time'] . ':00',
                'subject' =>            $session_data['subject_title'],
                'module_number' =>      0,
                'subject_id' =>         $session_data['subject_id'],
                'student_groups_id' =>  $student_group_id,
            ]);
        }
    }

    // week repeats es el numero de veces que quieren que se repita la sesion
    public function createSchedule($student_group_id, $horario, Carbon $start_date, $weeks_repeat)
    {
        $first_monday = $start_date;
        $start_days = [
            1 => $first_monday,
            2 => $start_date->copy()->addDays(1),       // martes
            3 => $start_date->copy()->addDays(2),       // miercoles
            4 => $start_date->copy()->addDays(3),       // jueves
            5 => $start_date->copy()->addDays(4),       // viernes
            6 => $start_date->copy()->addDays(5),       // sabado
        ];

        foreach ($horario as $c_session) {
            $this->insertSessionsRepetitions($c_session, $weeks_repeat, $start_days[$c_session['day']], $student_group_id);
        }
    }
}
