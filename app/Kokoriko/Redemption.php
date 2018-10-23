<?php

namespace App\Kokoriko;

use Illuminate\Database\Eloquent\Model;

class Redemption extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['entity_id','value'];



}
