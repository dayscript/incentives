<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Kokoriko\File;
use Carbon\Carbon;

use App\Incentives\Core\Information;
use App\Incentives\Core\Entity;
use App\Incentives\Core\Type;

use App\Kokoriko\Redemption;
use Storage;
use Log;



class CreateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      //Crear Leads
      $this->info('Starting.');

      $file = new File;
      foreach( $file->getFolder('ventas/', $all_files = true ) as $key => $name ){

        $search_file = File::firstOrCreate(['name' => $name . ' massive_creation']);
        $rows = $search_file->getContentsFile($name,'|');

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

        foreach( $rows as $key => $invoice){
          foreach ($invoice as $row => $value) {
            $new_invoice[$file_keys[$row]] = $value;
          }

          $invoice_date = Carbon::parse($new_invoice['invoice_date_up'])->format('Y-m-d h:m:s');
          $max = Carbon::parse('2019-01-03 19:26:00');
          if( $invoice_date>= $max ){
            continue;
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
              // try {
              //   if(is_null($invoice->zoho_id)){
              //     $zoho = $invoice->createZohoInvoice('Invoices');
              //   }else{
              //     $zoho = $invoice->UpdateZohoInvoice();
              //   }
              //   $information->createZoho('Invoice_Items');
              //   Log::info('Invoice create Zoho OK : '.$invoice->identification);
              //   }catch (\Exception $e) {
              //     Log::info('Invoice exist in Zoho : '.$e);
              //   }
            }
          }catch (\Exception $e) {
            Log::info('Factura error :'.$e->getMessage());
            continue;
          }
        }
      }
      $this->info('Finish');

      exit;
    }
}
