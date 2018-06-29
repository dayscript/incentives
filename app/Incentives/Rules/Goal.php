<?php

namespace App\Incentives\Rules;

use App\Incentives\Core\Client;
use App\Incentives\Core\Entity;
use Illuminate\Database\Eloquent\Model;
use App\Role;
use App\Type_Goal;


class Goal extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = ['name', 'description', 'modifier','weight','client_id', 'indicator_id', 'date_start', 'date_end', 'rol_id','typegoal_id', 'point'];

    /**
     * Returns associated client
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }
    /**
     * Returns associated client
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function rol()
    {
        return $this->belongsTo(Role::class);
    }
    /**
     * Returns associated client
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type_Goal::class);
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
     * @param $modifier
     * @return int
     */
    public static function modified($value, $modifier)
    {
        if ($modifier == 'modifier1') {
            $value = Goal::modifier1($value);
        } else if ($modifier == 'modifier2') {
            $value = Goal::modifier2($value);
        } else if ($modifier == 'modifier3') {
            $value = Goal::modifier3($value);
        } else if ($modifier == 'modifier4') {
            $value = Goal::modifier4($value);
        } else if ($modifier == 'modifier5') {
            $value = Goal::modifier5($value);
        } else if ($modifier == 'modifier6') {
            $value = Goal::modifier6($value);
        } else if ($modifier == 'modifier7') {
            $value = Goal::modifier7($value);
        } else if ($modifier == 'modifier8') {
            $value = Goal::modifier8($value);
        } else if ($modifier == 'modifier9') {
            $value = Goal::modifier9($value);
        } else if ($modifier == 'modifier10') {
            $value = Goal::modifier10($value);
        } else if ($modifier == 'modifier11') {
            $value = Goal::modifier11($value);
        }
        return $value;
    }
    /**
     * Percentage Modifier
     * @param $value
     * @param $modifier
     * @return int
     */
    public static function maxmodified($point,$value)
    {
        return $point*$value;
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
        if($value < 85) return 0;
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
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier8($value)
    {
        if($value <= 0) return 0;
        elseif ($value<=120)return $value;
        else return $value*1.05;
    }
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier9($value)
    {
        if($value < 100) return 0;
        else return 100;
    }
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier10($value)
    {
        if($value <= 0) return 0;
        elseif ($value<100)return 0;
        else return 100;
    }
    /**
     * Percentage Modifier
     * @param $value
     * @return int
     */
    public static function modifier11($value)
    {
        if($value <= 0) return 0;
        elseif ($value<85)return 0;
        else return 100;
    }
}
