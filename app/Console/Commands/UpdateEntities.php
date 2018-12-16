<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Incentives\Core\Entity;
use App\Kokoriko\Invoice;
use App\Kokoriko\Redemption;

use Simplon\Mysql\PDOConnector;
use Simplon\Mysql\Mysql;


use Storage;
use Log;


class UpdateEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entity:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualizar entidades desde un archivo csv';

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
        $this->info('Starting.');

        $pdo = new PDOConnector(
              'localhost', // server
              'root',      // user
              'Z7pVgH',      // password
              'kokorikoold'   // database
          );
        $pdoConn = $pdo->connect('utf8', []); // charset, options

        //
        // you could now interact with PDO for instance setting attributes etc:
        // $pdoConn->setAttribute($attribute, $value);
        //

        $dbConn = new Mysql($pdoConn);

        $cedulas = $dbConn->fetchRowMany('SELECT cedula FROM ju1 limit 19026,2000');
        $cont = 0;
        foreach( $cedulas as $cedula ){
          $cont ++;

          $cedula = $cedula['cedula'];

          $this->question($cont.' - Buscando: '. $cedula);
          $entity = Entity::where('identification','=',$cedula)->get()->first();

          if(!$entity){
            $this->error($cedula. ' No encontrado');
            $this->line('');
            continue;
          }

          // Starting Update Personal Information
          $this->info('Actualizando entidad');
          $personalData = (object)$dbConn->fetchRow('SELECT * FROM ju1 where cedula = :cedula;', ['cedula' => $cedula]);
          $entity->name = $personalData->nombres . ' ' . $personalData->apellidos;
          $entity->save();
          $entity->entityInformation[0]->nombres   = $personalData->nombres;
          $entity->entityInformation[0]->apellidos = $personalData->apellidos;
          $entity->entityInformation[0]->telephone = $personalData->telefono;
          $entity->entityInformation[0]->birthdate = $personalData->fecha_nacimiento;
          $entity->entityInformation[0]->save();
          // $zoho = $entity->updateZoho();
          // End Update Personal Information
          $this->info('Entidad actualizada');

          // Starting Create Invoices
          $cursor = $dbConn->fetchRowMany('SELECT * FROM transacciones where id_ju1 = :cedula;', ['cedula' => $cedula]);

          $transactions = [];
          $transactionPoints = 0;

          if($cursor){
            $this->info('Actualizando transacciones');
            $puntos = 0;
            foreach ($cursor as $transaction)
            {
                $transaction = (object)$transaction;

                $puntos += $transaction->puntos_acum;
                // if($transaction->factura == ''){
                //   $transaction->factura = NULL;
                // }

                // try {
                //
                //   $invoice = Invoice::firstOrCreate( ['kokoriko_id' =>  $transaction->id] );
                //   $invoice->identification  = $transaction->id_ju1;
                //   $invoice->restaurant_code = NULL;
                //   $invoice->product_code    = $transaction->id_jp1;
                //   $invoice->sale_type       = $transaction->canal;
                //   $invoice->quantity        = $transaction->cantidad;
                //   $invoice->value           = $transaction->monto;
                //   $invoice->points          = $transaction->puntos_acum;
                //   $invoice->invoice_date_up = $transaction->fecha_inicio;
                //   $invoice->invoice_code    = $transaction->factura;
                //   $invoice->save();
                // } catch (\Exception $e) {
                //   Log::info( 'error: ' . $transaction->id .' '. $e->getMessage() );
                //   continue;
                // }



                //$zoho = $incoide->createZoho();
            }
            $rule = Rule::find(5);
            $entity->rules()->attach($rule->id, ['value' => $puntos, 'points' => $puntos, 'description' => $rule->description]);
            $this->info('Transacciones actualizadas');

          }
          // End Create Invoices

          $cursor = $dbConn->fetchRowMany('SELECT * FROM redenciones where id_ju = :cedula;', ['cedula' => $cedula]);
          $redemptions = [];
          $puntosRedemp = 0;
          if($cursor){
            $this->info('Actualizando redenciones');
            foreach ($cursor as $result)
            {
                $redem = (object)$result;

                if($redem->puntos == 0) continue;

                try {

                  $redemption = Redemption::firstOrCreate(['token'=> $redem->codigo,'value'=>$redem->puntos]);
                  $redemption->entity_id = $entity->id;
                  $redemption->value = $redem->puntos;
                  $redemption->token = $redem->codigo;
                  $redemption->save();
                } catch (\Exception $e) {
                  Log::info( 'error: ' . $redem->id .' '. $e->getMessage() );
                  continue;
                }

                // $zoho = $redemption->createZoho();
            }
            $this->info('Redenciones actualizadas');

          }
        }
        // $indexes = array(
        //   'id',
        //   'cedula',
        //   'nombres',
        //   'apellidos',
        //   'file_foto_perfil',
        //   'pass_clave',
        //   'genero',
        //   'anio',
        //   'mes',
        //   'dia',
        //   'fecha_nacimiento',
        //   'celular',
        //   'telefono',
        //   'id_jg1',
        //   'email',
        //   'direccion',
        //   'tiene_hijos',
        //   'cuantos_hijos',
        //   'id_c2',
        //   'integrantes',
        //   'terminos',
        //   'estado',
        //   'fecha_registro',
        //   'fecha_actualizacion',
        //   'fecha_preregistro'
        // );
        //
        // $contents = Storage::disk('public')->get('clientes.csv');
        // $contents = explode(PHP_EOL,$contents);
        // $headers = $contents[0];
        // unset($contents[0]);
        // $cont = 0;
        //
        // foreach ($contents as $key => $item) {
        //   $item = explode(',',$item);
        //   $element = new \stdClass();
        //   foreach( $indexes as $key => $index ){
        //     $element->$index = $item[$key];
        //   }
        //
        //
        //   $entity = Entity::where('identification','=',$element->id)->get()->first();
        //   // var_dump($entity);
        //   if($entity){
        //     $cont ++;
        //
        //     $this->info($cont.' Actualizando: '.$element->id);
        //     $entity->name = $element->nombres . ' ' . $element->apellidos;
        //     $entity->save();
        //     $entity->entityInformation[0]->nombres = $element->nombres;
        //     $entity->entityInformation[0]->apellidos = $element->apellidos;
        //     $entity->entityInformation[0]->save();
        //     // $entity->updateZoho();
        //     //
        //   }else{
        //     $this->line($element->id.' No existe.');
        //     Log::info($element->id.' No existe.');
        //
        //   }
        //
        // }

    }
}
