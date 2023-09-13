<?php

namespace App\Http\Livewire\Asistencias;

use App\Models\CourseSession;
use App\Models\User;
use Livewire\Component;
use App\Traits\ZoomJWT;
use Illuminate\Support\Facades\Auth;

class Show extends Component
{

    public $asistencias;

    public $sessions;

    protected $listeners = ['clickGuardar' => 'guardarAsistencias'];


    public function ordenarDia($semana)
    {
        for ($i = 0; $i < 6; $i++) {
            $dia_ordenado = $this->obtenerSesionesDia($semana, $i);
            for ($j = 0; $j < 5; $j++) {
                $semana[0] = $dia_ordenado[$j];
            }
        }
    }

    // retorna un array con las sesiones correspondientes a un dia 0:L,1:M,2:Mx,3:J,4:V,5:S
    // para una semana específica $semana
    public function obtenerSesionesDia($semana, $dia)
    {
        // dd($semana);
        $sesiones_dia = [];
        array_push($sesiones_dia, $semana[0 + $dia]);
        array_push($sesiones_dia, $semana[6 + $dia]);
        array_push($sesiones_dia, $semana[12 + $dia]);
        array_push($sesiones_dia, $semana[18 + $dia]);
        array_push($sesiones_dia, $semana[24 + $dia]);



        // dd($sesiones_dia);
        return $sesiones_dia;
    }

    public function ordenarSemanaAlfab($semana)
    {
        $semana_cpy = $semana;
        for ($n_dia = 0; $n_dia < 6; $n_dia++) {
            $sesiones_dia = $this->obtenerSesionesDia($semana, $n_dia);
            // ordenar alfabeticamente
            usort($sesiones_dia, function ($item1, $item2) {
                return $item1['subject'] <=> $item2['subject'];
            });
            
            // asignar los datos recogidos a los indices correspondientes
            $semana_cpy[0 + $n_dia] = $sesiones_dia[0];
            $semana_cpy[6 + $n_dia] = $sesiones_dia[1];
            $semana_cpy[12 + $n_dia] = $sesiones_dia[2];
            $semana_cpy[18 + $n_dia] = $sesiones_dia[3];
            $semana_cpy[24 + $n_dia] = $sesiones_dia[4];
        }
        // dd($semana_cpy);
        return $semana_cpy;
    }

    // ordena un array $semana que contiene 30 clases como en el sig. ej.
    // LLLLLMMMMMIIIIIJJJJJVVVVVSSSSS -> LMIJVSLMIJVSLMIJVSLMIJVSLMIJVSLMIJVS
    public function ordenarSesionesSemana($semana)
    {
        // dd($semana);
        $semana_ordenada = [];
        $i2 = 0;
        $i3 = 0;

        // por cada uno de los 30 dias
        for ($i = 0; $i < 30; $i++) {
            array_push($semana_ordenada, $semana[($i % 6) + $i2 + $i3]);
            // echo $semana[($i%6) + $i2 + $i3];
            $i2 = $i2 + 4;
            if ($i2 > 22) {
                $i2 = 0;
                $i3 += 1;
            }
        }
        // ordenar alfabeticamente PENDIENTE: diferente orden
        $semana_ordenada = $this->ordenarSemanaAlfab($semana_ordenada);

        return $semana_ordenada;
    }

    public function obtenerSesionesTodo($sesiones)
    {
        $ses_ordenadas = [];
        for ($i = 0; $i < 20; $i++) {
            //1. Dividir en grupos
            $semana = $sesiones->slice($i * 30, 30);
            $ses_ordenadas = array_merge($ses_ordenadas, $this->ordenarSesionesSemana($semana->values()));
        }
        return $ses_ordenadas;
    }

    public function mount()
    {
        $tr_sessions = [];
        $all_sesiones = CourseSession::where('course_id', 1)->orderBy('date', 'ASC')->get();
        // natsort($all_sesiones);
        $all_sesiones = $this->obtenerSesionesTodo($all_sesiones);
        // dd($all_sesiones);
        $factor = 0;

        for ($i = 0; $i < 20; $i++) {
            $tmp = [];

            for ($j = 0; $j < 30; $j++) {
                // echo $i . $j . ' ' . ($factor+$j) . '<br>';
                array_push($tmp, $all_sesiones[$j + $factor]);
                $tr_sessions['semana' . strval($i + 1)] = $tmp;
            }
            $factor = $factor + 30;
        }
        $this->sessions = $tr_sessions;
        // dd($tr_sessions);
    }

    public function render()
    {
        $id = Auth::id();
        $user = User::find($id);
        $sessions = $user->course_sessions;
        // dd($sessions);
        return view('livewire.asistencias.show');
    }

    public function guardarAsistencias()
    {
        dd('fd');
    }
}
