<?php

namespace App\Http\Livewire;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Utils\ValidarIdentificacion;


class ContratoDigital extends Component
{
    
    public
        $estudiante,
        $representante,
        $cedulap,
        $correoe,
        $cedulam,
        $emailfirma,
        $correom,
        $celularp,
        $celularm,
        $name_est,
        $firma;
    public function store()
    {
        $cedulap = $this->cedulap;
        $emailfirma = $this->emailfirma;
        $firma = $this->firma;
        // dd($cedula[0]);
        $ci_validator = new ValidarIdentificacion();

        if ($cedulap === '') {
            $this->validate([
                'estudiante' => 'required',
                'representante' => 'required',                
                'firma' => 'required',
                'emailfirma' => 'required',
                'cedulap' => ['required', 'string', function ($attr, $value, $fail) use ($ci_validator) {
                    // dd($value);
                    if ($ci_validator->validarCedula($value)) return true;
                    $fail('Por favor, introduzca un valor de cédula correcto.');
                }],
            ]);
            return;
        }

        if ($cedulap != '' && is_numeric($cedulap[0])) {
            $this->validate([
                'estudiante' => 'required',
                'representante' => 'required',
                'firma' => 'required',
                'emailfirma' => 'required',

                'cedulap' => ['required', 'string', function ($attr, $value, $fail) use ($ci_validator) {
                    // dd($value);
                    if ($ci_validator->validarCedula($value)) return true;
                    $fail('Por favor, introduzca un valor de cédula correcto.');
                }],
            ]);
        } elseif ($cedulap != '' && !is_numeric($cedulap[0])) {
            $this->validate([
                'estudiante' => 'required',
                'representante' => 'required',
                'firma' => 'required',
                'emailfirma' => 'required',

                'cedulap' => ['required', 'string', function ($attr, $value, $fail) use ($ci_validator) {
                    // dd($value);
                    if ($ci_validator->validarPasaporte($value) && $value[0] == 'p') return true;
                    $fail('Por favor, introduzca un valor de pasaporte correcto.');
                }],
            ]);
        }
        if ($emailfirma === ''){
            $this->validate([
                'estudiante' => 'required',
                'representante' => 'required',
                'firma' => 'required',
                'emailfirma' => ['required', 'string', function ($attr, $value, $fail) use ($ci_validator) {
                    // dd($value);
                    if ($ci_validator->validaremail($value) && $value[0] == 'p') return true;
                    $fail('Por favor, introduzca email valido');
                }],
            ]);
        }

    /*  if ($firma === '') {
            $this->validate([
                'firma' => 'required'                
            ]);
        } */
        
      /*  if ($emailfirma === '') {
            $this->validate([
                'emailfirma' => 'required'
            ]);
        } */

        $user_id = Auth::id();
        $student = User::find($user_id);
        //$student->name = $this->name;
        $student->nombreEstudiante = $this->estudiante;
        //$student->nombresMadre = $this->last_name;
        $student->nombresPadre = $this->representante;
        $student->cedulaPadre = $this->cedulap;
        //$student->email = $this->correoe;
        $student->emailFirma = $this->emailfirma;
        $student->firma = $this->firma;


        //$student->cedulaPadre = $this->cedulap;
        //$student->nombreestudiante = $this->name;
        /* $student->cedulaMadre = $this->cedulam;
        $student->emailPadre = $this->correop;
        $student->emailMadre = $this->correom;
        $student->telefonoPadre = $this->celularp;
        $student->telefonoMadre = $this->celularm; */
        
        $student->save();

        return redirect()->to('/dashboard');
    }
    public function mount()
    {
        $user_id = Auth::id();
        $student = User::findOrFail($user_id);

        //$this->name = $student->name;
        $this->estudiante = $student->nombreestudiante;
        
        $this->representante = $student->nombresPadre;
        //$this->cedulap = $student->cedulaPadre;
        $this->cedulap = $student->cedulaPadre;
        $this->email = $student->emailfirma;

        $this->firma = $student->firma;

        //$this->cedulam = $student->cedulaMadre;
        //$this->correop = $student->emailPadre;
        //$this->correom = $student->emailMadre;
        //$this->celularp = $student->telefonoPadre;
        //$this->celularm = $student->telefonMadre;
        //$this->correom = $student->emailMadre;
        //$this->name_est = $student->nombreestudiante;
    }
    public function render()
    {
        return view('livewire.contrato-digital');
    }
}
