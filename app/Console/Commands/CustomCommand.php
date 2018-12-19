<?php

namespace App\Console\Commands;



use Illuminate\Console\Command;

use App\Incentives\Core\Entity;

class CustomCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom:command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to customs functions';

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
        $entities = Entity::where('type_id','=',1)->get();

        foreach($entities as $index => $entity){
          print_r($entity->id . "\n");
          $entity->zoho_id = $entity->entityInformation[0]->zoho_id;
          $entity->zoho_module = $entity->entityInformation[0]->zoho_module;
          $entity->zoho_lead_to_contact = $entity->entityInformation[0]->zoho_lead_to_contact;
          $entity->save();
          print_r('OK');
        }
    }
}
