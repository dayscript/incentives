<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Incentives\Core\Entity;


class FixZohoEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:fixEntities';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca entidades de tipo contacto que no hayan sido creadas en zoho';

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
      $this->info('Iniciando Contatos:');
      $entities = Entity::where('type_id',1,'=')->where('zoho_lead_to_contact',1,'=')->whereNull('zoho_id')->get();
      $this->info(count($entities));
      foreach ($entities as $key => $entity) {
        $this->line($entity->id);
        $entity->entityInformation;
        $entity->createContactZoho('Contacts');
      }
      $this->info('Terminado');

      $this->info('Iniciando leads:');
      $entities = Entity::where('type_id',1,'=')->where('zoho_lead_to_contact',0,'=')->whereNull('zoho_id')->get();
      $this->info(count($entities));
      foreach ($entities as $key => $entity) {
        $this->line($entity->id);
        $entity->entityInformation;
        $entity->createZoho('Leads');
      }
      $this->info('Terminado');

      $this->info('Iniciando Facturas');
      $entities = Entity::where('type_id',2,'=')->whereNull('zoho_id')->get();
      foreach ($entities as $key => $entity) {
        $this->line($entity->id);
        $entity->entityInformation;
        $entity->createZohoInvoice('Invoices');
        if($entity->zoho_id != null){
            $this->line('Creando Invoice Items');
            $this->line(count($entity->entityInformation));
            foreach ($entity->entityInformation as $key => $information) {
              $information->createZoho('Invoice_Items');
              $this->line($information->id);
            }
          }
      }
      $this->info('Terminado');
    }



}
