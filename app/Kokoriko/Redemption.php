<?php

namespace App\Kokoriko;

use Illuminate\Database\Eloquent\Model;

use dayscript\laravelZohoCrm\laravelZohoCrm;
use App\Incentives\Core\Information;

use App\Incentives\Core\Entity;


class Redemption extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['entity_id','value'];

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   public $zohoFields =[
     'Created_By' => '',
     'Created_Time' => '',
     'Email' => '',
     'Fecha_de_Redencion' => '',
     'Last_Activity_Time' => '',
     'Modified_By' => '',
     'Modified_Time' => '',
     'Puntos' => '',
     'Record_Image' => '',
     'Name' => '',
     'Owner' => '',
     'Token' => '',
     'Valor' => '',
     'Valor_en_pesos' => ''
   ];


   /**
    * Relationship with associated goals values
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
    */
   public function entity()
   {
       return $this->belongsTo(Entity::class);
   }




   /**
    * The attributes that are mass assignable.
    *
    * @var array
    */

    public function createZoho($module = 'Redenciones'){
      $zoho = new laravelZohoCrm();
      $date = str_replace(' ','T',date('Y-m-d H:m:s').'-05:00');
      $this->zohoFields = [
        'Created_By' => '',
        'Created_Time' => '',
        'Email' => $this->entity->entityInformation[0]->mail,
        'Fecha_de_Redencion' => $date,
        'Last_Activity_Time' => null,
        'Modified_By' => null,
        'Modified_Time' => null,
        'Puntos' => (string)$this->value,
        'Record_Image' => null,
        'Name' => $this->entity->name,
        'Owner' => null,
        'Token' => $this->token,
        'Valor_en_pesos' => (string)($this->value*50)
        ];

      $zoho->addModuleRecord($module, [$this->zohoFields] );

      return $zoho;
    }





}
