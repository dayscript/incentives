<?php

namespace App\Incentives\Utils;

// use Illuminate\Database\Eloquent\Model;

class Export
{
  public $models = [
    'App\Kokoriko\Redemption' => 'Redemption',
    'App\Kokoriko\Invoice' => 'Invoice',
    'App\Incentives\Core\Entity' => 'Entity'
  ];

}
