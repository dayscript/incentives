<?php

namespace App\Incentives\Core;

use Illuminate\Database\Eloquent\Model;
use App\Incentives\Core\Entity;

class EntityData extends Model
{

  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['entity_id', 'email', 'phone', 'status', 'real_date_up'];


}
