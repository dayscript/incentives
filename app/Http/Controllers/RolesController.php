<?php

namespace App\Http\Controllers;

use App\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RolesController extends Controller
{
/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role = Role::orderBy('name')->paginate(10);
        return view('roles.index', compact('role'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apilist()
    {
        $results            = [];
        $roles            = Role::orderBy('name')->get();
        $results['roles'] = $roles;

        return $results;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        sleep(1);
        $this->validate(request(), [
            'name'     => 'required|min:3',
        ]);

        $role = Role::create(request()->all());
        $results            = [];
        $results['role']     = $role;
        $results['status']  = 'success';
        $results['message'] = __('Rol creado');

        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $results           = [];
        $results['role']   = $role;
        $results['status'] = 'success';
		$results['message'] = __('Rol agregado');
        return $results;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        sleep(1);
    
        $this->validate(request(), [
            'name'     => 'required|min:3',
        ]);
        $role->update(request()->all());

        $results            = [];
        $results['role']    = $role;
        $results['status']  = 'success';
        $results['message'] = __('Datos actualizados');

        return $results;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Role  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $results = [];
        $role->delete();
        $results['status']  = 'success';
        $results['message'] = __('Rol eliminado');

        return $results;
    }
}
