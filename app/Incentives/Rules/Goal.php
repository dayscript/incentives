<?php

namespace App\Incentives\Rules;

use App\Incentives\Core\Client;
use Illuminate\Database\Eloquent\Model;

class Goal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'description', 'modifier','weight','client_id'];

    /**
     * Returns associated client
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier1($value)
    {
        if($value < 80) return 0;
        elseif($value < 100)return 80;
        elseif($value < 120)return 100;
        else return 120;
    }

    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier2($value)
    {
        if($value < 80) return 0;
        elseif($value < 100)return 80;
        else return 100;
    }
}
