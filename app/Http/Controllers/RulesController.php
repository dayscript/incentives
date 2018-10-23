<?php

namespace App\Http\Controllers;

use App\Incentives\Rules\Rule;
use Illuminate\Http\Request;

class RulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = Rule::orderBy('name')->paginate(10);
        return view('rules.index', compact('rules'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('rules.create');
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

        $rule = Rule::create(request()->all());
        $results            = [];
        $results['rule']    = $rule;
        $results['status']  = 'success';
        $results['message'] = __('Regla creada');

        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Incentives\Rules\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function show(Rule $rule)
    {
        $results           = [];
        $results['rule']   = $rule;
        $results['status'] = 'success';

        return $results;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Incentives\Rules\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function edit(Rule $rule)
    {
        return view('rules.edit', compact('rule'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Incentives\Rules\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function update(Rule $rule)
    {
        sleep(1);
        $this->validate(request(), [
            'name'     => 'required|min:3',
        ]);
        $rule->update(request()->all());

        $results            = [];
        $results['rule']    = $rule;
        $results['status']  = 'success';
        $results['message'] = __('Datos actualizados');

        return $results;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Incentives\Rules\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rule $rule)
    {
        $rule->delete();
        $results            = [];
        $results['status']  = 'success';
        $results['message'] = __('Datos eliminados');
        return $results;
    }
}
