<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Incentives\Core\Entity;
use App\Incentives\Core\Type;



class EntiiesAsingTypeMass extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entity:type';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Asing types to entities mass';

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
      $e = Entity::all();
      $t = Type::find(1);

      foreach ($e as $key => $entity) {
          $this->info($entity->id);
          $entity->type_id = $t->id;
          $this->line('OK');
      }


    }
}
