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
      $this->info('Iniciando:');
      $entities = Entity::where('type_id',1,'=')->where('zoho_lead_to_contact',1,'=')->whereNull('zoho_id')->get();
      foreach ($entities as $key => $entity) {
        $this->line($entity->id);
        $entity->entityInformation;
        $entity->createContactZoho('Contacts');
      }
      $this->info('Terminado');
    }
}
