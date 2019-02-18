<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Kokoriko\File;


class CreateInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
      foreach( $file->getFolder('ventas/') as $key => $name ){
        $search_file = File::firstOrCreate(['name' => $name . 'massive_creation']);
        $content = $search_file->getContentsFile($name,';');
        var_dump($content);
        exit;
      }
      $this->info('Finish');

      exit;
    }
}
