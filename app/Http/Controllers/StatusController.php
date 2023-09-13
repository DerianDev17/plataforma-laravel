<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function store(Request $request)
    {
     $st = User::all();
      foreach ($st as $s) {
        $s->status = false;
        $s->save();  
      }
    }
}
