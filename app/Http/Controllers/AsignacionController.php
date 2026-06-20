<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;

class AsignacionController extends Controller
{
    public function index()
    {
        return view('asignacion.index', [
            'users' => User::all(),
            'roles' => Role::all(),
        ]);
    }

    public function create()
    {
        return view('asignacion.create', [
            'users' => User::all(),
            'roles' => Role::all(),
        ]);
    }

    public function store(Request $request)
    {
        $user = User::find($request->user);
        $role = Role::find($request->rol);

        if ($user && $role) {
            $user->roles()->attach($role);
        }

        return redirect()->route('permisos.index')
            ->with('success', 'Permiso asignado correctamente.');
    }
}
