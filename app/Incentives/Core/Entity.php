<?php

namespace App\Incentives\Core;

use App\Incentives\Rules\Goal;
use App\Incentives\Rules\Rule;
use App\Incentives\Core\EntityData;
use App\Kokoriko\Redemption;
use App\Kokoriko\Invoice;


use Illuminate\Database\Eloquent\Model;

/**
 * @property mixed rules
 */
class Entity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['identification', 'name'];

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany(Rule::class)->withPivot('id','value','points','description')->withTimestamps();
    }
    /**
     * Relationship with associated goals values
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function goals()
    {
        return $this->belongsToMany(Goal::class)->withPivot('id','value','real','date')->withTimestamps();
    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function data()
    {
        return $this->hasOne(EntityData::class);
    }

    public function totalpoints()
    {
        $total = 0;
        foreach ($this->rules as $rule){
            $total += $rule->pivot->points;
        }
        return $total;
    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function redemptions()
    {
        return $this->hasMany(Redemption::class);
    }

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class,'identification','identification');
    }


    public function getPoints(){
      $invoices = [];
      $redemptions = [];

      foreach ( $this->invoices as $key => $invoice ) {
          $invoices[] = (int)$invoice->value;
      }

      foreach ( $this->redemptions as $key => $redemption ) {
          $redemptions[] = (int)$redemption->value;
      }

      return number_format( ( array_sum($invoices)  / 1000 ) - array_sum($redemptions),0 ) ;
    }


}
