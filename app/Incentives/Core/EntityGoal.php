<?php

namespace App\Incentives\Core;

use Illuminate\Database\Eloquent\Model;

class EntityGoal extends Model
{
    protected $table = 'entity_goal';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['entity_id', 'goal_id'];
}
