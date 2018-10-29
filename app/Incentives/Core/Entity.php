<?php

namespace App\Incentives\Core;

use App\Incentives\Rules\Goal;
use App\Incentives\Rules\Rule;
use App\Incentives\Core\EntityGoal;
use App\Incentives\Core\EntityData;
use App\Kokoriko\Redemption;
use App\Kokoriko\Invoice;
use Carbon\Carbon;

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
     * Relationship with associated goals values
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

     public function entityGoals(){
       return $this->hasMany(EntityGoal::class,'entity_id');
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
      $entity_goals =[];
      $total = 0;
      $date = Carbon::now()->subYears(1);


      foreach ( $this->invoices->where('invoice_date_up','>', $date->format('Y-m-d') ) as $key => $invoice ) {
          $invoices[] = (int)$invoice->value;
      }

      foreach ( $this->redemptions->where('created_at','>', $date->format('Y-m-d') )  as $key => $redemption ) {
          $redemptions[] = (int)$redemption->value;
      }

      foreach ($this->entityGoals as $key => $value) {
        $entity_goals[] = (int)$value->value;
      }

      foreach ($this->rules as $rule){
          $total += $rule->pivot->points;
      }

      return (int)number_format( ( array_sum($invoices)  / 1000 ) + ( array_sum($entity_goals) + $total ) - array_sum($redemptions),0 ) ;
    }

    public function overcomePoints(){
        $invoices_overcome = [];
        $date_from = Carbon::now()->subYears(364);
        $date_to = Carbon::now()->subYears(330);
        $invoices = $this->invoices->where('invoice_date_up', '>=' , $date_from->format('Y-m-d'))
                                   ->where('invoice_date_up', '<=' , $date_to->format('Y-m-d'));

         foreach ( $invoices as $key => $invoice ) {
             $invoices_overcome[] = (int)$invoice->value;
         }

        return (int)number_format( array_sum($invoices_overcome)  / 1000 ) ;

    }

    public function subscriptionPoints(){
      $rule = Rule::where('name', '=', 'Activación de cuenta')->first();
      if($rule){
        $params = ['value'=>$rule->points,'points'=>$rule->points,'description'=>$rule->description];
        $this->rules()->attach($rule,$params);
      }
    }
}
