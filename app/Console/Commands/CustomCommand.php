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
      $entitys = Entity::where("type_id",'=',2)->where('created_at','like','2019-01-07%')->get();
      $return = array();
      foreach ($entitys as $key => $value) {
        try {
          $return[$value->id] = $value->updateZohoInvoice();
        } catch (\Exception $e) {
          $return[$value->id] = $e->getMessage();
        }
      }
    }
}
