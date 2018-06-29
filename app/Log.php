<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

	protected $fillable = ['table', 'user_id', 'message','client_id','type'];


   	public function createLog($datos = array())
   	{
      if ($datos['type']) {
        $datos['type'] = 'True';
      }
      else
      {
        $datos['type'] = 'False';
      }
   		$msj = $this->create([
                'table'  => $datos['table'],
                'user_id'  =>  $datos['user_id'],
                'message'  =>  $datos['message'],
                'client_id'  =>  $datos['client_id'],
                'type'  =>  $datos['type']
        ]);
   	}
}
