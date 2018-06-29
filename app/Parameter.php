<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Parameter extends Model
{
    public static function GetParameter($value,$client_id)
    {
    	$entity = Parameter::where('name','=',$value)->where('client_id','=',$client_id)->get();
    	return $entity;
    }
}
