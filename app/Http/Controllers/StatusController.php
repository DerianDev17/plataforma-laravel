<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function store(Request $request)
    {
        User::query()->update(['status' => true]);

        return back()->with('message', 'Cuentas desbloqueadas.');
    }
}
