<?php

namespace App\Kokoriko;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['identification','restaurant_code','invoice_code','product_code','sale_type','quantity','value','invoice_date_up'];

}
