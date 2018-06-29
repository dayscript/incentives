<?php

namespace App\Http\Controllers;

use App\Indicator;
use Illuminate\Http\Request;

class IndicatorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $indicator = Indicator::orderBy('name')->paginate(10);
        return view('indicators.index', compact('indicator'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apilist()
    {
        $results            = [];
        $indicators            = Indicator::orderBy('name')->get();
        $results['indicators'] = $indicators;

        return $results;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('indicators.create');
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

        $indicators = Indicator::create(request()->all());
        $results               = [];
        $results['indicators']  = $indicators;
        $results['status']  = 'success';
        $results['message'] = __('Indicador creado');

        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function show(Indicator $indicator)
    {
        $results           = [];
        $results['indicator']   = $indicator;
        $results['status'] = 'success';
        $results['message'] = __('Indicador agregado');
        return $results;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function edit(Indicator $indicator)
    {
        return view('indicators.edit', compact('indicator'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function update(Indicator $indicator)
    {
        sleep(1);
    
        $this->validate(request(), [
            'name'     => 'required|min:3',
        ]);
        $indicator->update(request()->all());

        $results            = [];
        $results['indicator']    = $indicator;
        $results['status']  = 'success';
        $results['message'] = __('Datos actualizados');

        return $results;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Indicator  $indicator
     * @return \Illuminate\Http\Response
     */
    public function destroy(Indicator $indicator)
    {
        $results = [];
        $indicator->delete();
        $results['status']  = 'success';
        $results['message'] = __('Indicador eliminado');

        return $results;
    }
}
