<?php

namespace App\Http\Livewire\MaterialDigital;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{

    public $user;


    public function mount()
    {
        $this->user = auth()->user();
        // dd($this->user);

    }

    public function render()
    {
        return view('livewire.material-digital.show');
    }
}
