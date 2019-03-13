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

class CreateInIncentives extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:create-leads';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'send leads without zoho id';

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
        $entities = Entity::where('type_id','=',1)->whereNull('zoho_id')->get();
        $this->info('Starting. '.count($entities). 'to process');
        foreach ($entities as $key => $entity) {
          $result = $entity->createZoho('Leads');
          $this->line($result['code']);
        }
    }
}
