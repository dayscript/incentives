<?php

namespace App\Http\Controllers;

use App\Incentives\Utils\City;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $results           = [];
        $results['user']   = $user;
        $results['status'] = 'success';
        $cities            = [];
        foreach (City::orderBy('country')->get() as $city) {
            if (!isset($cities[$city->country])) $cities[$city->country] = [];
            $cities[$city->country][] = $city;
        }
        $results['cities'] = $cities;

        return $results;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        $this->validate(request(), [
            'name'     => 'required|min:5',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id)
            ],
            'position' => 'min:2',
        ]);

        $user->update(request()->except('password'));
        if ($pw = request()->password) {
            $this->validate(request(), [
                'password' => 'min:6',
            ]);
            $user->password = bcrypt($pw);
            $user->save();
        }

        $results            = [];
        $results['user']    = $user;
        $results['status']  = 'success';
        $results['message'] = __('Datos actualizados');

        return $results;

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
