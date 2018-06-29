<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Incentives\Core\Client;

class Indicator extends Model
{
    protected $fillable = ['name', 'client_id', 'type_id'];

    public function client()
  	{
    	return $this->belongsTo(Client::class);
  	}
}
