<?php

namespace App\Incentives\Compute;

use Illuminate\Database\Eloquent\Model;
use App\Incentives\Core\Client;


class Vars extends Model
{


  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = ['name', 'machine_name', 'value', 'type', 'description', 'vars_one_id', 'vars_two_id', 'client_id', 'operator'];



    /**
     * Return types of Vars
     *
     */
    public function getTypes(){
      return [
        'single' => 'Simple',
        'constant' => 'Constante',
        'bool' => 'Verdadero/Falso',
        'percentage' => 'Porcentaje',
        'assignment' => 'Asignación',
        'composite' => 'Compuesta'
      ];
    }

    /**
     * Return types of Vars
     *
     */
    public function getOperators(){
      return [
        'sum' => 'Suma',
        'rest' => 'Resta',
        'mult' => 'Multiplicación',
        'div' => 'División'
      ];
    }

    /**
     * Get the atribute's machine_name.
     *
     * @param  string  $value
     * @return string
     */
    public function getMachineNameAttribute($value)
    {
        return $value;
    }

    /**
     * Get the atribute's Name.
     *
     * @param  string  $value
     * @return string
     */
    public function getNameAttribute($value)
    {

        return ucwords($value);
    }

    /**
    * Always capitalize the first name when we save it to the database
    */
     public function setMachineNameAttribute($value) {

       $unwanted_characters = array(    'Š'=>'S', 'š'=>'s', 'Ž'=>'Z', 'ž'=>'z', 'À'=>'A', 'Á'=>'A', 'Â'=>'A', 'Ã'=>'A', 'Ä'=>'A', 'Å'=>'A', 'Æ'=>'A', 'Ç'=>'C', 'È'=>'E', 'É'=>'E',
                            'Ê'=>'E', 'Ë'=>'E', 'Ì'=>'I', 'Í'=>'I', 'Î'=>'I', 'Ï'=>'I', 'Ñ'=>'N', 'Ò'=>'O', 'Ó'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'O', 'Ø'=>'O', 'Ù'=>'U',
                            'Ú'=>'U', 'Û'=>'U', 'Ü'=>'U', 'Ý'=>'Y', 'Þ'=>'B', 'ß'=>'Ss', 'à'=>'a', 'á'=>'a', 'â'=>'a', 'ã'=>'a', 'ä'=>'a', 'å'=>'a', 'æ'=>'a', 'ç'=>'c',
                            'è'=>'e', 'é'=>'e', 'ê'=>'e', 'ë'=>'e', 'ì'=>'i', 'í'=>'i', 'î'=>'i', 'ï'=>'i', 'ð'=>'o', 'ñ'=>'n', 'ò'=>'o', 'ó'=>'o', 'ô'=>'o', 'õ'=>'o',
                            'ö'=>'o', 'ø'=>'o', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'ý'=>'y', 'þ'=>'b', 'ÿ'=>'y' );

       $this->attributes['machine_name'] = str_replace(' ', '_', strtr(strtolower($this->attributes['name']),$unwanted_characters));
     }

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
    public function vars_one()
    {
      return $this->belongsTo(Vars::class);
    }

    /**
     * Returns associated client
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function vars_two()
    {
      return $this->belongsTo(Vars::class);
    }
}
