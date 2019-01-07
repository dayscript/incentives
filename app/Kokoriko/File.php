<?php

namespace App\Kokoriko;

use Illuminate\Database\Eloquent\Model;
use App\Incentives\Core\Information;
use App\Incentives\Core\Entity;
use App\Incentives\Core\Type;

use App\Kokoriko\Redemption;
use Storage;
use Log;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


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

      // $aux = strtotime ('-1 day', strtotime(date('Y-m-d'))); $current_date = date ( 'Y-m-d', $aux);
      // $current_date = date ( 'Y-m-d');
      $current_date = Carbon::now()->subDays(1)->format('Y-m-d');
      $current_date = '2019-01-06';
      foreach ($this->files as $num_file => $file) {
        preg_match("/([0-9]{4})\-([0-9]{2})\-([0-9]{2})/i", $file , $array);
        if (!empty($array) && $current_date == $array[0]) {
          array_push($active_files, $file);
        }
      }
      $this->files = $active_files;
      print_r($this->files);
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
          case 'Enitity':
            $type = Type::find(1);
            $file_keys = array('field_no_identificacion','name','mail','field_telephone','fecha_de_registro','cedula_del_asesor','nombre_asesor','line_break');

            foreach ($this->rows as $key => $line) {
              foreach ($line as $row => $value) {
                $new_entity[$file_keys[$row]] = $value;
              }

              try {
                $new_entity['field_no_identificacion'] = explode('.', $new_entity['field_no_identificacion'])[0];
                $entity = Entity::firstOrCreate(['identification' => $new_entity['field_no_identificacion'], 'name' => $new_entity['name'], 'type_id'=> 1 ]);

                if(!$entity->wasRecentlyCreated){
                    continue;
                }else{
                  $this->process_counter++;
                }
                $entity->subscriptionPoints();
                $entity->createInformation($new_entity);
                $entity->entityInformation;


                Log::info('Enitity Create OK: '.$entity->id);
              } catch (\Exception $e) {
                Log:info('error creando entidad desde ftp: '. $new_entity['field_no_identificacion'] . ' '.  $e->getMessage());
                continue;
              }

              try {
                $entity->createRest();
                Log::info('Entity create Drupal OK : '.$entity->identification);
                $zoho = $entity->createZoho('Leads');
                Log::info('Entity create Zoho OK : '.$entity->identification);
              } catch (\Exception $e) {
                Log::info('Entity exist in Drupal : '.$entity->identification . ' '.$e->getMessage());
              }
            }

            // $info = Information::where('zoho_id', '=', null)->where('zoho_module', '=', null)->get();
            // foreach ($info as $key => $value) {
            //   $to_create = ['identification' => $value->no_identificacion,'name' => $value->nombres." ".$value->apellidos];
            //   $entity = Entity::firstOrCreate( $to_create );
            //   $entity->entityInformation()->attach($value);
            //   $entity->createZoho('Leads');//FTP o WEB Nuevos
            //   array_push($results['entity'], $to_create);
            // }

            Log::info($this->process_counter.' processed '.$model.'.');

            break;

          case 'Invoice':
            $file_keys = array(
              'identification',
              'restaurant_code',
              'invoice_code',
              'product_code',
              'sale_type',
              'quantity',
              'value',
              'invoice_date_up',
              'break_line'
            );

            foreach( $this->rows as $key => $invoice){
              foreach ($invoice as $row => $value) {
                $new_invoice[$file_keys[$row]] = $value;
              }

              if( strpos($new_invoice['identification'], '.') != false ) {
                  $new_invoice['identification'] = explode('.',$new_invoice['identification'])[0];
              }
              if($new_invoice['identification'] == 0 || $new_invoice['identification'] == 1 ){
                continue;
              }

              if( strpos($new_invoice['quantity'], '.') != false ) {
                  $new_invoice['quantity'] = explode('.',$new_invoice['quantity'])[0];
              }
              unset($new_invoice['break_line']);

              try {
                $entity = Entity::where('identification','=',$new_invoice['identification'])
                          ->where('type_id','=',1)->first();
                if($entity){
                  print_r('Procesando:' .$new_invoice['invoice_code']. '-' . $entity->id. ' ' .$new_invoice['invoice_date_up']."\n");

                  Log::info('Factura : ' . $new_invoice['invoice_code'] );
                  $invoice = Entity::firstOrCreate(
                    [
                      'identification' => $new_invoice['invoice_code'] . '-' . $entity->id,
                      'type_id' => 2
                    ]
                  );
                  $information = new Information;
                  $information->restaurant_code = $new_invoice['restaurant_code'];
                  $information->product_code    = $new_invoice['product_code'];
                  $information->sale_type       = $new_invoice['sale_type'];
                  $information->quantity        = $new_invoice['quantity'];
                  $information->invoice_date_up = $new_invoice['invoice_date_up'];
                  $information->value           = $new_invoice['value'];
                  $information->save();
                  $invoice->entityInformation()->attach($information);
                  $invoice->entity_id = $entity->id;
                  $invoice->save();
                  print_r('OK'."\n" );
                  Log::info('Factura : OK' );
                  try {

                    if(is_null($invoice->zoho_id)){
                      $zoho = $invoice->createZohoInvoice('Invoices');
                    }else{
                      $zoho = $invoice->UpdateZohoInvoice();
                    }
                    $information->createZoho('Invoice_Items');
                    Log::info('Invoice create Zoho OK : '.$invoice->identification);
                    }catch (\Exception $e) {
                      Log::info('Invoice exist in Zoho : '.$e);
                    }
                }
              }catch (\Exception $e) {
                Log::info('Factura error :'.$e->getMessage());
                continue;
              }
            }
            break;

          case 'Contacts':
          /*Actualizar puntos*/
          /*$file_keys = array('field_no_identificacion','puntos','line_break');

            foreach ($this->rows as $key => $line) {
                  $entity = Entity::where('identification', '=', $line[0])->first();
                  //dd($entity->id);
                  $entity_rule =  DB::table('entity_rule')
                ->where('id', $entity->id)
                ->update(['value' => $line[1], 'points' => $line[1]]);

           }*/
           /*Crear en Zoho Lead Contact*/
            /*$results = [];
            $results['entity'] = [];
            $results['count'] = [];
            $info = Information::where('zoho_id', '=', '')->where('zoho_module', '=', '')->get();
            $i=0;
            foreach ($info as $key => $value) {
              $to_create = ['identification' => $value->no_identificacion,'name' => $value->nombres." ".$value->apellidos];
              $entity = Entity::firstOrCreate( $to_create );
              $entity->entityInformation()->attach($value);
              //$entity->createZoho('Leads');//FTP o WEB Nuevos
              $entity->createContactZoho('Contacts');//FTP Antiguos
              //array_push($results['entity'], $to_create);
              array_push($results['entity'], $to_create);
              $this->process_counter++;
            }
            $results['count'] = $i;
            $results['status'] = '200';
            $results['messages'] = '';
            //return $results;
            print_r($this->process_counter.' processed '.$model.'.');*/
            /*Cargar Contactos Antiguos*/
            /*$file_keys = array('field_no_identificacion','name','mail','field_telephone','field_birthdate','field_gender','fecha_de_registro','points','cedula_del_asesor','nombre_asesor','line_break');

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

              $entity->subscriptionContactPoints($new_entity);
              $entity->createInformation($new_entity);
              $entity->entityInformation;
              Log::info('Contacts Create OK: '.$entity->id);

              try {
                $entity->createRest();
                Log::info('Contacts create Drupal OK : '.$entity->identification);
                $zoho = $entity->createContactZoho('Contacts');
                Log::info('Contacts create Zoho OK : '.$entity->identification);
              } catch (\Exception $e) {
                Log::info('Contacts exist in Drupal : '.$entity->identification);
              }
            }*/

            /*$info = Information::where('zoho_id', '=', null)->where('zoho_module', '=', null)->get();
            foreach ($info as $key => $value) {
              $to_create = ['identification' => $value->no_identificacion,'name' => $value->nombres." ".$value->apellidos];
              $entity = Entity::firstOrCreate( $to_create );
              $entity->entityInformation()->attach($value);
              $entity->createContactZoho('Contacts');//FTP Antiguos
              array_push($results['entity'], $to_create);
            }*/

            //print_r($this->process_counter.' processed '.$model.'.');
          break;
          case 'Redemptions':
            $this->file = explode(PHP_EOL,Storage::disk('ftp')->get( 'redemptions.csv' ));
            $redemptions = Redemption::All();
            $contents = "";
            $this->file = $contents;

            foreach ($redemptions as $key => $item) {
              $entity = Entity::where('id', '=', $item->entity_id)->first();
              //$entity->identification
              $value = (int)$item->value*50;
              $contents = $contents.$item->token.",".$item->created_at->format('Y-m-d').","."NULL".",".$value.PHP_EOL;
            }

            $response = Storage::disk('ftp')->put( 'redemptions.csv' , $contents);
          break;

          case 'Products':
            $file_keys = [
                'code',
                'name',
                'family_code',
                'family_name',
                'line_break'
            ];
            $type = Type::find(3);
            foreach ($this->rows as $key => $line) {
              foreach ($line as $row => $value) {
                $new_entity[$file_keys[$row]] = $value;

              }
              unset($new_entity['line_break']);

              $product = Entity::firstOrCreate(['identification' => $new_entity['code'] , 'type_id' => $type->id]);
              $product->name = $new_entity['name'];
              $product->zoho_module = 'Products';
              $product->save();
              $product->entityInformation()->delete();


              $information = new Information;
              $information->product_code = $new_entity['code'];
              $information->name = $new_entity['name'];
              $information->family_name = $new_entity['family_name'];
              $information->family_code = $new_entity['family_code'];
              $information->save();
              $product->entityInformation()->attach($information);
              $product->createProductZoho();
            }

            break;

          default:
            // code...
            break;
        }
    }
}
