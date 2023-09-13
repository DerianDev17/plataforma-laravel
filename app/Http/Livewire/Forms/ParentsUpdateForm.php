<?php

namespace App\Http\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Utils\ValidarIdentificacion;

class ParentsUpdateForm extends Component
{
    public
        $name,
        $last_name,
        $cedulap,
        $cedulam,
        $correop,
        $correom,
        $celularp,
        $celularm;

    public function store()
    {
        $cedulap = $this->cedulap;
        // dd($cedula[0]);
        $ci_validator = new ValidarIdentificacion();

        if ($cedulap === '') {
            $this->validate([
                'name' => 'required',
                'last_name' => 'required',
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
                'name' => 'required',
                'last_name' => 'required',
                'cedulap' => ['required', 'string', function ($attr, $value, $fail) use ($ci_validator) {
                    // dd($value);
                    if ($ci_validator->validarCedula($value)) return true;
                    $fail('Por favor, introduzca un valor de cédula correcto.');
                }],
            ]);
        } elseif ($cedulap != '' && !is_numeric($cedulap[0])) {
            $this->validate([
                'name' => 'required',
                'last_name' => 'required',
                'cedulap' => ['required', 'string', function ($attr, $value, $fail) use ($ci_validator) {
                    // dd($value);
                    if ($ci_validator->validarPasaporte($value) && $value[0] == 'p') return true;
                    $fail('Por favor, introduzca un valor de pasaporte correcto.');
                }],
            ]);
        }
        $user_id = Auth::id();

        $student = User::find($user_id);
        $student->nombresPadre = $this->name;
        $student->nombresMadre = $this->last_name;
        $student->cedulaPadre = $this->cedulap;
        $student->cedulaMadre = $this->cedulam;
        $student->emailPadre = $this->correop;
        $student->emailMadre = $this->correom;
        $student->telefonoPadre = $this->celularp;
        $student->telefonoMadre = $this->celularm;
        $student->save();

        return redirect()->to('/dashboard');
    }

    public function mount()
    {
        $user_id = Auth::id();
        $student = User::findOrFail($user_id);

        $this->name = $student->nombresPadre;
        $this->last_name = $student->nombresMadre;
        $this->cedulap = $student->cedulaPadre;
        $this->cedulam = $student->cedulaMadre;
        $this->correop = $student->emailPadre;
        $this->correom = $student->emailMadre;
        $this->celularp = $student->telefonoPadre;
        $this->celularm = $student->telefonMadre;
    }

    public function render()
    {
        return view('livewire.forms.parents-update-form');
    }
}
