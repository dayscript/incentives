<?php

namespace App\Http\Controllers;

use App\Incentives\Core\Entity;
use App\Incentives\Rules\Goal;
use App\Incentives\Rules\Rule;
use App\Role;
use App\State;
use App\Agile;
use App\Wordpress;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Log;
use App\Indicator;

class EntitiesController extends Core\BaseController
{
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
        //
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
        $entity->points    = $points;
        $results['entity'] = $entity;

        return $results;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Incentives\Core\Entity $entity
     * @return \Illuminate\Http\Response
     */
    public function showHtml($identification)
    {

      $date  =  Carbon::parse($this->dateModify->format('Y-m-d'));
      $datos  = $this->showByIdentificationtoMonth($identification,$date->format('Y-m-d'));
      if (!isset($datos['status'])) {
        //dd($datos["entity"]->goalvalues);
        $string ='';
        foreach ($datos["entity"]->goalvalues as $key) {
          $string =$string.'<tr style="background-color:#fdf6cc; text-align: center;">
          <td height="40px" style="padding:10px;">'.$key['name'].'</td>
          <td height="40px" width="210px" style="padding:10px;">'.$this->utf16_2_utf8($key['description']).'</td>
          <td height="40px" style="padding:10px;">'.$key['percentage'].'%</td>
          <td height="40px" style="padding:10px;">'.$key['point'].'</td>
        </tr>';
        }

        $string =$string.'<tfoot style="background-color: #f3d431">
        <tr style="text-align: right;">
          <td height="40px" colspan="4" style="padding-right: 10px;font-weight: bold">Total de Tus Millas 123445</td>
        </tr>
      </tfoot>';
        $string='<table border="0" width="100%" cellspacing="2" style="margin-bottom: 30px;">
      <thead style="background-color: #f3d431; font-weight:bold;">
        <tr style="text-align: center">
          <td height="40px">Item</td>
          <td height="40px">Condiciones</td>
          <td height="40px">Tu resultado</td>
          <td height="40px">Tus Millas</td>
        </tr>
      </thead>
      <tbody>'.$string.'</table>';
        echo $string;
      }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Incentives\Core\Entity $entity
     * @return \Illuminate\Http\Response
     */
    public function showIndicators($identification)
    {
        $date  =  Carbon::parse($this->dateModify->format('Y-m-d'));
        $i = 1;
        $mes =  [];
        $month = array('01','02','03','04','05','06','07','08','09','10','11','12');
        foreach ($month as $i) {
          $current = Carbon::createFromDate($date->format('Y'), $i , '01', 'America/Bogota');
          $dataEnd =  $this->showByIdentificationtoMonth($identification,$current->format('Y-m-d'));
          if (!isset($dataEnd['status'])) {
            $mes[] = [
              'mes' => $current->format('F'),
              'sum'=> $dataEnd['entity']->sum
            ];
          }
          else
          {
            $mes[] = [
              'mes' => $current->format('F'),
              'sum'=> 0
            ];
          }
        }
        return $mes;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Incentives\Core\Entity $entity
     * @return \Illuminate\Http\Response
     */
    public function showByMonthHtml($date,$rol = null)
    {
      $data  = $this->showByMonth($date,$rol);
      $string = '<table class="table">
<thead>
  <tr>
      <th>Nombre</th>
      <th>Cedula</th>
      <th>Correo</th>
      <th>Rol</th>
      <th>Total</th>
      <th>Detalles</th>
      <th>Puntos</th>
  </tr>
</thead>
<tbody>';
      foreach ($data as $key) {
        $goal =$this->goalvaluehtml($key['entity']->goalvalues);
        $string = $string.'<tr>
          <td class="active">'.$key['entity']->name.' '.$key['entity']->last_name.'</td>
          <td>'.$key['entity']->identification.'</td>
          <td>'.$key['entity']->email.'</td>
          <td>'.$key['entity']->role->name.'</td>
          <td>'.$key['entity']->sum.'</td>
          <td>
            '.$goal["name"].'
          </td>
          <td>
            '.$goal["point"].'
          </td>
        </tr>';
      }
      $string = $string.'</tbody>';
      echo $string;
    }
    public function goalvaluehtml($goald)
    {
      $string["name"]='';
      $string["point"]='';
      foreach ($goald as $key) {
        $string["name"] = $string["name"].$key["name"].'<br>';
        $string["point"] = $string["point"].$key["point"].'<br>';
      }
      return $string;
    }
    /**
     * Display the specified resource.
     *
     * @param  \App\Incentives\Core\Entity $entity
     * @return \Illuminate\Http\Response
     */
    public function showByMonth($date,$rol = null)
    {
      $entity = Entity::all();
      if ($rol != -1) {
        $entity = $entity->where('role_id',$rol);
      }
      $datosControl = [];
      foreach ($entity as $key) {
        $data = $this->showByIdentificationtoMonth($key->identification,$date);
        $datosControl[] = $data;
      }
      return $datosControl;
    }
    /**
     * Display the specified resource.
     *
     * @param $identification
     * @return \Illuminate\Http\Response
     */
    public function showByIdentificationtoMonth($identification,$date)
    {
        if ($date == 1) {
          $dateFilter = Carbon::parse($this->dateModify->format('Y-m-d'))->format('Y-m-d');
        }
        else
        {
          $dateFilter = Carbon::parse($date)->format('Y-m-d');
        }
        $results = [];
        if ($entity = Entity::where('identification', $identification)->first()) {
            $suma =0;
            $points = [];
            $entity->makeHidden(['rules', 'goals']);
            foreach ($entity->rules as $rule) {
                if (Carbon::parse($dateFilter)->format('m') == Carbon::parse($rule->pivot->created_at)->format('m')) {
                    $points[] = [
                      'id'          => $rule->pivot->id,
                      'created_at'  => $rule->pivot->created_at->toDateTimeString(),
                      'points'      => $rule->pivot->points,
                      'value'       => $rule->pivot->value,
                      'description' => $rule->pivot->description,
                      'rule_id'     => $rule->id
                    ];
                    $suma =  $suma+$rule->pivot->points;
                }
            }
            $goals = [];
            foreach ($entity->goals as $goal) {
                if (Carbon::parse($dateFilter)->format('m') == Carbon::parse($goal->pivot->date)->format('m')) {
                    if($goal->pivot->value == 0)$percentage = 0;
                    else $percentage = round(100 * $goal->pivot->real / $goal->pivot->value, 2);
                    $mod_percentage = Goal::modified($percentage, $goal->modifier);
                    switch ($goal->typegoal_id) {
                      case 1:
                        $mod_max = $goal->point;
                        $percentage_weighed = ($mod_percentage * ($goal->weight / 100));
                        if ($mod_percentage == 100) {
                          $point = $goal->point;
                        }
                        else
                        {
                          $point = 0; 
                        }
                      break;
                      case 2:
                        $mod_max = Goal::maxmodified($goal->point,$goal->pivot->value);
                        $percentage_weighed = ($mod_percentage * ($goal->weight / 100));
                        if ($percentage_weighed != 0) {
                          $point = $goal->point*$goal->pivot->real;
                          //$point = $goal->pivot->real*($mod_max/100);
                        }
                        else
                        {
                          $point = 0; 
                        }
                      break;
                    }
                    $goals[]            = [
                      'id'                  => $goal->pivot->id,
                      'goal_id'             => $goal->id,
                      'name'                => $goal->name,
                      'description'         => $goal->description,
                      'date'                => $goal->pivot->date,
                      'value'               => $goal->pivot->value,
                      'real'                => $goal->pivot->real,
                      'percentage'          => $percentage,
                      'percentage_modified' => $mod_percentage,
                      'percentage_weighed'  => $percentage_weighed,
                      'goal_max'            =>$mod_max,
                      'point'               =>$point,
                    ];
                     $suma =  $suma+$point;
                }
            }
            $entity->role = $entity->role;
            $entity->goalvalues = $goals;
            $entity->sum = $suma;
            $entity->points     = $points;
            $results['entity']  = $entity;
        } else {
            $results['status']  = 'error';
            $results['message'] = __('No existe la entidad');
        }

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
            $suma =0;
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
                $suma =  $suma+$rule->pivot->points;
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
                $suma =  $suma+$percentage_weighed;
            }
            $entity->role = $entity->role;
            $entity->goalvalues = $goals;
            $entity->points     = $points;
            $entity->sum = $suma;
            $results['entity']  = $entity;
        } else {
            $results['status']  = 'error';
            $results['message'] = __('No existe la entidad');
        }

        return $results;
    }
    public function addentities()
    {
    }
    /**
     * Display the specified resource.
     *
     * @param $identification
     * @return \Illuminate\Http\Response
     */
    public function addentitiesSOAP()
    {
        $this->Agile = new Agile();
        $this->Wordpress = new Wordpress();
        $this->Log = new Log();
        header("Content-Type: text/xml\r\n");
        ob_start();
        $capturedData = fopen('php://input', 'rb');
        $xml = fread($capturedData,5000);
        fclose($capturedData);
        ob_end_clean();
        if ($xml != '') {
            $xml = str_replace('soapenv:', '', str_replace('sf:', '', $xml));
            $xml = <<<XML
$xml 
XML;
            libxml_use_internal_errors(true);
            $elem = simplexml_load_string($xml);
            if($elem !== false)
            {
                $textxml = $xml;
                $xml = new \SimpleXMLElement($xml);
                $id = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Cedula_Identidad__c)));
                $name = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Nombre__c)));
                $last_name = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Apellidos__c)));
                $email = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->email__c)));
                $role = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Cargo__c)));
                $state = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Status__c)));
                $key = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->novolink__c)));
                $departamento = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Departamento__c)));
                $celular = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->phone__c)));
                $role =$departamento.' '.$role;
                $msj = 'OK';
                $check = false;
                $checkToken = false;
                $check_role = false;
                $check_state = false;
                $role_id = null;
                $state_id = null;
                $pos = strpos($key, 'PC0000002'.date('Ymd'));
                if ($pos == 0) {
                    $rest = substr($key, 17, 10000);
                }
                if($pos !== false and !$checkToken)
                {
                    foreach (Entity::All() as $key => $value) {
                        if( strtolower($value->email)  == strtolower($email) ) {
                            $check = true; 
                            $msj = 'El correo ya existe Incentive';
                        }
                    }
                    if ( $check == false ) {                      
                        foreach (Role::All() as $key => $value) {
                            if( strtolower($value->name)  == strtolower($role) ) {
                                $role_id = $value->id;
                                $check_role = true;
                            }
                        }
                        if ( $check_role == false ) {
                            $role_id = Role::create([ 'name'  =>  ucwords($role) ]);
                            $role_id = $role_id->id;
                        }

                        foreach (State::All() as $key => $value) {
                            if( strtolower($value->name)  == strtolower($state) ) {
                                $state_id = $value->id;
                                $check_state = true;
                            }
                        }
                        if ( $check_state == false ) {
                            $state_id = State::create([ 'name'  =>  ucwords($state) ]);
                            $state_id = $state_id->id;
                        }
                        $msj = Entity::create([
                                'identification'  => $id,
                                'name'  =>  $name,
                                'last_name'  =>  $last_name,
                                'email'  =>  $email,
                                'role_id'  =>  $role_id,
                                'state_id'  =>  $state_id,
                                'client_id' => 1//pendiente revisasr llave para carga 
                        ]);
                        $clave = $this->generarCodigo(10);
                        $datosUser = array(
                            'nombre' => $name,
                            'apellido' => $last_name,
                            'correo' => $email,
                            'celular' => $celular,
                            'codigounico' => $clave,
                            'estado' => 'Activo',
                            'genero' => 'Masculino',
                            'cargo' => $role,
                            'documento' => $id
                        ); 
                        $returndataAgile = $this->Agile->createUserAgile($datosUser);
                        $datosUser = array('username' => $id,'first_name'=>$name,'last_name'=> $last_name,'email'=> $email,'password'=>$clave);
                        $returndata = $this->Wordpress->createUserWordpres($datosUser,$textxml);
                        if (!isset($returndata->message) and $returndataAgile["status"]) {
                            $envio = array('dataSend' => $xml,'msn' => 'Carga en wordpress','error'=>$returndata, 'status'=> true);
                            $envio = array('dataSend' => $xml,'msn' => 'Carga en agile','error'=>$returndataAgile, 'status'=> true);
                        }else
                        {
                            if (isset($returndata->message)) {
                                $envio = array('dataSend' => $xml,'msn' => 'error en wordpress','error'=>$returndata->message, 'status'=> false);
                            }

                            if (!$returndataAgile["status"]) {
                                $envio = array('dataSend' => $xml,'msn' => 'error en agile','error'=>$returndataAgile["msn"], 'status'=> false);   
                            }
                        }

                        
                    }
                    else
                    {
                        $envio = array('dataSend' => $xml,'msn' => $msj.' '.$email,'error'=>$msj, 'status'=> false);
                    }
                }
                else
                {
                    $envio = array('dataSend' => $xml,'msn' => 'Error Autenticacion','error'=>null, 'status'=> false);
                }
            }
            else
            {
                $errorText = '';
                foreach(libxml_get_errors() as $error)
                {
                    $errorText = $errorText.$error->message;
                }
                $envio = array('dataSend' => $xml,'msn' => 'no es xml','error'=>$errorText, 'status'=> false);
            }
        }
        else
        {
            $envio = array('dataSend' => $xml,'msn' => 'no hay cadena de xml', 'status'=> false,'error'=>null);
        }
        $this->Log->createLog(array('table' => 'addentitiesSOAP','type'=>$envio['status'],'user_id'=>1,'message'=>json_encode($envio),'client_id'=>1));
        $this->respondsoap(($envio['status']) ? 'true' : 'false');
    }
    public function editentities()
    {
        # code...
    }
    public function editentitiesSOAP()
    {
        $this->Agile = new Agile();
        $this->Wordpress = new Wordpress();
        $this->Log = new Log();
        header("Content-Type: text/xml\r\n");
        ob_start();
        $capturedData = fopen('php://input', 'rb');
        $xml = fread($capturedData,5000);
        fclose($capturedData);
        ob_end_clean();
        if ($xml != '') {
            $xml = str_replace('soapenv:', '', str_replace('sf:', '', $xml));
            $xml = <<<XML
$xml 
XML;
            libxml_use_internal_errors(true);
            $elem = simplexml_load_string($xml);
            if($elem !== false)
            {
                $textxml = $xml;
                $xml = new \SimpleXMLElement($xml);
                $id = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Cedula_Identidad__c)));
                $name = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Nombre__c)));
                $last_name = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Apellidos__c)));
                $email = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->email__c)));
                $role = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Cargo__c)));
                $state = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Status__c)));
                $key = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->novolink__c)));
                $departamento = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->Departamento__c)));
                $celular = str_replace('{"0":"','',str_replace('"}','',json_encode($xml->Body->notifications->Notification->sObject->phone__c)));
                $pos = strpos($key, 'PC0000002'.date('Ymd'));
                $role =$departamento.' '.$role;
                $check = false; 
                $checkToken = false;
                $pos = 2;
                if ($pos == 0) {
                    $rest = substr($key, 17, 10000);
                }
                if($pos !== false and !$checkToken)
                {
                    foreach (Entity::All() as $key => $value) {
                        if( strtolower($value->email)  == strtolower($email) ) {
                            $update = Entity::find($value->id);
                            $update1 = $update;
                            $check = true; 
                            $dataEntity =  $value;
                            $datosCarga = array(
                                'identification'  => $id,
                                'name'  =>  $name,
                                'last_name'  =>  $last_name,
                                'email'  =>  $email,
                                'role_id'  =>  $role,
                                'state_id'  =>  $state,
                                'client_id' => 1//pendiente revisasr llave para carga 
                            );
                            $datosEntity = array(
                                'identification'  => $dataEntity->identification,
                                'name'  =>  $dataEntity->name,
                                'last_name'  =>  $dataEntity->last_name,
                                'email'  =>  $dataEntity->email,
                                'role_id'  =>  $dataEntity->role->name,
                                'state_id'  =>  $dataEntity->state->name,
                                'client_id' => 1//pendiente revisasr llave para carga 
                            );
                            $resultado = array_diff($datosCarga,$datosEntity);
                            foreach ($resultado as $key1 => $value1) 
                            {
                                if (strpos($key1, '_id')) {
                                    switch ($key1) {
                                        case 'state_id':
                                            $consulta = State::All();
                                        break;
                                        case 'role_id':
                                            $consulta = Role::All();
                                        break;
                                        default:
                                            $consulta = null;
                                        break;
                                    }
                                    if (!is_null($consulta)) 
                                    {
                                        $check_id = false;
                                        foreach ($consulta as $key2 => $value2) 
                                        {
                                            if( strtolower($value2->name)  == strtolower($value1) ) {
                                                $cambio = $value2->id;
                                                $check_id = true;
                                            }
                                        }
                                        if ( $check_id == false ) {
                                            switch ($key1) {
                                                case 'state_id':
                                                    $create_id = State::create([ 'name'  =>  ucwords($value1) ]);
                                                break;
                                                case 'role_id':
                                                    $create_id = Role::create([ 'name'  =>  ucwords($value1) ]);
                                                break;
                                            }
                                            $create_id = $create_id->id;
                                            $update->$key1 = $create_id;
                                        }
                                        else
                                        {
                                            $update->$key1 = $cambio;
                                        }
                                    }
                                }
                                else
                                {
                                    $update->$key1 = $value1;
                                }
                            }
                            if (count($resultado) > 0) {
                                $update->save();
                                $envio = array('dataSend' => $xml,'msn' => 'se actualizo registro Incentive','error'=>null, 'status'=> true);
                            }
                            else
                            {
                                $envio = array('dataSend' => $xml,'msn' => 'no se actualizo registro','error'=>null, 'status'=> false);
                            }
                            
                        }
                    }
                    if (!$check) {
                        $envio = array('dataSend' => $xml,'msn' => 'Error Autenticacion','error'=>null, 'status'=> false);
                    }
                }
                else
                {
                    $envio = array('dataSend' => $xml,'msn' => 'Error Autenticacion','error'=>null, 'status'=> false);
                }
            }
            else
            {
                $errorText = '';
                foreach(libxml_get_errors() as $error)
                {
                    $errorText = $errorText.$error->message;
                }
                $envio = array('dataSend' => $xml,'msn' => 'no es xml','error'=>$errorText, 'status'=> false);
            }
        }
        else
        {
            $envio = array('dataSend' => $xml,'msn' => 'no hay cadena de xml','error'=>null, 'status'=> false);
        }
        $this->Log->createLog(array('table' => 'editentitiesSOAP','type'=>$envio['status'],'user_id'=>1,'message'=>json_encode($envio),'client_id'=>1));
        $this->respondsoap(($envio['status']) ? 'true' : 'false');
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
    public function addvaluetype($identification)
    {

        $start = new Carbon('first day of this month');
        $this->Log = new Log();
        $results = [];
        if ($rule = Indicator::find(request()->get('indicators'))) {
            $entity            = Entity::firstOrCreate(['identification' => $identification]);
            $results['entity'] = $entity;
            $dataenvio = array(
              'fechaSend' => $this->dateModify->toDateString(), 
              'indicatorsSend' => request()->get('indicators'), 
              'rolSend' => $entity->role_id, 
              'entitySend'=> $results['entity'],
              'identificador' => $identification,
            );
            switch ($rule->type_id) {
                case 1:
                    $goal = Goal::where('date_start','<=',$this->dateModify->toDateString())
                    ->where('date_end','>=',$this->dateModify->toDateString())
                    ->where('client_id','=',1)
                    ->where('rol_id','=',$entity->role_id)
                    ->where('indicator_id','=',request()->get('indicators'))->get();
                    if (count($goal) != 0) {
                        $goal = $goal[0];
                        $value = request()->get('value', 0);
                        $real  = request()->get('real', 0);
                        $date  = request()->get('date', $start->toDateString());
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
                        $envio = array('dataSend' => array_merge($results,$dataenvio),'msn' => 'Carga de regla ','error'=>'', 'status'=> true);
                    }
                    else
                    {
                      $envio = array('dataSend' => $dataenvio,'msn' => 'No existe Goal','error'=>'No existe Goal', 'status'=> false);
                    }
                break;
                case 2:
                    $rule = Rule::where('date_start','<=',$this->dateModify->toDateString())
                    ->where('date_end','>=',$this->dateModify->toDateString())
                    ->where('client_id','=',1)
                    ->where('rol_id','=',$entity->role_id)
                    ->where('indicator_id','=',request()->get('indicators'))->get();
                    if (count($rule) != 0) {
                        $rule = $rule[0];
                        $value = request()->get('value', 1);
                        $description = request()->get('description');
                        $ids         = $entity->rules()->pluck('entity_rule.id')->toArray();
                        $points = $value * $rule->points;
                        $points = Rule::modified($points, $rule->modifier);
                        $entity->rules()->attach($rule->id, ['value' => $value, 'points' => $points, 'description' => $description]);
                        foreach ($entity->rules as $val) {
                            if (!in_array($val->pivot->id, $ids)) $rvalue = $val->pivot;
                        }
                        $results['value'] = $rvalue;
                        $envio = array('dataSend' => array_merge($results,$dataenvio),'msn' => 'Carga de regla','error'=>'', 'status'=> true);
                    }
                    else
                    {
                      $envio = array('dataSend' => $dataenvio,'msn' => 'No existe Goal','error'=>'No existe Goal', 'status'=> false);
                    }
                break;
            }
        }else
        {
          $envio = array('dataSend' => request()->get('indicators'),'msn' => 'Indicador No existente','error'=>'Indicador No existente', 'status'=> false);
        }
        $this->Log->createLog(array('table' => 'addvaluetype','type'=>$envio['status'],'user_id'=>$identification,'message'=>json_encode($envio),'client_id'=>1));
        return $results;
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
}
