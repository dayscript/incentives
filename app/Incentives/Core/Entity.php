<?php

namespace App\Incentives\Core;

use App\Incentives\Rules\Goal;
use App\Incentives\Rules\Rule;
use App\Incentives\Core\EntityGoal;
use App\Incentives\Core\EntityData;
use App\Kokoriko\Redemption;
use App\Kokoriko\Invoice;
use Carbon\Carbon;
use dayscript\laravelZohoCrm\laravelZohoCrm;

use Illuminate\Database\Eloquent\Model;

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
      'Cedula_Asesor'               => 'cedula_del_asesor',
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
      'Twitter'                     => NULL
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

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function data()
    {
        return $this->hasOne(EntityData::class);
    }

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
    public function createZoho($module,$arrayRecod){
      $zoho = new laravelZohoCrm();
      $date = str_replace(' ','T',date('Y-m-d H:m:s').'-05:00');

      foreach ($this->zohoFields as $key => $value) {
        if(isset($arrayRecod[$value])){
          $this->zohoFields[$key] = $arrayRecod[$value];
        } else {
          $this->zohoFields[$key] = $value;
        }
      }

      $this->zohoFields['Fecha_de_Preregistro'] = $date;
      $this->zohoFields['Fuente_de_Adquisicion'] = $this->detectResouceSubcription($arrayRecod['roles']);
      $this->zohoFields['Salutation'] = $this->detectGender($arrayRecod['field_gender']);

      $zoho->addModuleRecord($module, [$this->zohoFields] );
      return $zoho;
    }

    /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;

     */
    public function updateZoho($module, $entity_id){
      $zoho = new laravelZohoCrm();
      foreach ($this->zohoFields as $key => $value) {
        if(isset($arrayRecod[$value])){
          $this->zohoFields[$key] = $arrayRecod[$value];
        } else {
          $this->zohoFields[$key] = $value;
        }
      }
      $zoho->updateModuleRecord($module, $entity_id, $recordArray);
      return $zoho;

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
