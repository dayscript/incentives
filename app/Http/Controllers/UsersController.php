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
        $users = User::orderBy('name')->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cities            = [];
        foreach (City::orderBy('country')->get() as $city) {
            if (!isset($cities[$city->country])) $cities[$city->country] = [];
            $cities[$city->country][] = $city;
        }

        return view('users.create', compact('cities'));
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
            'name'     => 'required|min:5',
            'email'    => [
                'required',
                'email',
                Rule::unique('users')
            ],
            'position' => 'min:2',
        ]);

        $user = User::create(request()->except('password'));
        if ($pw = request()->password) {
            $this->validate(request(), [
                'password' => 'min:6',
            ]);
            $user->password = bcrypt($pw);
            $user->save();
        }

        if(str_contains($user->avatar,'avatars/0/')){
            $old_file = str_replace('/storage/','',$user->avatar);
            //            dd(Storage::disk('public')->size($old_file));
            $new_file = str_replace('avatars/0/','avatars/'.$user->id.'/',$old_file);
            Storage::disk('public')->move($old_file, $new_file);
            $user->avatar= '/storage/'.$new_file;
            $user->save();
        }
        $results            = [];
        $results['user']    = $user;
        $results['status']  = 'success';
        $results['message'] = __('Usuario creado');

        return $results;
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
        $cities            = [];
        foreach (City::orderBy('country')->get() as $city) {
            if (!isset($cities[$city->country])) $cities[$city->country] = [];
            $cities[$city->country][] = $city;
        }

        return view('users.edit', compact('user', 'cities'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\User $user
     * @return \Illuminate\Http\Response
     */
    public function update(User $user)
    {
        sleep(1);
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
        $results = [];
        $user->delete();
        $results['status']  = 'success';
        $results['message'] = __('Usuario eliminado');
        return $results;
    }
}
