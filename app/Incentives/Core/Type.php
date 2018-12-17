<?php

namespace App\Incentives\Core;


use App\Incentives\Core\Entity;
use Illuminate\Database\Eloquent\Model;

class Type extends Model
{
    /**
     * Relationship with associated rules values
     *
     * @return \Illuminate\Database\Eloquent\Relations\Type
     */
    function entities(){
      return $this->belongsToMany(Entity::class);
    }



}
