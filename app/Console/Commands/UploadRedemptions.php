<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Incentives\Core\Entity;
use App\Incentives\Core\Information;
use App\Kokoriko\File;

class UploadRedemptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'redemptions:upload';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'load redemptios to csv file';

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
      $name = 'redemptions.csv';
      $search_file = File::firstOrCreate(['name' => $name]);
      $search_file->process('Redemptions');
      $this->info('redemptions load finish');
      exit;
    }
}
