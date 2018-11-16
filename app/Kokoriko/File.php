<?php

namespace App\Kokoriko;

use Illuminate\Database\Eloquent\Model;
use App\Incentives\Core\Entity;
use Storage;
use Log;


class File extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','status','description'];

    public  $connection;
    public  $file;
    public  $files;
    public  $rows;
    public  $process_counter = 0;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function getFolder( $path = '/' ){
      $active_files = [];
      $this->files = Storage::disk('ftp')->allFiles($path);    
      
      //$aux = strtotime ('-1 day', strtotime(date('Y-m-d'))); $current_date = date ( 'Y-m-d', $aux);
      $current_date = date ( 'Y-m-d');

      foreach ($this->files as $num_file => $file) {
        preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/i", $file , $array);
        if (!empty($array) && $current_date == $array[0]) {
          array_push($active_files, $file);
        }
      }
      $this->files = $active_files;
      return $this->files;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function getContentsFile( $name = null, $parse = null){
      if(is_null($this->name)){
        $this->file = explode(PHP_EOL,Storage::disk('ftp')->get( $name ));
      }else{
        $this->file = explode(PHP_EOL,Storage::disk('ftp')->get( $this->name ));
      }

      $rows = [];
      
      if( is_null($parse) ){
        return $this->file;
      }
      else{
        foreach($this->file as $line => $contents){
          $aux = preg_split('/\r\n|\r|\n/', $contents);
          foreach ($aux as $key => $value) {
            if(strlen($value) !== 0){
              $rows[] = explode($parse,$value);
            }
          }
        }
        $this->rows = $rows;
        return $rows;
      }
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function process($model = null){
        switch ($model) {
          case 'entity':
            $file_keys = array('field_no_identificacion','name','mail','field_telephone','fecha_de_registro','cedula_del_asesor','nombre_asesor','line_break');

            foreach ($this->rows as $key => $line) {
              foreach ($line as $row => $value) {
                $new_entity[$file_keys[$row]] = $value;
              }

              $entity = Entity::firstOrCreate(['identification' => $new_entity['field_no_identificacion'], 'name' => $new_entity['name'] ]);

              if(!$entity->wasRecentlyCreated){
                  continue;
              }else{
                $this->process_counter++;
              }

              $entity->subscriptionPoints();
              $entity->createInformation($new_entity);
              $entity->entityInformation;
              Log::info('Enitity Create OK: '.$entity->id);

              try {
                $entity->createRest();
                $zoho = $entity->createZoho('Leads');
                Log::info('Entity create Drupal OK : '.$entity->identification);
              } catch (\Exception $e) {
                Log::info('Entity exist in Drupal : '.$entity->identification);
              }
            }
            print_r($this->process_counter.' processed '.$model.'.');
            break;
          case 'invoice':
            $file_keys = array('identification','restaurant_code','invoice_code','product_code','sale_type','quantity','value','invoice_date_up','break_line');

            foreach( $this->rows as $key => $invoice){
              foreach ($invoice as $row => $value) {
                $new_invoice[$file_keys[$row]] = $value;
              }

              if( strpos($new_invoice['identification'], '.') != false ) {
                  $new_invoice['identification'] = explode('.',$new_invoice['identification'])[0];
              }
              if( strpos($new_invoice['quantity'], '.') != false ) {
                  $new_invoice['quantity'] = explode('.',$new_invoice['quantity'])[0];
              }
              unset($new_invoice['break_line']);

              $invoice = Invoice::firstOrCreate(['invoice_code' => $new_invoice['invoice_code']]);

              if(!$invoice->wasRecentlyCreated){
                  continue;
              }else{
                $this->process_counter++;
              }

              $invoice->identification  = $new_invoice['identification'];
              $invoice->restaurant_code = $new_invoice['restaurant_code'];
              $invoice->product_code    = $new_invoice['product_code'];
              $invoice->sale_type       = $new_invoice['sale_type'];
              $invoice->quantity        = $new_invoice['quantity'];
              $invoice->value           = $new_invoice['value'];
              $invoice->invoice_date_up = $new_invoice['invoice_date_up'];
              $invoice->save();
              try {
                $invoice->createZoho('Invoices');
                Log::info('Entity create Zoho OK : '.$invoice->identification);
              } catch (\Exception $e) {
                Log::info('Entity exist in Zoho : '.$e);
              }
              
            }
            print_r($this->process_counter.' processed '.$model.'.');
            break;
          default:
            // code...
            break;
        }
    }
}
