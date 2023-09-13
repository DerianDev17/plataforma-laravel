<?php

namespace App\Http\Livewire\Calculadora;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Roles;
use Illuminate\Http\Response;
use Livewire\Component;

class CalculadoraController extends Component
{

    public function render()
    {

        return view('livewire.calculadora.calculador');
    }

    public function index()
    {
        $roles = Role::all();
        // dd($roles);
        return view('calculadora.index', ['mis_roles' => $roles]);
    }

    
}
