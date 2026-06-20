<?php

namespace App\Http\Livewire\Meetings;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\CourseSession;
use App\Models\StudentGroup;
use App\Models\User;
use Livewire\Component;
use App\Traits\ZoomJWT;
use App\Utils\Horarios;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class Show extends Component
{
    use ZoomJWT;

    public $meetings;
    public $user;
    public $tipo_zoom;

    public $horario;
    public $zoom_link;
    public $show_alert = false;
    public $scheduleGroups = [];
    public $studentGroupName;
    public $studentGroupCode;
    public $currentScheduleDay;

    public function check_payment($student)
    {
        return true;
    }

    public $today_sessions;

    // retorna una array [['hora1', 'materia1'], ['hora2', 'materia2'],...]
    public function getTodaySessions($horario = null)
    {
        if ($horario === null) {
            $user = auth()->user();
            $horario = $this->get_horario_estudiante($user);
        }

        if (!$horario) return [];
        // dd($horario);
        $horario = $this->transposeData($horario);
        if (!$horario) return [];

        $today_sessions = [];

        $curr_day = Carbon::now();

        if ($curr_day->isMonday()) {
            for ($i = 0; $i < 5; $i++) {
                array_push($today_sessions, [$horario[0][$i], $horario[1][$i]]);
            }
        }
        if ($curr_day->isTuesday()) {
            for ($i = 0; $i < 5; $i++) {
                array_push($today_sessions, [$horario[0][$i], $horario[2][$i]]);
            }
        }
        if ($curr_day->isWednesday()) {
            for ($i = 0; $i < 5; $i++) {
                array_push($today_sessions, [$horario[0][$i], $horario[3][$i]]);
            }
        }
        if ($curr_day->isThursday()) {
            for ($i = 0; $i < 5; $i++) {
                array_push($today_sessions, [$horario[0][$i], $horario[4][$i]]);
            }
        }
        if ($curr_day->isFriday()) {
            for ($i = 0; $i < 5; $i++) {
                array_push($today_sessions, [$horario[0][$i], $horario[5][$i]]);
            }
        }
        if ($curr_day->isSaturday()) {
            for ($i = 0; $i < 5; $i++) {
                array_push($today_sessions, [$horario[6][$i], $horario[7][$i]]);
            }
        }

        return $today_sessions;
    }

    // transpone un array (filas a columnas)
    function transposeData($data)
    {
        $retData = array();

        foreach ($data as $row => $columns) {
            foreach ($columns as $row2 => $column2) {
                $retData[$row2][$row] = $column2;
            }
        }
        return $retData;
    }

    public function get_horario_estudiante(User $student)
    {
        return $student->horario();
    }


    public function get_zoom_link_estudiante(User $student)
    {
        if ($student->join_url == null) {
            return '';
        }
        return $student->join_url;
    }


    public function mount()
    {
        $user = auth()->user();
        $user->loadMissing('student_group');

        $this->user = $user;
        $this->studentGroupName = $user->student_group->name ?? null;
        $this->studentGroupCode = $user->student_group->code ?? null;
        $this->currentScheduleDay = (int) Carbon::now()->dayOfWeekIso;

        $this->zoom_link = $this->get_zoom_link_estudiante($user);

        $this->horario = $this->get_horario_estudiante($user);

        $this->today_sessions = $this->getTodaySessions($this->horario);
        $this->scheduleGroups = $this->buildScheduleGroups($user);

        // dd($this->today_sessions);
        
    }

    private function buildScheduleGroups(User $user): array
    {
        $user->loadMissing('student_group');

        if ($user->hasRole('student')) {
            $group = $user->student_group;

            if (!$group || !$group->code || $group->code === 'Z') {
                return [];
            }

            return [[
                'name' => $group->name,
                'code' => $group->code,
                'schedule' => $this->horario,
            ]];
        }

        return Cache::remember('meetings.schedule_groups.all', now()->addHours(6), function () {
            $horarios = new Horarios();

            return StudentGroup::valids()
                ->orderBy('id')
                ->get()
                ->map(function (StudentGroup $group) use ($horarios) {
                    return [
                        'name' => $group->name,
                        'code' => $group->code,
                        'schedule' => $horarios->get_horario($group->code),
                    ];
                })
                ->filter(function (array $group) {
                    return !empty($group['schedule']);
                })
                ->values()
                ->all();
        });
    }

    public function get_student_meetings($student)
    {
        $student_meetings = [];

        $path = 'users/me/meetings';

        // $response = $this->zoomGet($path);

        // $data = json_decode($response->body(), true);

        // $data['meetings'] = array_map(function (&$m) {
        //   // cambiar fecha a otro formato
        //   $m['start_at'] = $this->toUnixTimeStamp($m['start_time'], $m['timezone']);
        //   return $m;
        // }, $data['meetings']);

        // dd($data['meetings']);

        // foreach ($data['meetings'] as $meeting) {
        //   // var_dump($meeting['id']);
        //   // if ($meeting['id'] === 91961110615) {
        //   //   array_push($student_meetings, $meeting);
        //   // }
        // }

        $path = 'users/me/meetings';


        return $student_meetings;
    }

    function checkExistingAttendance($course_session_id, $user_id)
    {
        return Attendance::where('course_session_id', $course_session_id)
            ->where('user_id', $user_id)
            ->exists();
    }

    /**
     * recibe la $materia y $hora_clase como esta en el horario,
     * selecciona la sesion (tabla course_session) correspondiente
     * basado en los valores $materia y $hora_clase
     * (SELECT * FROM course_sessions WHERE time=$hora_clase AND subject=$materia AND date=? AND course_id=1),
     * !!!fijarse en el parelo del esudiante,
     * guarda en la tabla attendances (asistencias) el user_id y course_session_id en esa tabla
     */
    public function registAttendance($materia, $hora_clase)
    {

        $time_now = Carbon::now();

        $time_session = Carbon::now();
        $time_session->hour = explode(':', $hora_clase)[0];
        $time_session->minute = explode(':', $hora_clase)[1];
        $time_session->second = '00';

        // diferencia entre la hora de la sesion y la hora actual
        $diff = $time_now->diffInMinutes($time_session);

        //compara si la fecha actual es mayor q la fecha de la sesion
        $compare = $time_now->lessThan($time_session);

        //dd(($diff >= 15) || ($compare == true));
        // validar lapso de tiempo permitido para realizar el registro
        if ($diff >= 59 || $compare == true) {
            $this->show_alert = true;
            session()->flash(
                'error',
                'Solo puede registrar su asistencia hasta 59 minutos despues de iniciada la clase.'
            );
        } else {
            // permitido ingresar una asistencia
            $user = auth()->user();
            $user->loadMissing('student_group');
            $std_grp_id = $user->student_group_id;

            if (!$std_grp_id) {
                $this->show_alert = true;
                session()->flash(
                    'error',
                    'No tiene un paralelo asignado.'
                );
                return;
            }

            // dd($std_grp_id);
            // $course_id = $this->getCourseid($user);
            $date = Carbon::today()->toDateString();

            // obtener la sesion de la bdd
            $clase = CourseSession::where('date', $date)
                ->where('time', $hora_clase . ':00')
                ->where('student_groups_id', $std_grp_id)
                ->first();

            // $clase = CourseSession::where('subject', $materia)
            //     ->where('date', $date)
            //     ->where('time', $hora_clase . ':00')
            //     ->where('course_id', $course_id)
            //     ->first();

            if (!$clase) {
                $this->show_alert = true;
                session()->flash(
                    'error',
                    'No existe la sesion.'
                );
                return;
            }

            // crear un nuevo registro attendance
            if (!$this->checkExistingAttendance($clase->id, $user->id)) {
                $asistencia = new Attendance();
                $asistencia->course_session_id = $clase->id;
                $asistencia->user_id = $user->id;
                $asistencia->save();

                $this->show_alert = true;

                session()->flash(
                    'ok',
                    'Asistencia confirmada'
                );
            } else {
                $this->show_alert = true;
                session()->flash(
                    'ok',
                    'Ya ha registrado su asistencia previamente.'
                );
            }
            // return;
        }
    }
    //funcion de paralelos
    /*public function registParalelo($paraleloA, $paraleloB)
    {

        // permitido ingresar una asistencia
        $user = auth()->user();
        
        // crear un nuevo registro paralelo
        $paralelo = new Paralelo();
        $paralelo->id_user = $user->id;
        if($paraleloA=1 && $paraleloB=0){
            $paralelo->id_courses = $paraleloA;
        }else if($paraleloA=0 && $paraleloB=1){
            $paralelo->id_courses = $paraleloB;
        }else
        $paralelo->save();
        $this->show_alert = true;
        session()->flash(
            'ok',
            'Paralelo confirmada');
        
            // return;
    }*/

    public function closeAlert()
    {
        $this->show_alert = false;
    }

    public function getCourseid($user)
    {
        $exam_month_pre =  'pre_' . strtolower($user->exam_month);
        $curso = Course::where('code', $exam_month_pre)->first();
        return $curso->id;
    }

    public function getSid($user)
    {
        $exam_month_pre =  'pre_' . strtolower($user->exam_month);
        $curso = Course::where('code', $exam_month_pre)->first();
        return $curso->id;
    }

    public function asisitioReunion($datos_reunion)
    {
        $date = Carbon::today()->toDateString();
        $logged_user = auth()->user();
        $logged_user->loadMissing('student_group');
        $std_grp_id = $logged_user->student_group_id;

        if (!$std_grp_id) {
            return false;
        }

        return CourseSession::where('date', $date)
            ->where('time', $datos_reunion[0] . ':00')
            ->where('student_groups_id', $std_grp_id)
            ->whereHas('users', function ($q) use ($logged_user) {
                $q->where('user_id', $logged_user->id);
            })
            ->exists();
    }

    private function attendedTimesForToday(): array
    {
        $logged_user = auth()->user();
        $logged_user->loadMissing('student_group');
        $std_grp_id = $logged_user->student_group_id;

        if (!$std_grp_id || empty($this->today_sessions)) {
            return [];
        }

        $times = collect($this->today_sessions)
            ->map(function ($session) {
                return isset($session[0]) ? $session[0] . ':00' : null;
            })
            ->filter()
            ->unique()
            ->values();

        if ($times->isEmpty()) {
            return [];
        }

        return CourseSession::where('date', Carbon::today()->toDateString())
            ->where('student_groups_id', $std_grp_id)
            ->whereIn('time', $times)
            ->whereHas('users', function ($q) use ($logged_user) {
                $q->where('user_id', $logged_user->id);
            })
            ->pluck('time')
            ->flip()
            ->all();
    }

    public function render()
    {
        $attendedTimes = $this->attendedTimesForToday();

        foreach ((array) $this->today_sessions as $i => $ts) {
            // crear un nuevo elemento en el array con dato asistencia o ausencia
            $time = isset($ts[0]) ? $ts[0] . ':00' : null;
            $this->today_sessions[$i]['asistio'] = $time && array_key_exists($time, $attendedTimes);
        }
        // dd($this->today_sessions);
        return view('livewire.meetings.show');
    }
}
