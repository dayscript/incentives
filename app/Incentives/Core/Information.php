<?php

namespace App\Incentives\Core;
use App\Incentives\Core\Entity;

use Illuminate\Database\Eloquent\Model;

use dayscript\laravelZohoCrm\laravelZohoCrm;
use Log;

class Information extends Model
{
    protected $table = "information";

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mail', 'telephone', 'status','zoho_lead_to_contact'];


    public function setNombresAttribute($value)
    {
        $this->attributes['nombres'] = ucwords(strtolower($value));
    }

    public function setApellidosAttribute($value)
    {
        $this->attributes['apellidos'] = ucwords(strtolower($value));
    }

    public function setAsesorAttribute($value)
    {
        $this->attributes['asesor'] = ucwords(strtolower($value));
    }

    public function setNameAttribute($value)
    {
        if( strpos($value, '.') != false ) {
            $this->attributes['name'] = explode('.',$value)[0];
        }else{
          $this->attributes['name'] = $value;
        }
    }

    public function setPassAttribute($value)
    {
      if( strpos($value, '.') != false ) {
          $this->attributes['pass'] = explode('.',$value)[0];
      }else{
        $this->attributes['pass'] = $value;
      }
    }

    public function setNoIdentificacionAttribute($value)
    {
      if( strpos($value, '.') != false ){
          $this->attributes['no_identificacion'] = explode('.',$value)[0];
      }else{
        $this->attributes['no_identificacion'] = $value;
      }
    }

    public function setTelephoneAttribute($value)
    {
      if( strpos($value, '.') != false ){
          $this->attributes['telephone'] = explode('.',$value)[0];
      }else{
        $this->attributes['telephone'] = $value;
      }
    }

    public function setNoIdentificacionAsesorAttribute($value)
    {
      if( strpos($value, '.') != false ){
          $this->attributes['no_identificacion_asesor'] = explode('.',$value)[0];
      }else{
        $this->attributes['no_identificacion_asesor'] = $value;
      }
    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function entity()
    {
        return $this->belongsToMany(Entity::class)->withPivot('entity_id');
    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function getProduct()
    {
      return Entity::where('identification', '=', $this->product_code)->first();
    }


    /**
     * Relationship with associated rules values
     *
     * @return dayscript\laravelZohoCrm\laravelZohoCrm;

     */

    public function createZoho($module){

      $zohoFields = [
        'Cantidad' => $this->quantity,
        'Created_By'=> 677524459,
        // 'Created_Time'=> '',
        // 'Email'=> '',
        // 'Email_Opt_Out'=> '',
        'Invoice'=> $this->entity->first()->zoho_id,
        'Name'=> $this->getProduct()->name,
        // 'Owner'=> '',
        // 'Last_Activity_Time'=> '',
        // 'Modified_By'=> '',
        // 'Modified_Time'=> '',
        'Producto'=> $this->getProduct()->zoho_id,
        // 'Secondary_Email'=> '',
        'Valor_Total'=> $this->value,

      ];

      $zoho = new laravelZohoCrm();
      $date = str_replace(' ','T',date('Y-m-d H:m:s').'-05:00');


      $zoho->addModuleRecord( $module, [$zohoFields] );
      $response = json_encode($zoho->response);
      Log::info($response);
      if( $zoho->response['code'] == 'SUCCESS'){
        $this->zoho_id = $zoho->response['details']['id'];
        $this->zoho_module = $module;
        $this->save();
      }
      return $zoho->response;
    }

}
