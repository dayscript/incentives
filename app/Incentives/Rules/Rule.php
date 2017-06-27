<?php

namespace App\Incentives\Rules;

use App\Incentives\Core\Client;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'description', 'points', 'client_id'];

  /**
   * Returns associated client
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function client()
  {
    return $this->belongsTo(Client::class);
  }
}
