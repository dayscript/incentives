<?php

namespace App\Incentives\Core;

use App\Incentives\Rules\Goal;
use App\Incentives\Rules\Rule;
use App\Incentives\Core\EntityGoal;
use App\Incentives\Core\Information;
use App\Incentives\Core\Type;

use App\Kokoriko\Redemption;
use App\Kokoriko\Invoice;
use Carbon\Carbon;
use dayscript\laravelZohoCrm\laravelZohoCrm;

use Illuminate\Database\Eloquent\Model;

use GuzzleHttp\Pool;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;


use Log;
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
    protected $fillable = ['identification', 'name','type_id'];


    public function setNameAttribute($value)
    {
        $this->attributes['name'] = ucwords(strtolower($value));
    }


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
      'Genero'                      => 'field_gender',
      'Convertir_en_Contacto'       => 'to_contact'
    ];


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
     public $zohoFieldsInvoice =[
       'Account_Name' =>  null,
       'Cantidad_de_producto' =>  null,
       'Created_Time' =>  null,
       'Cedula_de_cliente' =>  null,
       'Codigo_de_producto' =>  null,
       'Codigo_de_restaurante' =>  null,
       'Fecha_de_Transaccion' =>  null,
       'Invoice_Number' =>  null,
       'Owner' =>  null,
       'Kokoripuntos_Acumulados' =>  null,
       'Modified_Time' =>  null,
       'Subject' =>  null,
       'Tipo_de_Venta' =>  null,
       'Valor_de_compra' => null,
       'test_email' => null
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
    * @return \Illuminate\Database\Eloquent\Relations\Type
    */
    public function type(){
      return $this->belongsTo(Type::class);
    }

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
     public function entity(){
       return $this->hasMany(Entity::class,'entity_id');
     }

     /**
      * Relationship with associated goals values
      *
      * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
      */
      public function entityMain(){
        return $this->belongsTo(Entity::class,'entity_id');
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

        $name_and_last_name = explode(' ',$this->name);


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
      $rule_points = 0;
      $invoice_total = 0;

      $date = Carbon::now()->subYears(1);


      foreach ( $this->redemptions as $key => $redemption ) {
          $redemptions[] = $redemption->value;
      }

      foreach ($this->entityGoals as $key => $value) {
        $entity_goals[] = $value->value;
      }

      foreach ($this->rules as $rule){
          $rule_points += $rule->pivot->points;
      }

      foreach ($this->entity as $key => $invoice) {
        $invoice_points = [];
        foreach( $invoice->entityInformation as $index => $item ){
          $invoice_points[] += round((int)$item->value / 1000);

        }
        
        $invoice_total += array_sum($invoice_points);
      }

      // $total = ( array_sum($invoices) + ( array_sum($entity_goals) + $rule_points ) ) - array_sum($redemptions);
      $total = ( $invoice_total + ( array_sum($entity_goals) + $rule_points ) ) - array_sum($redemptions);

      if($total < 0) $total = 0;

      return (int)number_format($total,'2','.','');
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
        'field_gender' => $this->entityInformation[0]->gender,
        'fecha_de_registro'=>str_replace(' ', 'T', $this->entityInformation[0]->created_at->format('Y-m-d H:m:s') ).'-05:00',
        'field_birthdate' => $this->entityInformation[0]->birthdate,
        'roles' => $this->entityInformation[0]->roles,
        'to_contact' => $this->zoho_lead_to_contact
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
      $this->zohoFields['Tipo_de_Usuario'] = 'Nuevo';

      $zoho->addModuleRecord( $module, [$this->zohoFields] );
      $response = json_encode($zoho->response);
      Log::info($response);
      if( $zoho->response['code'] == 'SUCCESS'){
        $this->zoho_id = $zoho->response['details']['id'];
        $this->zoho_module = $module;
        $this->save();
      }
      return $zoho->response;
    }

    /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;

     */
    public function createZohoInvoice($module){


      $valorTotal = 0;

      foreach($this->entityInformation as $key => $item ){
        $valorTotal += $item->value;
        $product_code = $item->produc_code;
        $invoice_date_up = $item->invoice_date_up;
        $sale_type = $item->sale_type;
        $restaurant_code = $item->restaurant_code;
      }

      $zoho = new laravelZohoCrm();
      $date = str_replace(' ','T',date('Y-m-d H:m:s').'-05:00');


      $this->zohoFields = [
        'Account_Name' =>  null,
        // 'Cantidad_de_producto' =>  $this->quantity,
        // 'Created_Time' =>  null,
        'Cedula_de_cliente' =>   $this->entityMain->identification,
        // 'Codigo_de_producto' =>  $this->product_code,
        'Codigo_de_restaurante' =>  $restaurant_code,
        'Fecha_de_Transaccion' =>  str_replace(' ','T', $invoice_date_up.':00-05:00'),
        'Invoice_Number' =>  $this->identification,
        'Owner' =>  null,
        'Kokoripuntos_Acumulados' =>  number_format($valorTotal / 1000, 0),
        // 'Modified_Time' =>  null,
        'Subject' =>  $this->identification,
        'Tipo_de_Venta' =>  $sale_type,
        'Valor_de_compra' => $valorTotal,
        'Contact_Name' => $this->entityMain->zoho_id,
      ];

      $zoho->addModuleRecord( $module, [$this->zohoFields] );
      $response = json_encode($zoho->response);
      Log::info($response);
      if( $zoho->response['code'] == 'SUCCESS'){
        $this->zoho_id = $zoho->response['details']['id'];
        $this->zoho_module = $module;
        $this->save();
      }
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
        'field_gender' => $this->entityInformation[0]->gender,
        'fecha_de_registro'=>str_replace(' ', 'T', $this->entityInformation[0]->created_at->format('Y-m-d H:m:s') ).'-05:00',
        'field_birthdate' => $this->entityInformation[0]->birthdate,
        'roles' => $this->entityInformation[0]->roles,
        'to_contact' => ($this->zoho_lead_to_contact) ? true:false,
      ];

      $zoho = new laravelZohoCrm();
      foreach ($this->zohoFields as $key => $value) {
        if(isset($arrayRecod[$value])){
          $this->zohoFields[$key] = $arrayRecod[$value];
        } else {
          $this->zohoFields[$key] = $value;
        }
      }

      $zoho->updateModuleRecord($this->zoho_module, $this->zoho_id, [$this->zohoFields]);
      $response = json_encode($zoho->response);
      Log::info($response);
      if( $zoho->response['code'] == 'SUCCESS'){
          if($this->zoho_module == 'Contacts'){
            $this->zoho_id = $zoho->response['details']['id'];
            $this->zoho_module = 'Contacts';
          }else{
            sleep(2);
            $this->zoho_id = $this->getSearchModuleFieldZoho('Contacts', 'id', 'Cedula', (string)$this->identification);
            $this->zoho_module = 'Contacts';
          }
          $this->save();
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

      $client = new Client(['base_uri' => env('DRUPAL_URL')]);
      $headers = array(
          'headers' => [
              'Authorization' => 'Basic '.base64_encode( env('DRUPAL_USER') . ':' . env('DRUPAL_PASS') ),
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
        /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;

     */
    public function createContactZoho($module){

      $this->identification = explode('.',$this->identification)[0];

      $arrayRecod = [
        'mail' =>  $this->entityInformation[0]->mail,
        'field_no_identificacion' =>  (string)$this->identification,
        'field_nombres' =>  $this->entityInformation[0]->nombres,
        'field_apellidos' =>  $this->entityInformation[0]->apellidos,
        'field_telephone' => $this->entityInformation[0]->telephone,
        'asesor' => $this->entityInformation[0]->asesor,
        'cedula_del_asesor' => $this->entityInformation[0]->no_identificacion_asesor,
        'field_gender' => $this->entityInformation[0]->gender,
        'fecha_de_registro'=>str_replace(' ', 'T', $this->entityInformation[0]->created_at->format('Y-m-d H:m:s') ).'-05:00',
        'field_birthdate' => $this->entityInformation[0]->birthdate,
        'roles' => $this->entityInformation[0]->roles,
        'to_contact' => true
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
      $this->zohoFields['Tipo_de_Usuario'] = 'Antiguo';
      $zoho->addModuleRecord( $module, [$this->zohoFields] );
      $response = json_encode($zoho->response);
      Log::info($response);
      if( $zoho->response['code'] == 'SUCCESS'){
        $this->zoho_id = $zoho->response['details']['id'];
        $this->zoho_module = $module;
        $this->save();
      }
      return $zoho->response;
    }
        /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function subscriptionContactPoints($entity_information = array()){
      $rule = Rule::where('name', '=', 'Usuario Antiguo')->first();
      if($rule){
        $rule->points = $entity_information['points'];
        $params = ['value'=>$rule->points, 'points'=>$rule->points,'description'=>$rule->description];
        $this->rules()->attach($rule,$params);
      }
    }

    /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;

     */
    public function getSearchModuleFieldZoho($module, $search, $field, $value){
      $zoho = new laravelZohoCrm();
      $a = $zoho->getModuleRecords($module);
      foreach ($a['data'] as $key => $item) {
        if($item[$field] == $value){
          Log::info('Zoho ID: '.$item[$search]);
          return $item[$search];
        }
      }
    }

    /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;
     */

     function createInvoiceItemZoho($module){

       $fieldsMap = array(
          array(
             'field_label '=>'Cantidad',
             'api_name    '=>' Cantidad',
             'data_type   '=>'Number',
             'incentives_field' => 'quantity',
           ),
         array(
             'field_label '=>' Created By',
             'api_name    '=>' Created_By',
             'data_type   '=>' Single Line	',
             'incentives_field' => null,
           ),
         array(
             'field_label '=>' Created Time',
             'api_name    '=>' Created_Time',
             'data_type   '=>' DateTime',
             'incentives_field' => null,
           ),
           array(
             'field_label '=>' Email',
             'api_name    '=>' Email',
             'data_type   '=>' Email',
             'incentives_field' => null,
           ),
         array(
             'field_label '=>' Email Opt Out',
             'api_name    '=>' Email_Opt_Out',
             'data_type   '=>' Boolean',
             'incentives_field' => null,
           ),
         array(
             'field_label '=>' Invoice',
             'api_name    '=>' Invoice',
             'data_type   '=>' Lookup',
             'incentives_field' => '[zoho_id]',
           ),
         array(
             'field_label '=>' Invoice Item Name',
             'api_name    '=>' Name',
             'data_type   '=>' Single Line	',
             'incentives_field' => null,
           ),
           array(
             'field_label '=>' Invoice Item Name',
             'api_name    '=>' Owner	',
             'data_type   '=>' Lookup	',
             'incentives_field' => null,
           ),
           array(
             'field_label '=>' Last Activity Time ',
             'api_name    '=>' Last_Activity_Time 	',
             'data_type   '=>' DateTime',
             'incentives_field' => null,
           ),
         array(
             'field_label '=>' Modified By',
             'api_name    '=>'  Modified_By ',
             'data_type   '=>' 	Single Line	',
             'incentives_field' => null,
           ),
           array(
             'field_label '=>' Modified Time  ',
             'api_name    '=>' Modified_Time ',
             'data_type   '=>' 	DateTime	',
             'incentives_field' => null,
           ),
         array(
             'field_label '=>' Producto ',
             'api_name    '=>'  Producto	',
             'data_type   '=>'  Lookup	',
             'incentives_field' => '[entityInformation][product_code]',
           ),
         array(
             'field_label '=>' Secondary Email  ',
             'api_name    '=>' Secondary_Email	',
             'data_type   '=>'  Email	',
             'incentives_field' => null,
           ),
           array(
             'field_label '=>' Valor Total',
             'api_name    '=>' Valor_Total',
             'data_type   '=>'  Currency',
             'incentives_field' => '[entityInformation][product_code]',
           ),
        );

        $incentives_field = 'incentives_field';
        $api_name = 'api_name';

        foreach($fieldsMap as $key => $field){

            if( !is_null( $field[$incentives_field] ) ){
              $property = '';
              preg_match_all("/\\[(.*?)\\]/", $field[$incentives_field], $matches);
              foreach ($matches[0] as $key => $item) {
                $property .= '->{'.$item.'}';
              }

            }else{
              $arrayRecord[$field[$api_name]]  = $field[$incentives_field];
            }
        }

       Log::info($response);
       if( $zoho->response['code'] == 'SUCCESS'){
         $this->zoho_id = $zoho->response['details']['id'];
         $this->zoho_module = $module;
         $this->save();
       }
       return $zoho->response;

     }


     /**
      * Relationship with associated rules values
      *
      * @return dayscript\laravelZohoCrm\laravelZohoCrm;
      */


      function createProductZoho(){

        $date = str_replace(' ','T',date('Y-m-d H:m:s').'-05:00');

        $zohoFields = array(
          'Created_By' => 3609958000001218233,
          'Created_Time' => $date,
          'Description' => '',
          'Modified_By' => 677524459,
          'Modified_Time' => $date,
          'Product_Active' => TRUE,
          'Product_Category' => $this->entityInformation[0]->family_name,
          'Product_Code' => $this->entityInformation[0]->product_code,
          'Record_Image' => NULL,
          'Product_Name' => $this->name,
          'Owner' => NULL,
          // 'Vendor_Name' => 3609958000001218233,
        );

        $zoho = new laravelZohoCrm();
        if( is_null($this->zoho_id) ){
            $zoho->addModuleRecord( $this->zoho_module, [$zohoFields] );
        }else{
            $zoho->updateModuleRecord($this->zoho_module, $this->zoho_id, [$zohoFields]);
        }

        $response = json_encode($zoho->response);

        Log::info($response);
        if( $zoho->response['code'] == 'SUCCESS'){
          $this->zoho_id = $zoho->response['details']['id'];
          $this->zoho_module = $this->zoho_module;
          $this->save();
        }
        return $zoho->response;
      }


}
