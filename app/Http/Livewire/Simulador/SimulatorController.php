<?php

namespace App\Http\Livewire\Simulador;

use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Roles;
use Illuminate\Http\Response;
use Livewire\Component;

class SimulatorController extends Component
{

    public function render()
    {

        return view('livewire.simulator.simulator');
    }

    public function index()
    {
        $roles = Role::all();
        // dd($roles);
        return view('simulator.index', ['mis_roles' => $roles]);
    }

    
}
