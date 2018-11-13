<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Incentives\Core\Entity;
use App\Incentives\Core\Information;
use App\Kokoriko\File;

class SearchEntities extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'entity:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search entities from files FTP';

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
        $file = new File;
        foreach( $file->getFolder('preregistro/') as $key => $name ){
          $search_file = File::firstOrCreate(['name' => $name]);
          $search_file->getContentsFile(null,';');
          $search_file->process('entity');
          exit;
        }

      }

}
