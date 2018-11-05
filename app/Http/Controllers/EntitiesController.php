<?php

namespace App\Http\Controllers;

use App\Incentives\Core\Entity;
use App\Incentives\Core\EntityData;
use App\Kokoriko\File;
use App\Incentives\Rules\Goal;
use App\Incentives\Rules\Rule;
use Carbon\Carbon;
use Illuminate\Http\Request;

use dayscript\laravelZohoCrm\laravelZohoCrm;
use Storage;
use Log;

class EntitiesController extends Controller
{

    public function __construct(){

    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $entities = Entity::orderBy('name')->paginate(10);

        return view('entities.index', compact('entities'));

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
      sleep(1);
      $results    = [];
      $goals      = [];
      $this->validate(request(),[
        'identification'     => 'required',
        'name'         => 'required',
      ]);

      $to_create = ['identification' => $request->input('identification'),'name' => $request->input('name')];
      $entity = Entity::firstOrCreate( $to_create );
      $to_zoho = $request->all();
      $entity->subscriptionPoints();
      $entity->createZoho('Contacts',$to_zoho);

      $results['entity'] = $entity;
      $results['status'] = '200';
      $results['messages'] = '';
      
      return $entity;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Incentives\Core\Entity $entity
     * @return \Illuminate\Http\Response
     */
    public function show(Entity $entity)
    {
        $results = [];
        $points  = [];
        $entity->makeHidden('rules');
        foreach ($entity->rules as $rule) {
            $points[] = [
              'created_at'  => $rule->pivot->created_at->toDateTimeString(),
              'points'      => $rule->pivot->points,
              'value'       => $rule->pivot->value,
              'description' => $rule->pivot->description,
            ];
        }
        $entity->totalpoints = $entity->getPoints();

        $entity->point_values    = $points;
        $entity->data;
        $entity->redemptions;
        $entity->invoices;
        $results['entity'] = $entity;

        return $results;
    }

    /**
     * Display the specified resource.
     *
     * @param $identification
     * @return \Illuminate\Http\Response
     */
    public function showByIdentification($identification)
    {
        $results = [];
        if ($entity = Entity::where('identification', $identification)->first()) {

            $points = [];
            $entity->makeHidden(['rules', 'goals']);
            foreach ($entity->rules as $rule) {
                $points[] = [
                  'id'          => $rule->pivot->id,
                  'created_at'  => $rule->pivot->created_at->toDateTimeString(),
                  'points'      => $rule->pivot->points,
                  'value'       => $rule->pivot->value,
                  'description' => $rule->pivot->description,
                  'rule_id'     => $rule->id
                ];
            }
            $goals = [];
            foreach ($entity->goals as $goal) {
                if($goal->pivot->value == 0)$percentage = 0;
                else $percentage = round(100 * $goal->pivot->real / $goal->pivot->value, 2);
                $mod_percentage = Goal::modified($percentage, $goal->modifier);
                $percentage_weighed = $mod_percentage * ($goal->weight / 100);
                $goals[]            = [
                  'id'                  => $goal->pivot->id,
                  'goal_id'             => $goal->id,
                  'date'                => $goal->pivot->date,
                  'value'               => $goal->pivot->value,
                  'real'                => $goal->pivot->real,
                  'percentage'          => $percentage,
                  'percentage_modified' => $mod_percentage,
                  'percentage_weighed'  => $percentage_weighed,
                ];
            }

            $entity->totalpoints = $entity->getPoints();
            $entity->point_values = $points;
            $entity->goal_values = $goals;
            $entity->points_overcome = $entity->overcomePoints();
            $entity->redemptions;
            $entity->invoices;
            $results['entity']  = $entity;
        } else {
          $results['status']  = '404';
          $results['message'] = __('No existe la entidad');
          return \Response::json([$results], 404); // Status code here
        }


        return $results;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Incentives\Core\Entity $entity
     * @return \Illuminate\Http\Response
     */
    public function edit(Entity $entity)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request    $request
     * @param  \App\Incentives\Core\Entity $entity
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Entity $entity)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Incentives\Core\Entity $entity
     * @return \Illuminate\Http\Response
     */
    public function destroy(Entity $entity)
    {
        //
    }

    /**
     * Adds rule value to given entity
     * @param $identification
     * @return array
     */
    public function addvalue($identification)
    {
        $this->validate(request(), [
          'rule' => 'required|exists:rules,id',
        ]);
        $results = [];
        $entity  = Entity::firstOrCreate(['identification' => $identification]);

        if ($rule = Rule::find(request()->get('rule'))) {
            $value       = request()->get('value', 1);
            $description = request()->get('description');
            $ids         = $entity->rules()->pluck('entity_rule.id')->toArray();
            $points = $value * $rule->points;
            $points = Rule::modified($points, $rule->modifier);
            $entity->rules()->attach($rule->id, ['value' => $value, 'points' => $points, 'description' => $description]);
            foreach ($entity->rules as $val) {
                if (!in_array($val->pivot->id, $ids)) $rvalue = $val->pivot;
            }
            $results['value'] = $rvalue;
        }
        $results['entity'] = $entity;

        return $results;
    }

    /**
     * Adds goal value to given entity
     * @param $identification
     * @return array
     */
    public function addgoalvalue($identification)
    {
        $this->validate(request(), [
          'goal' => 'required|exists:goals,id',
        ]);
        $results           = [];
        $entity            = Entity::firstOrCreate(['identification' => $identification]);
        $results['entity'] = $entity;

        if ($goal = Goal::find(request()->get('goal'))) {
            $value = request()->get('value', 0);
            $real  = request()->get('real', 0);
            $date  = request()->get('date', Carbon::now()->toDateString());
            if ($gvalue = $entity->goals()->wherePivot('date', $date)->where('goals.id', $goal->id)->first()) {
                $gvalue->pivot->value = $value;
                $gvalue->pivot->real  = $real ;
                if($gvalue->pivot->value == 0)$percentage = 0;
                else $percentage = round(100 * $gvalue->pivot->real / $gvalue->pivot->value, 2);
                $mod_percentage = Goal::modified($percentage, $gvalue->modifier);
                $percentage_weighed = $mod_percentage * ($gvalue->weight / 100);
                $gvalue->pivot->percentage = $percentage;
                $gvalue->pivot->percentage_modified = $mod_percentage;
                $gvalue->pivot->percentage_weighed = $percentage_weighed;
                $results['value']     = $gvalue->pivot;
                $entity->goals()->wherePivot('date', $date)->updateExistingPivot($goal->id, ['value' => $value, 'real' => $real]);
            } else {
                $ids = $entity->goals()->pluck('entity_goal.id')->toArray();
                $entity->goals()->attach($goal->id, ['value' => $value, 'date' => $date, 'real' => $real]);
                foreach ($entity->goals as $val) {
                    if (!in_array($val->pivot->id, $ids)) {
                        $gvalue = $val;
                        break;
                    }
                }
                if($gvalue->pivot->value == 0)$percentage = 0;
                else $percentage = round(100 * $gvalue->pivot->real / $gvalue->pivot->value, 2);
                $mod_percentage = Goal::modified($percentage, $gvalue->modifier);
                $percentage_weighed = $mod_percentage * ($gvalue->weight / 100);
                $gvalue->pivot->percentage = $percentage;
                $gvalue->pivot->percentage_modified = $mod_percentage;
                $gvalue->pivot->percentage_weighed = $percentage_weighed;

                $results['value'] = $gvalue->pivot;
            }
        }

        return $results;
    }

    /**
     * Deletes rule value to given entity
     * @param $identification
     * @param $id
     * @return array
     */
    public function delvalue($identification, $id)
    {
        $results           = [];
        $entity            = Entity::firstOrCreate(['identification' => $identification]);
        $values            = $entity->rules()->wherePivot('id', $id)->detach();
        $results['values'] = $values;

        return $results;
    }

    /**
     * Deletes goal value to given entity
     * @param $identification
     * @param $id
     * @return array
     */
    public function delgoalvalue($identification, $id)
    {
        $results           = [];
        $entity            = Entity::firstOrCreate(['identification' => $identification]);
        $values            = $entity->goals()->wherePivot('id', $id)->detach();
        $results['values'] = $values;

        return $results;
    }


    /**
     * Create entities from file FTP
     * @param
     * @return array
     */
    public function createFromFile(){

      $limit_process_entityes = env('LIMIT_IMPORT_ENTITIES',0);
      $file_keys = array('identification','name','email','phone','date','identification2','name2','line_break');
      $return = array();
      $entities = [];
      $message = '';


      $files = Storage::disk('ftp')->allFiles('preregistro/');

      foreach ($files as $num_file => $file) {

        if( count(File::where('name',$file)->get()) ) {$message= 'no new files'; continue; };
        if( $num_file > $limit_process_entityes ) break; # limita el numero de archivos a procesar


        $file_contents = Storage::disk('ftp')->get($file);


        $data_contents = explode(PHP_EOL,$file_contents);

        foreach( $data_contents as $key => $entity){

          if(strlen($entity) == 0) continue;

          $entity = explode(';', $entity);

          foreach ($entity as $row => $value) {
            $new_entity[$file_keys[$row]] = $value;
          }

          if( strpos($new_entity['identification'], '.') != false ) {

              $new_entity['identification'] = explode('.',$new_entity['identification'])[0];
          }


          $entity = Entity::firstOrCreate(['identification' => $new_entity['identification'], 'name' => $new_entity['name'] ]);
          $entity_data = EntityData::firstOrCreate([
             'entity_id' => $entity->id,
             'email'  => $new_entity['email'],
             'phone'  => $new_entity['phone'],
             'real_date_up' => $new_entity['date']
           ]);
          Log::info('Enitity Create OK: '.$entity->id);

          $recordsArray = array([
            "Company" => "Kokoriko",
            "Email" => $entity_data->email,
            "Description" => null,
            "Rating" => null,
            "Website" => null,
            "Twitter" => null,
            "Salutation" => "Sr.",
            "First_Name" => explode(' ',$entity->name)[0],
            "Lead_Status" => null,
            "Industry" => null,
            "Full_Name" => $entity->name,
            "Record_Image" => null,
            "Skype_ID" => null,
            "Phone" => "3167490905",
            "Street" => null,
            "Zip_Code" => null,
            "Email_Opt_Out" => false,
            "Designation" => "Comprador",
            "City" => null,
            "No_of_Employees" => null,
            "Mobile" => null,
            "Prediction_Score" => null,
            "Last_Name" => explode(' ',$entity->name)[1],
            "State" => null,
            "Lead_Source" => null,
            "Country" => null,
            "Tag" => [],
            "Fax" => null,
            "Annual_Revenue" => null,
            "Secondary_Email" => null
          ]);

          $zoho = new laravelZohoCrm;
          $zoho->addModuleRecord('Contacts',$recordsArray);
        }

        $file_save = File::firstOrCreate(['name' => $file,'status' => true]);
        $message .= 'Process File: '. $file_save ."\n";
      }

      Log::info('Import entites : '.$message);
      $return['status'] = 'success';
      $return['entities'] = $entities;
      $return['message'] = $message;

      return $return;
    }

}
