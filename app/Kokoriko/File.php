<?php

namespace App\Kokoriko;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    //

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name','status','description'];
}
