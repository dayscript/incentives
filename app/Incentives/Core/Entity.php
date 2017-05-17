<?php

namespace App\Incentives\Core;

use App\Incentives\Rules\Rule;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['identification'];

    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function rules()
    {
        return $this->belongsToMany(Rule::class)->withPivot('value','points','description')->withTimestamps();
    }

    public function totalpoints()
    {
        $total = 0;
        foreach ($this->rules as $rule){
            $total += $rule->pivot->points;
        }
        return $total;
    }
}
