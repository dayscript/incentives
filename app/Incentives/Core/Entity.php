<?php

namespace App\Incentives\Core;

use App\Incentives\Rules\Goal;
use App\Incentives\Rules\Rule;
use App\Incentives\Core\EntityGoal;
use App\Incentives\Core\Information;
use App\Kokoriko\Redemption;
use App\Kokoriko\Invoice;
use Carbon\Carbon;
use dayscript\laravelZohoCrm\laravelZohoCrm;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/**
 * @property mixed rules
 */
class Entity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['identification', 'name'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $zohoFields = [
      'Record_Image'                => NULL,
      'Owner'                       => NULL,
      'Created_By'                  => NULL,
      'Created_Time'                => NULL,
      'Salutation'                  => NULL,
      'Cedula'                      => 'field_no_identificacion',
      'Cedula_Ascesor'              => 'cedula_del_asesor',
      'Date_of_Birth'               => 'field_birthdate',
      'Email'                       => 'mail',
      'Email_Opt_Out'               => TRUE,
      'Fecha_de_Preregistro'        => NULL,
      'Fecha_de_Registro'           => 'fecha_de_registro',
      'First_Name'                  => 'field_nombres',
      'Fuente_de_AdquisicionEdit'   => NULL,
      'Last_Activity_Time'          => NULL,
      'Last_Name'                   => 'field_apellidos',
      'Mobile'                      => 'field_telephone',
      'Modified_By'                 => NULL,
      'Modified_Time'               => NULL,
      'Negative_Score'              => NULL,
      'Negative_Touch_Point_Score'  => NULL,
      'Nombre_de_Asesor'            => 'asesor',
      'Positive_Score'              => NULL,
      'Positive_Touch_Point_Score'  => NULL,
      'Score'                       => NULL,
      'Touch_Point_Score'           => NULL,
      'Twitter'                     => NULL,
      'Convertir_en_Contacto'       => 'to_contact'
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    public $fieldsRest = [
         'name' => null,
         'pass' => null,
         'mail' => null,
         'status' => null,
         'field_no_identificacion' => null,
         'field_nombres' => null,
         'field_apellidos' => null,
         'field_acepto_terminos_y_condicio' => null,
         'field_acepto_politica_de_datos_p' => null,
         'field_birthdate' => null,
         'field_gender' => null,
         'field_telephone' => null,
         'roles' => null,
     ];


    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany(Rule::class)->withPivot('id','value','points','description')->withTimestamps();
    }
    /**
     * Relationship with associated goals values
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function goals()
    {
        return $this->belongsToMany(Goal::class)->withPivot('id','value','real','date')->withTimestamps();
    }

    /**
     * Relationship with associated goals values
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

     public function entityGoals(){
       return $this->hasMany(EntityGoal::class,'entity_id');
     }

    // /**
    //  * Relationship with associated rules values
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasOne
    //  */
    // public function data()
    // {
    //     return '';//$this->hasOne(EntityData::class,'entity_id');
    // }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function entityInformation()
    {
        return $this->belongsToMany(Information::class);
    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
      public function createInformation( $entity_information = array() )
      {

        $name_and_last_name = explode(' ',$entity_information['name']);


        $information = new Information;
        $information->name  = $this->identification;
        $information->pass  = $this->identification;
        $information->nombres  = $name_and_last_name[0];
        $information->apellidos  = $name_and_last_name[1];
        $information->no_identificacion  = $this->identification;
        $information->acepto_terminos_y_condicio  = 1;
        $information->acepto_politica_de_datos_p  = 1;
        $information->birthdate  = ( isset($entity_information['field_birthdate'] ) ) ? $entity_information['field_birthdate']:null;
        $information->gender  = ( isset($entity_information['field_gender'] ) ) ? $entity_information['field_gender']:null;;
        $information->status = 1;
        $information->telephone  = $entity_information['field_telephone'];
        $information->asesor  = $entity_information['nombre_asesor'];
        $information->no_identificacion_asesor  = $entity_information['cedula_del_asesor'];
        $information->roles  = 'incentives';
        $information->mail  = $entity_information['mail'];

        $information->save();
        $this->entityInformation()->attach($information);
      }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
      public function totalpoints()
      {
          $total = 0;
          foreach ($this->rules as $rule){
              $total += $rule->pivot->points;
          }
          return $total;
      }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function redemptions()
    {
        return $this->hasMany(Redemption::class);
    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {

        return $this->hasMany(Invoice::class,'identification','identification');
    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function getPoints(){
      $invoices = [];
      $redemptions = [];
      $entity_goals =[];
      $total = 0;
      $date = Carbon::now()->subYears(1);


      foreach ( $this->invoices->where('invoice_date_up','>', $date->format('Y-m-d') ) as $key => $invoice ) {
          $invoices[] = (int)$invoice->value;
      }

      foreach ( $this->redemptions->where('created_at','>', $date->format('Y-m-d') )  as $key => $redemption ) {
          $redemptions[] = (int)$redemption->value;
      }

      foreach ($this->entityGoals as $key => $value) {
        $entity_goals[] = (int)$value->value;
      }

      foreach ($this->rules as $rule){
          $total += $rule->pivot->points;
      }

      return (int)number_format( ( array_sum($invoices)  / 1000 ) + ( array_sum($entity_goals) + $total ) - array_sum($redemptions),0 ) ;
    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function overcomePoints(){
        $invoices_overcome = [];
        $date_from = Carbon::now()->subYears(364);
        $date_to = Carbon::now()->subYears(330);
        $invoices = $this->invoices->where('invoice_date_up', '>=' , $date_from->format('Y-m-d'))
                                   ->where('invoice_date_up', '<=' , $date_to->format('Y-m-d'));

         foreach ( $invoices as $key => $invoice ) {
             $invoices_overcome[] = (int)$invoice->value;
         }

        return (int)number_format( array_sum($invoices_overcome)  / 1000 ) ;

    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptionPoints(){
      $rule = Rule::where('name', '=', 'Activación de cuenta')->first();
      if($rule){
        $params = ['value'=>$rule->points,'points'=>$rule->points,'description'=>$rule->description];
        $this->rules()->attach($rule,$params);
      }
    }

    /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;

     */
    public function getZoho($module, $entity_id){
      $zoho = new laravelZohoCrm();
      $zoho->getModuleRecord($module, $entity_id);
      return $zoho;
    }

    /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;

     */
    public function createZoho($module){

      $arrayRecod = [
        'mail' =>  $this->entityInformation[0]->mail,
        'field_no_identificacion' =>  (string)$this->identification,
        'field_nombres' =>  $this->entityInformation[0]->nombres,
        'field_apellidos' =>  $this->entityInformation[0]->apellidos,
        'field_telephone' => $this->entityInformation[0]->telephone,
        'asesor' => $this->entityInformation[0]->asesor,
        'cedula_del_asesor' => $this->entityInformation[0]->no_identificacion_asesor,
        'field_gender' => $this->entityInformation[0]->no_identificacion_gender,
        'fecha_de_registro'=>str_replace(' ', 'T', $this->entityInformation[0]->created_at->format('Y-m-d H:m:s') ).'-05:00',
        'field_birthdate' => $this->entityInformation[0]->birthdate,
        'roles' => $this->entityInformation[0]->roles,
        'to_contact' => $this->entityInformation[0]->zoho_lead_to_contact
      ];

      $zoho = new laravelZohoCrm();
      $date = str_replace(' ','T',date('Y-m-d H:m:s').'-05:00');

      foreach ($this->zohoFields as $key => $value) {
        if(isset($arrayRecod[$value])){
          $this->zohoFields[$key] = $arrayRecod[$value];
        } else {
          $this->zohoFields[$key] = null;
        }
      }

      $this->zohoFields['Fecha_de_Preregistro'] = str_replace(' ', 'T', $this->entityInformation[0]->created_at->format('Y-m-d H:m:s') ).'-05:00';
      $this->zohoFields['Fuente_de_Adquisicion'] = $this->detectResouceSubcription( [ $this->entityInformation[0]->roles ] );
      $this->zohoFields['Salutation'] = $this->detectGender($this->entityInformation[0]->gender);

      $zoho->addModuleRecord( $module, [$this->zohoFields] );
      $this->entityInformation[0]->zoho_id = $zoho->response['details']['id'];
      $this->entityInformation[0]->zoho_module = $module;
      $this->entityInformation[0]->save();
      return $zoho->response;
    }

    /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;

     */
    public function updateZoho(){
      $arrayRecod = [
        'mail' =>  $this->entityInformation[0]->mail,
        'field_no_identificacion' =>  (string)$this->identification,
        'field_nombres' =>  $this->entityInformation[0]->nombres,
        'field_apellidos' =>  $this->entityInformation[0]->apellidos,
        'field_telephone' => $this->entityInformation[0]->telephone,
        'asesor' => $this->entityInformation[0]->asesor,
        'cedula_del_asesor' => $this->entityInformation[0]->no_identificacion_asesor,
        'field_gender' => $this->entityInformation[0]->no_identificacion_gender,
        'fecha_de_registro'=>str_replace(' ', 'T', $this->entityInformation[0]->created_at->format('Y-m-d H:m:s') ).'-05:00',
        'field_birthdate' => $this->entityInformation[0]->birthdate,
        'roles' => $this->entityInformation[0]->roles,
        'to_contact' => ($this->entityInformation[0]->zoho_lead_to_contact) ? true:false,
      ];

      $zoho = new laravelZohoCrm();
      foreach ($this->zohoFields as $key => $value) {
        if(isset($arrayRecod[$value])){
          $this->zohoFields[$key] = $arrayRecod[$value];
        } else {
          $this->zohoFields[$key] = $value;
        }
      }
      $zoho->updateModuleRecord($this->entityInformation[0]->zoho_module, $this->entityInformation[0]->zoho_id, [$this->zohoFields]);
      if( $zoho->response['code'] = 'SUCCESS'){
          $this->entityInformation[0]->zoho_id = $zoho->response['details']['id'];
          $this->entityInformation[0]->zoho_module = 'Contacts';
          $this->entityInformation[0]->save();
      }

      return $zoho->response;

    }

    /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;

     */
    public function deleteZoho($module, $entity_id){
      $zoho = new laravelZohoCrm;
      $zoho->deleteModuleRecord($module,[$entity_id]);
      return $zoho;
    }

    // public function createRest($arrayRecod = array() ){
    public function createRest( ){
      $arrayRecod = [
        'name' => array( 'value'=> $this->entityInformation[0]->name ),
        'pass' => array( 'value'=> $this->entityInformation[0]->pass ),
        'mail' => array( 'value'=> $this->entityInformation[0]->mail),
        'status' => array( 'value'=> $this->entityInformation[0]->status ),
        'field_no_identificacion' => array( 'value'=> $this->entityInformation[0]->no_identificacion),
        'field_nombres' => array( 'value'=> $this->entityInformation[0]->nombres),
        'field_apellidos' => array( 'value'=> $this->entityInformation[0]->apellidos ),
        'field_acepto_terminos_y_condicio' => array( 'value' => $this->entityInformation[0]->acepto_terminos_y_condicio ),
        'field_acepto_politica_de_datos_p' => array( 'value' => $this->entityInformation[0]->acepto_politica_de_datos_p ),
        'field_birthdate' => array( 'value'=> $this->entityInformation[0]->birthdate ),
        'field_gender' => array( 'value'=> $this->entityInformation[0]->gender ),
        'field_telephone' => array( 'value'=> $this->entityInformation[0]->telephone ),
        'roles' => array( $this->entityInformation[0]->roles ),
      ];

      $client = new Client(['base_uri' => 'https://kokoriko.demodayscript.com/']);
      $headers = array(
          'headers' => [
              'Authorization' => 'Basic '.base64_encode('admin:p0p01234'),
              'Content-Type'  => 'application/json'
            ],
          'body'=>  json_encode($arrayRecod)
          );
      $res = $client->request('POST', 'api/user', $headers );

      return $res->getBody();
    }

    /**
      *
      *[0] => authenticated
      *[1] => administrator
      *[2] => facebook
      *[3] => incentives
      */
    public function detectResouceSubcription($roles = array() ){

      $array = ['facebook' => 'Facebook' ,'incentives' => 'Punto de Venta'];
      foreach ($roles as $key => $value) {
        if(isset($array[$value]))
        {
          return $array[$value];
        }
      }
      return 'Web';
    }


  /**
   * Relationship with associated rules values
   *
   * @return String;

   */
    public function detectGender($salutation = '' ){
      $array = ['Sr.' => 'Mr' ,'Sra.' => 'Mrs','Sra.'=>'Miss'];
        if( $item = array_search($salutation, $array) )
        {
          return $item;
        }
        return NULL;
    }
}
