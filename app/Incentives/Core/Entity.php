<?php

namespace App\Incentives\Core;

use App\Incentives\Rules\Goal;
use App\Incentives\Rules\Rule;
use App\State;
use App\Role;
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
    protected $fillable = ['identification', 'name', 'last_name', 'email', 'role_id', 'state_id'];

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

    public function totalpoints()
    {
        $total = 0;
        foreach ($this->rules as $rule){
            $total += $rule->pivot->points;
        }
        return $total;
    }
    public function state()
    {
        return $this->belongsTo(State::class);
    }
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
