<?php

namespace App\Http\Livewire\Informacion;

use Illuminate\Http\Request;
use App\Models\Roles;
use Illuminate\Http\Response;
use Livewire\Component;

class Show extends Component
{
    public function render()
    {
        return view('livewire.informacion.show');
    }

}
