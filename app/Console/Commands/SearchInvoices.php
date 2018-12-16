<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Kokoriko\File;


class SearchInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:search';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search invoices from files FTP';

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
        foreach( $file->getFolder('ventas/') as $key => $name ){
          $search_file = File::firstOrCreate(['name' => $name]);
          $search_file->getContentsFile($name,'|');
          $search_file->process('Invoice');
        }
        $this->info('Finish');
        exit;
    }
}
