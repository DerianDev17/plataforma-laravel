<?php

namespace App\View\Components;

use App\Models\User;
use App\Utils\Horarios;
use Carbon\Carbon;
use Illuminate\View\Component;

class Adminsa extends Component
{
    public function render()
    {
        return view('layouts.adminsa');
    }
}
