<?php

namespace App\Http\Controllers;

use App\Incentives\Core\Client;
use App\Incentives\Rules\Goal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $clients = Client::orderBy('name')->paginate(10);

        return view('clients.index', compact('clients'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function apilist()
    {
        $results            = [];
        $clients            = Client::orderBy('name')->get();
        $results['clients'] = $clients;

        return $results;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('clients.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        sleep(1);
        $this->validate(request(), [
          'name' => 'required|min:5',
        ]);

        $client = Client::create(request()->all());
        if (str_contains($client->image, 'clients/0/')) {
            $old_file = str_replace('/storage/', '', $client->image);
            //            dd(Storage::disk('public')->size($old_file));
            $new_file = str_replace('clients/0/', 'clients/' . $client->id . '/', $old_file);
            Storage::disk('public')->move($old_file, $new_file);
            $client->image = '/storage/' . $new_file;
            $client->save();
        }
        $results            = [];
        $results['client']  = $client;
        $results['status']  = 'success';
        $results['message'] = __('Cliente creado');

        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Incentives\Core\Client $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        $results           = [];
        $results['client'] = $client;
        $results['status'] = 'success';

        return $results;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Incentives\Core\Client $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('clients.edit', compact('client'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Incentives\Core\Client $client
     * @return \Illuminate\Http\Response
     */
    public function update(Client $client)
    {
        sleep(1);
        $this->validate(request(), [
          'name' => 'required|min:5',
        ]);

        $client->update(request()->all());

        $results            = [];
        $results['client']  = $client;
        $results['status']  = 'success';
        $results['message'] = __('Datos actualizados');

        return $results;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Incentives\Core\Client $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $results = [];
        $client->delete();
        $results['status']  = 'success';
        $results['message'] = __('Cliente eliminado');

        return $results;
    }

    /**
     * List date goal values
     * @param Client $client
     * @param null   $date
     * @return array
     */
    public function dategoalvalues(Client $client, $date = null)
    {
        if (!$date) $date = Carbon::now()->toDateString();
        $results                = [];
        $results['goal_values'] = [];
        foreach ($client->goals as $goal) {
            foreach ($goal->entities()->wherePivot('date', $date)->get() as $entity) {
                if($entity->pivot->value == 0)$percentage = 0;
                else $percentage = round(100 * $entity->pivot->real / $entity->pivot->value, 2);
                if ($goal->modifier == 'modifier1') {
                    $mod_percentage = Goal::modifier1($percentage);
                } else if ($goal->modifier == 'modifier2') {
                    $mod_percentage = Goal::modifier2($percentage);
                } else if ($goal->modifier == 'modifier3') {
                    $mod_percentage = Goal::modifier3($percentage);
                } else if ($goal->modifier == 'modifier4') {
                    $mod_percentage = Goal::modifier4($percentage);
                } else {
                    $mod_percentage = $percentage;
                }
                $percentage_weighed       = $mod_percentage * ($goal->weight / 100);
                $results['goal_values'][] = [
                  'id'                  => $entity->pivot->id,
                  'identification'      => $entity->identification,
                  'name'                => $entity->name,
                  'goal_id'             => $entity->pivot->goal_id,
                  'value'               => $entity->pivot->value,
                  'real'                => $entity->pivot->real,
                  'percentage'          => $percentage,
                  'percentage_modified' => $mod_percentage,
                  'percentage_weighed'  => $percentage_weighed,
                  'date'                => $entity->pivot->date,
                  'created_at'          => $entity->pivot->created_at->toDateTimeString(),
                ];
            }
        }

        return $results;

    }
}
