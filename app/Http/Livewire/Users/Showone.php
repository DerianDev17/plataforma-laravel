<?php

namespace App\Http\Livewire\Users;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Livewire\Component;
use App\Models\User;

class Showone extends Component
{

    public $user;

    public function mount(Request $peticion) {
        
        // $this->users=User::all();
        $this->user = User::find($peticion->id);
        //    dd($this->user);
    }

    public function render()
    {
        return view('livewire.users.showone');
    }
}
