<?php

namespace App\Http\Controllers;

use App\Incentives\Rules\Goal;
use Illuminate\Http\Request;

class GoalsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $goals = Goal::orderBy('name')->paginate(10);
        return view('goals.index', compact('goals'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('goals.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        sleep(1);
        $this->validate(request(), [
          'name'     => 'required|min:3',
        ]);

        $goal = Goal::create(request()->all());
        $results            = [];
        $results['goal']    = $goal;
        $results['status']  = 'success';
        $results['message'] = __('Meta creada');

        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param Goal $goal
     * @return \Illuminate\Http\Response
     */
    public function show(Goal $goal)
    {
        $results           = [];
        $results['goal']   = $goal;
        $results['status'] = 'success';

        return $results;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Goal $goal
     * @return \Illuminate\Http\Response
     */
    public function edit(Goal $goal)
    {
        return view('goals.edit', compact('goal'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Goal $goal
     * @return \Illuminate\Http\Response
     */
    public function update(Goal $goal)
    {
        sleep(1);
        $this->validate(request(), [
          'name'     => 'required|min:3',
        ]);

        $goal->update(request()->all());

        $results            = [];
        $results['goal']    = $goal;
        $results['status']  = 'success';
        $results['message'] = __('Datos actualizados');

        return $results;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Goal $goal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Goal $goal)
    {
        //
    }
}
