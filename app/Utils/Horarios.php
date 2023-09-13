<?php

namespace App\Utils;

use Illuminate\Support\Facades\Log;
use App\Models\Subject;

class Horarios
{
    public $subjects;

    public function __construct()
    {
        $this->subjects = Subject::all();
    }

    public function subj_name($code)
    {
        return $this->subjects->firstWhere('code', $code)->name ?? 'no data';
    }

    public function get_horario($paralelo)
    {
        switch ($paralelo) {
            case 'A':
                return $this->get_horario_a();
                break;
            case 'B':
                return $this->get_horario_b();
                break;
            case 'C':
                return $this->get_horario_c();
                break;
            case 'D':
                return $this->get_horario_d();
                break;
            default:
                return [];
        }
    }

    // A
    public function get_horario_a()
    {
        $orie = $this->subj_name('ORIE'); //Orientacion Vocaciona
        $ranu = $this->subj_name('RANU'); //Razonamiento Númerico
        $ralo = $this->subj_name('RALO'); // Razonamiento Lógico
        $raes = $this->subj_name('RAES'); // Atención y Concentración
        $rave = $this->subj_name('RAVE'); // Razonamiento Verbal

        return [
            [
                '19:00',
                $ranu,
                $raes,
                $rave,
                $orie,
                $ralo,
                '13:00',
                $orie,
            ],
            [
                '18:00',
                $raes,
                $rave,
                $orie,
                $ralo,
                $ranu,
                '12:00',
                $ralo,
            ],
            [
                '17:00',
                $rave,
                $orie,
                $ralo,
                $ranu,
                $raes,
                '11:00',
                $raes,
            ],
            [
                '16:00',
                $orie,
                $ralo,
                $ranu,
                $raes,
                $rave,
                '10:00',
                $rave,
            ],
            [
                '15:00',
                $ralo,
                $ranu,
                $raes,
                $rave,
                $orie,
                '09:00',
                $ranu,
            ],
        ];
    }

    // B
    public function get_horario_b()
    {
        $orie = $this->subj_name('ORIE');
        $ranu = $this->subj_name('RANU');
        $ralo = $this->subj_name('RALO');
        $raes = $this->subj_name('RAES');
        $rave = $this->subj_name('RAVE');

        return [
            [
                '19:00',
                $ralo,
                $ranu,
                $raes,
                $rave,
                $orie,
                '13:00',
                $ranu,
            ],
            [
                '18:00',
                $ranu,
                $raes,
                $rave,
                $orie,
                $ralo,
                '12:00',
                $rave,
            ],
            [
                '17:00',
                $raes,
                $rave,
                $orie,
                $ralo,
                $ranu,
                '11:00',
                $ralo,
            ],
            [
                '16:00',
                $rave,
                $orie,
                $ralo,
                $ranu,
                $raes,
                '10:00',
                $raes,
            ],
            [
                '15:00',
                $orie,
                $ralo,
                $ranu,
                $raes,
                $rave,
                '09:00',
                $orie,
            ],
        ];
    }

    // C
    public function get_horario_c()
    {
        $orie = $this->subj_name('ORIE');
        $ranu = $this->subj_name('RANU');
        $ralo = $this->subj_name('RALO');
        $raes = $this->subj_name('RAES');
        $rave = $this->subj_name('RAVE');

        return [
            [
                '19:00',
                $rave,
                $orie,
                $ralo,
                $ranu,
                $raes,
                '13:00',
                $raes,
            ],
            [
                '18:00',
                $orie,
                $ralo,
                $ranu,
                $raes,
                $rave,
                '12:00',
                $rave,
            ],
            [
                '17:00',
                $ralo,
                $ranu,
                $raes,
                $rave,
                $orie,
                '11:00',
                $orie,
            ],
            [
                '16:00',
                $ranu,
                $raes,
                $rave,
                $orie,
                $ralo,
                '10:00',
                $ralo,
            ],
            [
                '15:00',
                $raes,
                $rave,
                $orie,
                $ralo,
                $ranu,
                '09:00',
                $ranu,
            ],
        ];
    }

    // D
    public function get_horario_d()
    {
        $orie = $this->subj_name('ORIE');
        $ranu = $this->subj_name('RANU');
        $ralo = $this->subj_name('RALO');
        $raes = $this->subj_name('RAES');
        $rave = $this->subj_name('RAVE');

        return [
            [
                '19:00',
                $orie,
                $ralo,
                $ranu,
                $raes,
                $rave,
                '13:00',
                $rave,
            ],
            [
                '18:00',
                $ralo,
                $ranu,
                $raes,
                $rave,
                $orie,
                '12:00',
                $orie,
            ],
            [
                '17:00',
                $ranu,
                $raes,
                $rave,
                $orie,
                $ralo,
                '11:00',
                $ralo,
            ],
            [
                '16:00',
                $raes,
                $rave,
                $orie,
                $ralo,
                $ranu,
                '10:00',
                $ranu,
            ],
            [
                '15:00',
                $rave,
                $orie,
                $ralo,
                $ranu,
                $raes,
                '09:00',
                $raes,
            ],
        ];
    }
}
