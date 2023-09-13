<?php

namespace App\Http\Controllers\CGrabadas;

use App\Http\Controllers\Controller;
use App\Models\User;

class ClasesGrabController extends Controller
{
    public function show()
    {
       $user= User::find(1);

       return view("clases-grabadas.show", compact('user'));
    }
}
