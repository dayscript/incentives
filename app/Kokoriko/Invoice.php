<?php

namespace App\Kokoriko;

use Illuminate\Database\Eloquent\Model;
use dayscript\laravelZohoCrm\laravelZohoCrm;
use App\Incentives\Core\Information;
use App\Incentives\Core\Entity;


class Invoice extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['identification','restaurant_code','invoice_code','product_code','sale_type','quantity','value','invoice_date_up'];




  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
   public $zohoFields =[
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
     'Valor_de_compra' => null
   ];

   /**
    * Relationship with associated rules values
    *
    * @return dayscript\laravelZohoCrm\laravelZohoCrm;

    */
   public function createZoho($module){


     $zoho = new laravelZohoCrm();
     $date = str_replace(' ','T',date('Y-m-d H:m:s').'-05:00');

     $this->zohoFields = [
       'Account_Name' =>  null,
       'Cantidad_de_producto' =>  $this->quantity,
       // 'Created_Time' =>  null,
       'Cedula_de_cliente' =>  $this->identification,
       'Codigo_de_producto' =>  $this->product_code,
       'Codigo_de_restaurante' =>  $this->restaurant_code,
       'Fecha_de_Transaccion' =>  str_replace(' ','T', $this->invoice_date_up.':00-05:00'),
       'Invoice_Number' =>  $this->invoice_code,
       'Owner' =>  null,
       'Kokoripuntos_Acumulados' =>  number_format($this->value / 1000, 0),
       // 'Modified_Time' =>  null,
       'Subject' =>  $this->identification,
       'Tipo_de_Venta' =>  $this->sale_type,
       'Valor_de_compra' => $this->value
     ];


     $zoho->addModuleRecord( $module, [$this->zohoFields] );
     if($zoho->response['code'] !== 'SUCCESS'){
       Log::info('Invoce Zoho Faild: '. $zoho->response['code']);
     }
     $this->zoho_id = $zoho->response['details']['id'];
     $this->zoho_module = $module;
     $this->save();
     return $zoho->response;
   }
}
