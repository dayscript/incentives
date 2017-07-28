<?php

namespace App\Incentives\Rules;

use App\Incentives\Core\Client;
use App\Incentives\Core\Entity;
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
     * Entities relationship
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function entities()
    {
        return $this->belongsToMany(Entity::class)->withPivot('id','value','real','date')->withTimestamps();
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
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier3($value)
    {
        if($value < 80) return 0;
        elseif($value < 90)return 90;
        else return 100;
    }
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier4($value)
    {
        if($value < 90) return 0;
        else return 100;
    }
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier5($value)
    {
        if($value <= 120) return $value;
        else return 125;
    }
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier6($value)
    {
        if($value <= 100) return $value;
        else return 100;
    }
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier7($value)
    {
        if($value <= 0) return 0;
        else return $value;
    }
}
