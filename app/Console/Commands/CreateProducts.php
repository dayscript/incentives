<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Incentives\Core\Information;
use App\Incentives\Core\Entity;
use App\Incentives\Core\Type;
use App\Kokoriko\Redemption;
use Storage;
use Log;

class CreateProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:create-products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'search all products without zoho_id and send to zoho CRM';

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
        $entities = Entity::where('type_id','=',3)->whereNull('zoho_id')->get();
        $this->info('Starting products. '.count($entities). 'to process');
        foreach ($entities as $key => $entity) {
          $result = $entity->createProductZoho();
          $this->line($result['code']);
        }
    }
}
