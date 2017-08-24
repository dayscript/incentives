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
  protected $fillable = ['name', 'description', 'modifier', 'points', 'client_id'];

  /**
   * Returns associated client
   * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function client()
  {
    return $this->belongsTo(Client::class);
  }
    /**
     * Points Modifier
     * @param $value
     * @param $modifier
     * @return int
     */
    public static function modified($value, $modifier)
    {
        if ($modifier == 'modifier1') {
            $value = Rule::modifier1($value);
        }
        return $value;
    }
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier1($value)
    {
        if($value <= 10000) return 100;
        elseif($value <= 50000)return 200;
        elseif($value <= 100000)return 300;
        elseif($value <= 200000)return 600;
        elseif($value <= 500000)return 1200;
        elseif($value <= 1000000)return 2500;
        else return 6000;
    }
}
