<?php

namespace App\Incentives\Core;
use App\Incentives\Core\Entity;

use Illuminate\Database\Eloquent\Model;

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
        return $this->belongsTo(Entity::class);
    }


}
