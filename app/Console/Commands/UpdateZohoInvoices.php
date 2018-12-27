<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Incentives\Core\Entity;


class UpdateZohoInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'zoho:update-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Search invoices without Zoho Id';

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
        $this->info('Start');
        $invoices = Entity::where('type_id','=',2)->limit(1);
        foreach ($invoices as $key => $invoice) {
          $this->info('Sending number: '. $invoice->identification);
          $invoice->createZohoInvoice('Invoices');
          foreach ($invoice->entityInformation as $index => $information) {
            $this->info('Sending information: '. $information->id);
            $information->createZoho('Invoice_Items');
          }
        }
        $this->info('Finish.');
    }
}
