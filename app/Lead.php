<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Incentives\Core\Entity;
use App\State;

class Lead extends Model
{
    protected $fillable = ['entity_id', 'state_id', 'number_lead'];


    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }
}
