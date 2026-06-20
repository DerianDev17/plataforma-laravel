<?php

namespace App\View\Components;

use App\Models\User;
use App\Utils\Horarios;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\Component;

class AppLayout extends Component
{

    public $today_sessions;

    // retorna una array [['hora1', 'materia1'], ['hora2', 'materia2'],...]
    public function getTodaySessions()
    {
        $user = auth()->user();

        if (!$user) return [];

        $cacheKey = 'layout.today_sessions.' . $user->id . '.' . ($user->student_group_id ?: 'none') . '.' . Carbon::today()->toDateString();

        return Cache::remember($cacheKey, now()->addMinutes(10), function () use ($user) {
            return $this->buildTodaySessions($user);
        });
    }

    private function buildTodaySessions(User $user): array
    {
        $horario = $this->get_horario_estudiante($user);

        if (!$horario) return [];
        // dd($horario);
        $horario = $this->transposeData($horario);

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

        // dd($today_sessions);
        return $today_sessions;
    }

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

    public function render()
    {
        $this->today_sessions = $this->getTodaySessions();
        return view('layouts.admin', [
            'today_sessions' => $this->today_sessions,
        ]);
    }
}
