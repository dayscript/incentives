<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Kokoriko\File;


class SearchProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Busca nuevos productos';

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
      foreach( $file->getFolder('productos/') as $key => $name ){
        $search_file = File::firstOrCreate(['name' => $name]);
        $search_file->getContentsFile($name,'|');
        $search_file->process('Products');
      }
      $this->info('Finish');
    }
}
