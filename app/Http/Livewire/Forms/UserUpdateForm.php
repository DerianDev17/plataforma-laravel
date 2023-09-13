<?php

namespace App\Http\Livewire\Forms;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Utils\ValidarIdentificacion;

class UserUpdateForm extends Component
{
    public
        $name,
        $last_name,
        $cedula;

    public function store()
    {
        $cedula = $this->cedula;
        // dd($cedula[0]);
        $ci_validator = new ValidarIdentificacion();
        if( is_numeric($cedula[0]) ) {
            $this->validate([
                'name' => 'required',
                'last_name' => 'required',
                'cedula' => ['required', 'string', function($attr, $value, $fail) use ($ci_validator) {
                    // dd($value);
                    if ($ci_validator->validarCedula($value)) return true;
                    $fail('Por favor, introduzca un valor de cédula correcto.');
                }],
            ]);
        }elseif ( !is_numeric($cedula[0]) ){
            $this->validate([
                'name' => 'required',
                'last_name' => 'required',
                'cedula' => ['required', 'string', function($attr, $value, $fail) use ($ci_validator) {
                    // dd($value);
                    if ($ci_validator->validarPasaporte($value) && $value[0] == 'p') return true;
                    $fail('Por favor, introduzca un valor de pasaporte correcto.');
                }],
            ]);
        }
        $user_id = Auth::id();

            $student = User::find($user_id);
            $student->name = $this->name;
            $student->last_name = $this->last_name;
            $student->cedula = $this->cedula;
        
            $student->save();

            return redirect()->to('/dashboard');
    }


    public function mount()
    {
        $user_id = Auth::id();
        $student = User::findOrFail($user_id);

        $this->name = $student->name;
        $this->last_name = $student->last_name;
        $this->cedula = $student->cedula;
    }

    public function render()
    {
        return view('livewire.forms.user-update-form');
    }
}
