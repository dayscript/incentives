<?php

namespace App\Http\Controllers;

use App\Incentives\Compute\Vars;
use Illuminate\Http\Request;

class VarsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
      $vars = Vars::orderBy('name')->paginate(10);
      return view('vars.index', compact('vars'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('vars.create');

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


      $var = Vars::create(request()->all());
      $results            = [];
      $results['vars']    = $var;
      $results['status']  = 'success';
      $results['message'] = __('Variable creada');

      return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Incentives\Compute\Vars  $vars
     * @return \Illuminate\Http\Response
     */
    public function show(Vars $var)
    {
      $results           = [];
      $results['vars']   = $var;
      $results['status'] = 'success';

      return $results;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Incentives\Compute\Vars  $vars
     * @return \Illuminate\Http\Response
     */
    public function edit(Vars $var)
    {
      return view('vars.edit', compact('var'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Incentives\Compute\Vars  $vars
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vars $var)
    {
      sleep(1);
      $this->validate(request(), [
          'name'     => 'required|min:3',
      ]);
      $var->update(request()->all());

      $results            = [];
      $results['rule']    = $var;
      $results['status']  = 'success';
      $results['message'] = __('Datos actualizados');

      return $results;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Incentives\Compute\Vars  $vars
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vars $var)
    {
        $var->delete();
        $results            = [];
        $results['status']  = 'success';
        $results['message'] = __('Datos eliminados');
        return $results;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apilist()
    {
        $results            = [];
        $vars            = Vars::orderBy('name')->get();
        $results['variables'] = $vars;

        return $results;
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function devel($id)
    {
        $results = Vars::find($id);
        $vars = $results->vars_one;
        dd($vars);
    }
}
