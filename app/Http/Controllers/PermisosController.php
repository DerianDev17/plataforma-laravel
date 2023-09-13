<?php

namespace App\Http\Controllers;

use App\Models\Ability;
use App\Models\Role;
use Illuminate\Http\Request;
use App\Models\Roles;
use Illuminate\Http\Response;

class PermisosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();

        $abilities = Ability::all();

        // dd($roles[1]->abilities);

        return view('permisos.index', compact('roles', 'abilities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::all();

        $abilities = Ability::all();

        return view('permisos.create', compact('roles', 'abilities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
       //crear rol vacio
       $rol = Role::find($request->rol);
        //   dd($request->rol);
       //crear ability vacia
       $ability = Ability::find($request->ability);
       //vincular las 2
       $rol->abilities()->save($ability);
        return redirect()->route('permisos.index')
            ->with('success', 'Product created successfully.');
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ability = Ability::find($id);
        return view('abilities.show', ['ability' => $ability]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $ability = Ability::find($id);
        return view('abilities.update', ['ability' => $ability]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $ability = Ability::find($id);
        $ability->name = $request->nombre;
        $ability->label = $request->label;
        $ability->save();
        return redirect()->route('abilities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find($id);
        $role->delete();
        return redirect()->route('roles.index');
    }
}
